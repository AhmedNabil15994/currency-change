<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model{

    use \TraitsFunc;

    protected $table = 'transfers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Delegate(){
        return $this->belongsTo('App\Models\Delegate','delegate_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    function NewCurrency(){
        return $this->belongsTo('App\Models\Currency','new_currency_id');
    }

    function BankAccount(){
        return $this->belongsTo('App\Models\BankAccount','bank_account_id');
    }

    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['currency_id']) && $input['currency_id'] != 0) {
            $source->where('currency_id', $input['currency_id']);
        }
        if (isset($input['delegate_id']) && $input['delegate_id'] != 0) {
            $source->where('delegate_id', $input['delegate_id']);
        }
        if (isset($input['user_id']) && $input['user_id'] != 0) {
            $source->where('delegate_id', $input['user_id']);
        }
        if (isset($input['type']) && $input['type'] != 0) {
            $source->where('type', $input['type']);
        }
        if (isset($input['bank_account_id']) && $input['bank_account_id'] != 0) {
            $source->where('bank_account_id', $input['bank_account_id']);
        }
        if (isset($input['company']) && !empty($input['company'])) {
            $source->where('company', 'LIKE', '%' . $input['company'] . '%');
        }
        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('created_at','>=',$input['from'].' 00:00:00')->where('created_at','<=',$input['to'].' 23:59:59');
        }
        if (!IS_ADMIN) {
            $source->whereHas('Delegate',function($delegateQuery){
                $delegateQuery->where('shop_id', 'LIKE', '%' .','.\Session::get('shop_id') . '%')
                   ->orWhere('shop_id', 'LIKE', '%' .\Session::get('shop_id').',' . '%')
                   ->orWhere('shop_id', \Session::get('shop_id'));
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
        $data->id = $source->id;
        $data->delegate_id = $source->delegate_id;
        $data->delegate_name = $source->Delegate->name;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->bank_account_id = $source->bank_account_id;
        $data->bank_account = $source->BankAccount->account_number;
        $data->company = $source->company;
        $data->company_account = $source->company_account;
        $data->details_id = $source->details_id;
        $data->rate = $source->rate;
        $data->total = $source->total;
        $data->new_currency_id = $source->new_currency_id;
        $data->new_currency = $source->new_currency_id != null ? $source->NewCurrency->name : '';
        $data->type = $source->type;
        $data->balance = $source->balance;
        $data->commission_rate = $source->commission_rate;
        $data->commission_value = $source->commission_value;
        $data->type = $source->type;
        $data->type_text = $source->type == 1 ? "وارد" : "صادر";
        $data->created_at = $source->created_at;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

}
