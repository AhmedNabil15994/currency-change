<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model{

    use \TraitsFunc;

    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Shop(){
        return $this->belongsTo('App\Models\Shop','shop_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['name']) && !empty($input['name'])) {
            $source->where('name', 'LIKE', '%' . $input['name'] . '%');
        }
        if (isset($input['account_number']) && !empty($input['account_number'])) {
            $source->where('account_number', $input['account_number']);
        }
        if (isset($input['shop_id']) && $input['shop_id'] != 0) {
            $source->where('shop_id', $input['shop_id']);
        }
        if (isset($input['currency_id']) && $input['currency_id'] != 0) {
            $source->where('currency_id', $input['currency_id']);
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

    static function checkByAccountNumber($account_number, $notId = false){
        $dataObj = self::NotDeleted()->where('account_number','!=',null)->where('account_number',$account_number);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->Shop->title;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->name = $source->name;
        $data->account_number = $source->account_number;
        $data->balance = $source->balance;
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

}
