<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model{

    use \TraitsFunc;

    protected $table = 'delegates';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Shop(){
        return $this->belongsTo('App\Models\Shop','shop_id');
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['name']) && !empty($input['name'])) {
            $source->where('name', 'LIKE', '%' . $input['name'] . '%');
        }
        if (isset($input['phone']) && $input['phone'] != 0) {
            $source->where('phone', $input['phone']);
        }
        if (isset($input['commission']) && $input['commission'] != 0) {
            $source->where('commission', $input['commission']);
        }
        if (isset($input['shop_id']) && !empty($input['shop_id'])) {
            $source->where('shop_id', $input['shop_id']);
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }


    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = ucwords($source->name);
        $data->phone = $source->phone;
        $data->address = $source->address;
        $data->commission = $source->commission;
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->Shop->title;
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
        $userObj->commission = $input['commission'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->shop_id = $input['shop_id'];
        $userObj->created_at = DATE_TIME;
        $userObj->created_by = USER_ID;
        $userObj->save();
        return $userObj->id;
    }
}
