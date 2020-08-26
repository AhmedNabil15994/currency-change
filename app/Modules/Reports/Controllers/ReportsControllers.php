<?php namespace App\Http\Controllers;
use App\Models\Shop;
use App\Models\Expense;
use App\Models\ShopStorage;
use App\Models\Currency;
use App\Models\Profile;
use App\Models\User;
use App\Models\Exchange;
use Illuminate\Support\Facades\Input;
use \Session;
use Carbon\Carbon;   

class ReportsControllers extends Controller {

    use \TraitsFunc;

    public $shops;
    public $startDate;
    public $endDate;
    
    function __construct(){
        $input = \Input::all();
        $shops = [];
        if(Session::get('group_id') != 1){
            $shops[0] = Session::get('shop_id');
        }else{
            $shops = Shop::NotDeleted()->pluck('id');
            $shops = reset($shops);
        }
        if(isset($input['shop_id']) && !empty($input['shop_id'])){
            $shops=[];
            $shops[0]=[$input['shop_id']];
        }
        $startDate = date('Y-m-d') . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $startDate = $input['from'] . ' 00:00:00';
            $endDate = $input['to'] . ' 23:59:59';
        }
        $this->shops = $shops;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function expenses(){
        $dataList = Expense::dataList();
        $dataList['shops'] = Shop::dataList('no_paginate')['data'];
        $dataList['users'] = User::getUserByType(2);
        return view('Reports.Views.expenseReports')
            ->with('data', (Object) $dataList);
    }    

    public function prepareStorageData($data,$totals,$loopData,$type){
        foreach ($loopData as $expense) {
            $currency = isset($expense->currency) ? $expense->currency : 1;
            $currencyIndex = $currency - 1;
            if(!isset($data[$expense->created_at][$expense->shop_id])){
                $data[$expense->created_at][$expense->shop_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                $data[$expense->created_at][$expense->shop_id][$type][$currencyIndex] = $data[$expense->created_at][$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $data[$expense->created_at][$expense->shop_id][$type][$currencyIndex] = $data[$expense->created_at][$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }

            if(!isset($totals[$expense->shop_id])){
                $totals[$expense->shop_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                $totals[$expense->shop_id][$type][$currencyIndex] = $totals[$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $totals[$expense->shop_id][$type][$currencyIndex] = $totals[$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }
        }
        return [$data,$totals];
    }

    public function setPaginationForArray($allData){
        $row_per_page = PAGINATION;
        $pageNo = isset($input['page']) && !empty($input['page']) ? $input['page'] : 1;
        if($pageNo > 1){
            $oldData = array_slice($allData, ($pageNo-1)*$row_per_page  );
        }else{
            $oldData = $allData;
        }
        $myData =  new \Illuminate\Pagination\Paginator($oldData,$row_per_page);
        $dumpData =  new \Illuminate\Pagination\Paginator($allData,$row_per_page);
        $dataList['data'] = $myData;
        $myCount = count($allData) == 0 ? -1 : count($allData);
        $dataList['pagination'] = \Helper::GeneratePagination($dumpData,$myCount,$row_per_page);
        return $dataList;
    }

    public function getBalances($totals){
        $balances = [];
        $allTotals = [0,0,0,0,0,0];
        $storages = ShopStorage::NotDeleted()->whereIn('shop_id',$this->shops)->where('is_active',1)->get();
        foreach ($storages as $oneStorage) {
            if(!isset($balances[$oneStorage->shop_id])){
                $balances[$oneStorage->shop_id] = [0,0,0,0,0,0];   
            }
            if(!isset($totals[$oneStorage->shop_id])){
                $totals[$oneStorage->shop_id][1] = [0,0,0,0,0,0];
                $totals[$oneStorage->shop_id][0] = [0,0,0,0,0,0];
            }
            $balances[$oneStorage->shop_id][$oneStorage->currency_id-1] = $oneStorage->balance + $totals[$oneStorage->shop_id][1][$oneStorage->currency_id-1] - $totals[$oneStorage->shop_id][0][$oneStorage->currency_id-1];
            $allTotals[$oneStorage->currency_id-1] = $balances[$oneStorage->shop_id][$oneStorage->currency_id-1] + $allTotals[$oneStorage->currency_id-1];
        }
        return [$balances,$allTotals];
    }

    public function storages(){
        $data = [];
        $totals = [];
        $input = \Input::all();
        $expenses = Expense::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,sum(total) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id'))->get(['myTotal','created_at','shop_id']);
        $expenses = reset($expenses);

        $outcomingSAR = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->where('to_id',1)->selectRaw('shop_id,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id'))->get(['shop_id','myTotal','created_at']);
        $outcomingSAR = reset($outcomingSAR);

        $outComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->where('to_id','!=',1)->selectRaw('shop_id,to_id as currency,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id'),'to_id')->get(['shop_id,currency','myTotal','created_at']);
        $outComingData = reset($outComingData);

        $inComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,from_id as currency,sum(amount) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,from_id'))->get(['shop_id','currency','myTotal','created_at']);
        $inComingData = reset($inComingData);

        $currencyArray = Currency::NotDeleted()->orderBy('id','ASC')->pluck('name');
        $currencyArray = reset($currencyArray);

        $expenseData = $this->prepareStorageData($data,$totals,$expenses,0);
        $outcomingSARData = $this->prepareStorageData($expenseData[0],$expenseData[1],$outcomingSAR,0);
        $outcomingAllData = $this->prepareStorageData($outcomingSARData[0],$outcomingSARData[1],$outComingData,0);
        $incomingAllData = $this->prepareStorageData($outcomingAllData[0],$outcomingAllData[1],$inComingData,1);

        $allData = $incomingAllData[0];
        krsort($allData);

        $balances = $this->getBalances($incomingAllData[1]);

        $dataList = $this->setPaginationForArray($allData);
        $dataList['balances'] = $balances[0];
        $dataList['allTotals'] = $balances[1];
        $dataList['shops'] = Shop::whereIn('id',$this->shops)->orderBy('id','ASC')->get();
        $dataList['shops_count'] = count($dataList);
        $dataList['currencies'] = $currencyArray;
        $dataList['totals'] = $incomingAllData[1];
        $dataList['data_count'] = count($allData);
        return view('Reports.Views.storages')
            ->with('data', (Object) $dataList);
    }

    public function bankAccounts(){
       
    }

    public function delegates(){
        
    }

    public function daily(){
        
    }

    public function yearly(){
        
    }
}
