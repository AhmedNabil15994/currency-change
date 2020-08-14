<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class ShopStorage extends Model{

    use \TraitsFunc;

    protected $table = 'storages';
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
        if (isset($input['shop_id']) && $input['shop_id'] != 0) {
            $source->where('shop_id', $input['shop_id']);
        }
        if (isset($input['currency_id']) && $input['currency_id'] != 0) {
            $source->where('currency_id', $input['currency_id']);
        }

        $source->groupBy('shop_id')->orderBy('id','DESC');
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
        $currency_id = [];
        $currencies = self::NotDeleted()->where('shop_id',$source->shop_id)->get();
        $currency_names = [];
        $balances = [];
        $myData = [];
        foreach ($currencies as $one) {
            $myData[] = [$one->currency_id,$one->balance];
            $currency_id[] = $one->currency_id;
            $balances[] = $one->balance;
            $currency_names[] = Currency::NotDeleted()->where('id',$one->currency_id)->first()->name;
        }
        $data->id = $source->id;
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->Shop->title;
        $data->currency_id = $currency_id;
        $data->balance = $balances;
        $data->currency_name = $currency_names;
        $data->myData = $myData;
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('shop_id', $id)
            ->first();
    }

}
