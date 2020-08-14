<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model{

    use \TraitsFunc;

    protected $table = 'transfers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Client(){
        return $this->belongsTo('App\Models\Client','client_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
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
        if (isset($input['client_id']) && $input['client_id'] != 0) {
            $source->where('client_id', $input['client_id']);
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
        $data->client_id = $source->client_id;
        $data->client_name = $source->Client->name;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->bank_account_id = $source->bank_account_id;
        $data->bank_account = $source->BankAccount->account_number;
        $data->company = $source->company;
        $data->type = $source->type;
        $data->balance = $source->balance;
        $data->type = $source->type;
        $data->type_text = $source->type == 1 ? "وارد" : "صادر";
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
