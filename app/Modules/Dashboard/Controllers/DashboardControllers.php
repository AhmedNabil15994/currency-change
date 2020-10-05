<?php namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Expense;
use App\Models\Exchange;
use App\Models\Currency;
use App\Models\Wallet;
use App\Models\StorageTransfer;
use App\Models\ShopStorage;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardControllers extends Controller {

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

    public function getBalances($totals){
        $balances = [];
        $allTotals = [0,0,0,0,0,0];
        $storages = ShopStorage::NotDeleted()->select('*')->selectRaw(\DB::raw('sum(balance) as balance'))->whereIn('shop_id',$this->shops)->groupBy('currency_id','shop_id')->where('is_active',1)->get();
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

    public function index(){
        $data = [];
        $totals = [];
        $input = \Input::all();
        $expenses = Expense::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,sum(total) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id'))->get(['myTotal','created_at','shop_id']);
        $expenses = reset($expenses);

        $outcomingSAR = Exchange::NotDeleted()->whereIn('to_shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->where('to_id',1)->selectRaw('to_shop_id as shop_id,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),to_shop_id'))->get(['shop_id','myTotal','created_at']);
        $outcomingSAR = reset($outcomingSAR);

        $outComingData = Exchange::NotDeleted()->whereIn('to_shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->where('to_id','!=',1)->selectRaw('to_shop_id as shop_id,to_id as currency,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),to_shop_id,to_id'))->get(['shop_id,currency','myTotal','created_at']);
        $outComingData = reset($outComingData);

        $inComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,from_id as currency,sum(amount) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,from_id'))->get(['shop_id','currency','myTotal','created_at']);
        $inComingData = reset($inComingData);

        $currencyArray = Currency::NotDeleted()->orderBy('id','ASC')->pluck('name');
        $currencyArray = reset($currencyArray);

        $outComingTransfers = StorageTransfer::NotDeleted()->whereIn('from_shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('from_shop_id as shop_id,currency_id as currency,sum(total) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),from_shop_id,currency_id'))->get(['shop_id,currency','myTotal','created_at']);
        $outComingTransfers = reset($outComingTransfers);

        $outComingWallets = Wallet::NotDeleted()->whereIn('shop_id',$this->shops)->where('type',2)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id as shop_id,from_id as currency,sum(amount) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,from_id'))->get(['shop_id,currency','myTotal','created_at']);
        $outComingWallets = reset($outComingWallets);


        $inComingTransfers = StorageTransfer::NotDeleted()->whereIn('to_shop_id',$this->shops)->where('type',1)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('to_shop_id as shop_id,currency_id as currency,sum(total) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),to_shop_id,currency_id'))->get(['shop_id','currency','myTotal','created_at']);
        $inComingTransfers = reset($inComingTransfers);

        $inComingWallets = Wallet::NotDeleted()->whereIn('shop_id',$this->shops)->where('type',1)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id as shop_id,from_id as currency,sum(amount) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,from_id'))->get(['shop_id,currency','myTotal','created_at']);
        $inComingWallets = reset($inComingWallets);


        $expenseData = $this->prepareStorageData($data,$totals,$expenses,0);
        $outcomingSARData = $this->prepareStorageData($expenseData[0],$expenseData[1],$outcomingSAR,0);
        $outcomingAllData = $this->prepareStorageData($outcomingSARData[0],$outcomingSARData[1],$outComingData,0);
        $outComingTransfersData = $this->prepareStorageData($outcomingAllData[0],$outcomingAllData[1],$outComingTransfers,0);
        $outComingWalletData = $this->prepareStorageData($outComingTransfersData[0],$outComingTransfersData[1],$outComingWallets,0);
        $incomingAllData = $this->prepareStorageData($outComingWalletData[0],$outComingWalletData[1],$inComingData,1);
        $inComingTransfersData = $this->prepareStorageData($incomingAllData[0],$incomingAllData[1],$inComingTransfers,1);
        $inComingWalletData = $this->prepareStorageData($inComingTransfersData[0],$inComingTransfersData[1],$inComingWallets,1);

        $allData = $inComingWalletData[0];
        krsort($allData);

        $balances = $this->getBalances($inComingWalletData[1]);

        $dataList['balances'] = $balances[0];
        $dataList['shops'] = Shop::whereIn('id',$this->shops)->orderBy('id','ASC')->get();
        $dataList['currencies'] = $currencyArray;
        $dataList['bankAccounts'] = BankAccount::reportList(null,null,'no_paginate')['data'];
        return view('Dashboard.Views.dashboard')->with('data', (Object) $dataList);
    }
}
