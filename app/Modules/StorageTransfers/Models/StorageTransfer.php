<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class StorageTransfer extends Model{

    use \TraitsFunc;

    protected $table = 'storage_transfers';
    protected $primaryKey = 'id';
    public $timestamps = false;


    function Storage(){
        return $this->hasOne('App\Models\ShopStorage','id','storage_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    static function dataList($withPaginate=null,$currency_id=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['storage_id']) && $input['storage_id'] != 0) {
            $source->where('storage_id', $input['storage_id']);
        }
        if (isset($input['type']) && $input['type'] != 0) {
            $source->where('type', $input['type']);
        }
        if (isset($input['to_id']) && $input['to_id'] != 0 && isset($input['type']) && $input['type'] != 0) {
            $source->where('to_id', $input['to_id'])->where('type', $input['type']);
        }
        if (isset($input['currency_id']) && $input['currency_id'] != 0) {
            $source->where('currency_id', $input['currency_id']);
        }
        if ($currency_id != null) {
            $source->where('currency_id', $currency_id);
        }
        if(!IS_ADMIN){
            $source->whereHas('Storage',function($storageQuery){
                $storageQuery->where('shop_id',\Session::get('shop_id'));
            });
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
        if($source->type == 2){
            $bankObj = BankAccount::getData(BankAccount::getOne($source->to_id));
        }
        $data->id = $source->id;
        $data->storage_id = $source->storage_id;
        $data->to_id = $source->to_id;
        $data->storage_name = 'صندوق '.$source->Storage->Shop->title;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->type = $source->type;
        $data->type_text = $source->type == 1 ? 'الي صندوق' : 'الي حساب بنكي';
        $data->to_text = $source->type == 1 ? 'صندوق '. ShopStorage::getData(ShopStorage::getOneById($source->to_id))->shop_name : $bankObj->account_number . ' - ' . $bankObj->name . ' - ' . $bankObj->shop_name;
        $data->to_shop_id = $source->to_shop_id;
        $data->from_shop_id = $source->from_shop_id;
        $data->total = $source->total;
        $data->created_at = $source->created_at;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

}
