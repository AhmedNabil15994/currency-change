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

    function Transfer(){
        return $this->hasMany('App\Models\Transfer','bank_account_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    static function dataList($withPaginate=null,$currency_id=null) {
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
        if ($currency_id != null) {
            $source->where('currency_id', $currency_id);
        }
        if(!IS_ADMIN){
            $source->where('shop_id',\Session::get('shop_id'));
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$withPaginate);
    }

    static function reportList($shop_id=null,$date=null,$withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted()->whereHas('Transfer');
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

        if($shop_id != null){
            $source->whereIn('shop_id',$shop_id);
        }

        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $source->whereHas('Transfer',function($transferQuery) use ($input){
                $transferQuery->where('created_at','>=',$input['from'])->where('created_at','<=',$input['to']);
            });
        }

        if($date == null){
            $source->whereHas('Transfer',function($transferQuery){
                $transferQuery->groupBy(\DB::raw('Date(created_at),bank_account_id'));
            });
        }else{
            $source->whereHas('Transfer',function($transferQuery){
                $transferQuery->groupBy(\DB::raw('DATE_FORMAT(created_at,"%m-%Y"),bank_account_id'));
            });
        }

        $source->orderBy('id','DESC');
        return self::generateObj2($source,$date,$withPaginate);
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

    static function generateObj2($source,$date=null,$withPaginate=null){
        if($withPaginate != null){
            $sourceArr = $source->get();          
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData2($value,$date);
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

    static function getData2($source,$date=null) {
        $data = new  \stdClass();
        $transfers = [];
        $totalSent = 0;
        $totalCome = 0;

        $to_id = 0;
        if($source->currency_id == 1){
            $to_id = 2;
        }else{
            $to_id = 1;
        }
        $toObj = Currency::getOne($to_id);
        if($date == null){
            $transfersData = $source->Transfer()->NotDeleted()->select('*')->selectRaw('sum(balance) as myTotal')->groupBy(\DB::raw('Date(created_at),bank_account_id,currency_id,type'))->orderBy('id','DESC')->get();
        }else{
            $transfersData = $source->Transfer()->NotDeleted()->select('*')->selectRaw('sum(balance) as myTotal')->groupBy(\DB::raw('DATE_FORMAT(created_at,"%m-%Y"),bank_account_id,currency_id,type'))->orderBy('id','DESC')->get();
        }
        foreach ($transfersData as $value) {
            $created_at = date('Y-m-d',strtotime($value->created_at));
            $rate = \ConvertCurrency::convertHistorical($value->Currency->code,$toObj->code,$created_at);
            if(!isset($transfers[$created_at])){
                $transfers[$created_at] = [[],[]];
            }
            $myDate = date('Y-m-d',strtotime($value->created_at));
            if($date != null){
                $myDate = date('m-Y',strtotime($value->created_at));
            }
            if($value->type == 2){
                $transfers[$created_at][0] = [$value->myTotal,$rate,round($rate*$value->myTotal , 4),$myDate,$source->shop_id,$source->currency_id];
                $totalSent = $totalSent + $value->myTotal;
            }elseif($value->type == 1){
                $transfers[$created_at][1] = [$value->myTotal,$rate,round($rate*$value->myTotal , 4),$myDate,$source->shop_id,$source->currency_id];
                $totalCome = $totalCome + $value->myTotal;
            }
        }
        $data->id = $source->id;
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->Shop->title;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->name = $source->name;
        $data->transfers = $transfers;
        $data->account_number = $source->account_number;
        $data->balance = $source->balance;
        $data->total = $source->balance + $totalCome - $totalSent;
        $data->myTotal = $data->total;
        $data->to = $toObj->name;
        $data->currency = $source->currency_id;
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
