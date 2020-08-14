<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model{

    use \TraitsFunc;

    protected $table = 'currency';
    protected $primaryKey = 'id';
    public $timestamps = false;


    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['name']) && !empty($input['name'])) {
            $source->where('name', 'LIKE', '%' . $input['name'] . '%');
        }
        if (isset($input['code']) && !empty($input['code'])) {
            $source->where('code', $input['code']);
        }
        if (isset($input['is_active']) && $input['is_active'] != -1) {
            $source->where('is_active', $input['is_active']);
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
        if($withPaginate==null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;
        return $data;
    }


    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = ucwords($source->name);
        $data->code = $source->code;
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function checkByName($name, $notId = false){
        $dataObj = self::NotDeleted()->where('name','!=',null)->where('name',$name);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

     static function checkByCode($code, $notId = false){
        $dataObj = self::NotDeleted()->where('code','!=',null)->where('code',$code);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function createOneCurrency(){
        $input = \Input::all();

        $userObj = new self();
        $userObj->code = strtoupper($input['code']);
        $userObj->name = $input['name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->created_at = DATE_TIME;
        $userObj->created_by = USER_ID;
        $userObj->save();
        return $userObj->id;
    }
}
