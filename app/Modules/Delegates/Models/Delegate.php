<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model{

    use \TraitsFunc;

    protected $table = 'delegates';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['name']) && !empty($input['name'])) {
            $source->where('name', 'LIKE', '%' . $input['name'] . '%');
        }
        if (isset($input['phone']) && $input['phone'] != 0) {
            $source->where('phone', $input['phone']);
        }
        if (isset($input['shop_id']) && !empty($input['shop_id'])) {
            $source->where('shop_id', 'LIKE', '%' .','.$input['shop_id'] . '%')
                   ->orWhere('shop_id', 'LIKE', '%' .$input['shop_id'].',' . '%')
                   ->orWhere('shop_id', $input['shop_id']);
        }

        if (!IS_ADMIN) {
            $source->where('shop_id', 'LIKE', '%' .','.\Session::get('shop_id') . '%')
                   ->orWhere('shop_id', 'LIKE', '%' .\Session::get('shop_id').',' . '%')
                   ->orWhere('shop_id', \Session::get('shop_id'));
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$withPaginate);
    }

    static function generateObj($source,$withPaginate=null){
        if($withPaginate != null){
            $sourceArr = $source->get();
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        if($withPaginate == null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;

        return $data;
    }


    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = ucwords($source->name);
        $data->phone = $source->phone;
        $data->address = $source->address;
        $data->shops_id = explode(',', $source->shop_id);
        $data->shops = Shop::whereIn('id',explode(',', $source->shop_id))->get();
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function checkUserByPhone($phone, $notId = false){
        $dataObj = self::NotDeleted()->where('phone','!=',null)->where('phone',$phone);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function createOneDelegate(){
        $input = \Input::all();

        $userObj = new self();
        $userObj->phone = $input['phone'];
        $userObj->address = $input['address'];
        $userObj->name = $input['name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->shop_id = implode(',', $input['shop_id']);
        $userObj->created_at = DATE_TIME;
        $userObj->created_by = USER_ID;
        $userObj->save();
        return $userObj->id;
    }
}
