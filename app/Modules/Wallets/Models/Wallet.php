<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model{

    use \TraitsFunc;

    protected $table = 'client_wallets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Shop(){
        return $this->belongsTo('\App\Models\Shop','shop_id');
    }

    public function Client(){
        return $this->belongsTo('\App\Models\Client','client_id');
    }

    public function FromCurrency(){
        return $this->belongsTo('\App\Models\Currency','from_id');
    }

    public function ToCurrency(){
        return $this->belongsTo('\App\Models\Currency','to_id');
    }

    static function dataList($withPaginate=null,$withRemainBalance=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['from_id']) && !empty($input['from_id'])) {
            $source->where('from_id', $input['from_id']);
        }
        if (isset($input['shop_id']) && !empty($input['shop_id'])) {
            $source->where('shop_id', $input['shop_id']);
        }
        if (isset($input['to_id']) && !empty($input['to_id'])) {
            $source->where('to_id', $input['to_id']);
        }
        if (isset($input['type']) && !empty($input['type'])) {
            $source->where('type', $input['type']);
        }
        if (isset($input['client_id']) && !empty($input['client_id'])) {
            $source->where('client_id', $input['client_id']);
        }
        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('created_at','>=',$input['from'].' 00:00:00')->where('created_at','<=',$input['to'].' 23:59:59');
        }
        if (!IS_ADMIN) {
            $source->where('shop_id', \Session::get('shop_id'))->orWhere('to_shop_id',\Session::get('shop_id'));
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$withPaginate,$withRemainBalance);
    }

    static function generateObj($source,$withPaginate=null,$withRemainBalance=null){
        if($withPaginate != null){
            $sourceArr = $source->get();
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }
        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value,$withRemainBalance);
        }
        if($withPaginate==null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;
        return $data;
    }


    static function getData($source,$withRemainBalance=null) {
        $data = new  \stdClass();
        $myType = '';
        $report_text = '';
        if($source->type == 1){
            $myType = 'ايداع';
            $report_text = 'دائن';
        }else{
            $myType = 'سحب';
            $report_text = 'مدين';
        }

        $data->id = $source->id;
        $data->type = $source->type;
        $data->type_text = $myType;
        $data->type_report_text = $report_text;
        $data->commission_type = $source->commission_type;
        $data->commission_type_text = $source->commission_type == 1 ? 'قيمة' : 'نسبة';
        $data->commission_value = $source->commission_value;
        $data->commission = $source->commission;
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->Shop->title;
        $data->from_id = $source->from_id;
        $data->from = $source->FromCurrency;
        $data->client_id = $source->client_id;
        $data->client_name = $source->Client->name;
        $data->to_id = $source->to_id;
        $data->to = $source->ToCurrency;
        $data->convert_price = $source->convert_price;
        $data->amount = $source->amount;
        $data->bank_convert_price = $source->bank_convert_price;
        $data->gain = $source->gain;
        if($withRemainBalance != null){
            $data->remain_balance = self::getBalance($source->client_id);
        }
        $data->total = $source->total;
        $data->created_at = $source->created_at;
        return $data;
    }

    static function getBalance($client_id){
        $clientObj = Client::getOne($client_id);
        $main_balance = $clientObj->balance;
        $main_currency = $clientObj->currency_id;
        $deposites = Wallet::NotDeleted()->where('type',1)->where('client_id',$client_id)->where('to_id',$main_currency)->sum('total');
        $withDraws = Wallet::NotDeleted()->where('type',2)->where('client_id',$client_id)->where('to_id',$main_currency)->sum('total');
        $remain_balance = round( ($main_balance + $deposites - $withDraws) ,2);
        return $remain_balance;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

}
