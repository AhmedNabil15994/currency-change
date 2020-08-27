<?php namespace App\Http\Controllers;
use App\Models\Shop;
use App\Models\Expense;
use App\Models\ShopStorage;
use App\Models\Currency;
use App\Models\Profile;
use App\Models\Transfer;
use App\Models\BankAccount;
use App\Models\Commission;
use App\Models\User;
use App\Models\Delegate;
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

    public function prepareStorageDataForDay($dataType,$data,$totals,$loopData,$type){
        foreach ($loopData as $expense) {
            $currency = isset($expense->currency) ? $expense->currency : 1;
            $currencyIndex = $currency - 1;
            if(!isset($data[$expense->created_at][$dataType][$expense->shop_id])){
                $data[$expense->created_at][$dataType][$expense->shop_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                $data[$expense->created_at][$dataType][$expense->shop_id][$type][$currencyIndex] = $data[$expense->created_at][$dataType][$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $data[$expense->created_at][$dataType][$expense->shop_id][$type][$currencyIndex] = $data[$expense->created_at][$dataType][$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }

            if(!isset($totals[$dataType][$expense->shop_id])){
                $totals[$dataType][$expense->shop_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                @$totals[$dataType][$expense->shop_id][$type][$currencyIndex] = $totals[$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $totals[$dataType][$expense->shop_id][$type][$currencyIndex] = $totals[$dataType][$expense->shop_id][$type][$currencyIndex] + $expense->myTotal;
            }
        }
        return [$data,$totals];
    }

    public function prepareStorageDataForDay2($dataType,$data,$totals,$loopData,$type){
        foreach ($loopData as $expense) {
            $currency = isset($expense->currency) ? $expense->currency : 1;
            $currencyIndex = $currency - 1;
            if(!isset($data[$expense->created_at][$dataType][$expense->bank_account_id])){
                $data[$expense->created_at][$dataType][$expense->bank_account_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                $data[$expense->created_at][$dataType][$expense->bank_account_id][$type][$currencyIndex] = $data[$expense->created_at][$dataType][$expense->bank_account_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $data[$expense->created_at][$dataType][$expense->bank_account_id][$type][$currencyIndex] = $data[$expense->created_at][$dataType][$expense->bank_account_id][$type][$currencyIndex] + $expense->myTotal;
            }

            if(!isset($totals[$dataType][$expense->bank_account_id])){
                $totals[$dataType][$expense->bank_account_id] = [[0,0,0,0,0,0],[0,0,0,0,0,0]];
                @$totals[$dataType][$expense->bank_account_id][$type][$currencyIndex] = $totals[$expense->bank_account_id][$type][$currencyIndex] + $expense->myTotal;
            }else{
                $totals[$dataType][$expense->bank_account_id][$type][$currencyIndex] = $totals[$dataType][$expense->bank_account_id][$type][$currencyIndex] + $expense->myTotal;
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

    public function getAllBalances($totals){
        $balances = [];
        $storages = BankAccount::NotDeleted()->select('*')->selectRaw(\DB::raw('sum(balance) as balance'))->whereIn('shop_id',$this->shops)->groupBy('currency_id','shop_id')->where('is_active',1)->get();
        foreach ($storages as $oneStorage) {
            if(!isset($balances[$oneStorage->id])){
                $balances[$oneStorage->id] = [0,0,0,0,0,0];   
            }
            $balances[$oneStorage->id][$oneStorage->currency_id-1] = $oneStorage->balance + $totals[$oneStorage->id][1][$oneStorage->currency_id-1] - $totals[$oneStorage->id][0][$oneStorage->currency_id-1];
        }
        return $balances;
    }

    public function getDeterminedBalances($totals,$expeses){
        foreach ($totals as $key=> $oneStorage) {
            if(isset($expeses[$key])){
                $totals[$key][0] = $totals[$key][0] - $expeses[$key][0][0];
            }
        }
        return $totals;
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
        })->where('to_id','!=',1)->selectRaw('shop_id,to_id as currency,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,to_id'))->get(['shop_id,currency','myTotal','created_at']);
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
        $dataList = BankAccount::reportList();
        $dataList['bankAccounts'] = BankAccount::dataList('no_paginate')['data'];
        $dataList['shops'] = Shop::whereIn('id',$this->shops)->orderBy('id','ASC')->get();
        return view('Reports.Views.bankAccounts')
            ->with('data', (Object) $dataList);
    }

    public function delegates(){
        $data = [];
        $exchanges = Exchange::dataList('no_paginate')['data'];
        $totalWithdraw = [];
        $totalDeposit = [];
        foreach ($exchanges as $exchange) {
            if($exchange->type == 2){
                $commissionObj = Commission::NotDeleted()->where('delegate_id',$exchange->user_id)->where('valid_until','>=',$exchange->created_at)->where('is_active',1)->orderBy('valid_until','ASC')->first();
                $exchangeObj = new \stdClass();
                $exchangeObj->type_text = 'ايداع';
                $exchangeObj->type = 1;
                $exchangeObj->commssion = $commissionObj != null ? $commissionObj->commission : 0;
                $exchangeObj->user_name = $exchange->user_name;
                if($exchange->from_id == 1){
                    $exchangeObj->amount = $exchange->amount;
                    $exchangeObj->rate = 1;
                }else{
                    $exchangeObj->amount = $exchange->paid;
                    $exchangeObj->rate = $exchange->convert_price;
                }
                $exchangeObj->dayen = $exchangeObj->amount;
                $exchangeObj->modein = 0;
                $exchangeObj->created_at = date('Y-m-d',strtotime($exchange->created_at));
                $data[] = $exchangeObj;
                @$totalDeposit[$exchange->user_id] = $totalDeposit[$exchange->user_id] + $exchangeObj->amount;
              
                $exchangeObj2 = new \stdClass();
                $exchangeObj2->type_text = 'سحب';
                $exchangeObj2->commssion = 1;
                $exchangeObj2->type = 2;
                $exchangeObj2->user_name = $exchange->user_name;
                if($exchange->to_id == 1){
                    $exchangeObj2->amount = $exchange->amount;
                    $exchangeObj2->rate = 1;
                }else{
                    $exchangeObj2->amount = $exchange->paid;
                    $exchangeObj2->rate = $exchange->convert_price;
                }
                $exchangeObj2->dayen = 0;
                $exchangeObj2->modein = $exchangeObj2->amount;
                $exchangeObj2->created_at = date('Y-m-d',strtotime($exchange->created_at));
                $data[] = $exchangeObj2;
                @$totalWithdraw[$exchange->user_id] = $totalWithdraw[$exchange->user_id] + $exchangeObj2->amount;
            }
        }

        $transfers = Transfer::dataList('no_paginate')['data'];
        foreach ($transfers as $transfer) {
            $commissionObj = Commission::NotDeleted()->where('delegate_id',$transfer->delegate_id)->where('valid_until','>=',$transfer->created_at)->where('is_active',1)->orderBy('valid_until','ASC')->first();
            $transferObj = new \stdClass();
            $transferObj->commssion = $commissionObj != null ? $commissionObj->commission : 0;
            $transferObj->user_name = $transfer->delegate_name;
            if($transfer->currency_id == 1){
                $transferObj->amount = $transfer->balance;
                $transferObj->rate = 1;
            }else{
                $transferObj->amount = $transfer->balance;
                $transferObj->rate = \Helper::convertHistorical(1,$transfer->currency_id,date('Y-m-d',strtotime($transfer->created_at)));
            }
            if($transfer->type == 1){
                $transferObj->type_text = 'ايداع';
                $transferObj->type = 1;
                $transferObj->dayen = $transfer->balance;
                $transferObj->modein = 0;
                @$totalDeposit[$transfer->delegate_id] = $totalDeposit[$transfer->delegate_id] + $transferObj->amount;
            }else{
                $transferObj->type_text = 'سحب';
                $transferObj->type = 2;
                $transferObj->dayen = 0;
                $transferObj->modein = $transfer->balance;
                @$totalWithdraw[$transfer->delegate_id] = $totalWithdraw[$transfer->delegate_id] + $transferObj->amount;
            }
            $transferObj->created_at = date('Y-m-d',strtotime($transfer->created_at));
            $data[] = $transferObj;
        }

        $dataList = $this->setPaginationForArray($data);
        $dataList['data_count'] = count($data);
        $dataList['totalWithdraw'] = $totalWithdraw;
        $dataList['totalDeposit'] = $totalDeposit;
        $dataList['delegates'] = Delegate::dataList('no_paginate')['data'];
        return view('Reports.Views.delegates')
            ->with('data', (Object) $dataList);
    }

    public function daily(){
        $data = [];
        $totals = [];
        $input = \Input::all();

        $outComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,to_id as currency,sum(paid) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,to_id'))->get(['shop_id,currency','myTotal','created_at']);
        $outComingData = reset($outComingData);

        $inComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,from_id as currency,sum(amount) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id,from_id'))->get(['shop_id','currency','myTotal','created_at']);
        $inComingData = reset($inComingData);

        $currencyArray = Currency::NotDeleted()->orderBy('id','ASC')->pluck('name');
        $currencyArray = reset($currencyArray);

        $outcomingAllData = $this->prepareStorageDataForDay(0,$data,$totals,$outComingData,0);
        $incomingAllData = $this->prepareStorageDataForDay(0,$outcomingAllData[0],$outcomingAllData[1],$inComingData,1);

        $bankAccounts = BankAccount::reportList($this->shops,null,'no_paginate')['data'];
        $banksData = [];
        $bankAccountAllData = [];
        foreach ($bankAccounts as $oneBank) {
            foreach($oneBank->transfers as $one){
                $myType = 0;
                for ($i = 0; $i < count($one) ; $i++) {
                    if(!empty($one[$i])){
                        $myBankData = new \stdClass();
                        $myBankData->shop_id = $one[$i][4];
                        $myBankData->bank_account_id = $oneBank->id;
                        $myBankData->currency = $one[$i][5];
                        $myBankData->myTotal = $one[$i][0];
                        $myBankData->created_at = $one[$i][3];
                        $banksData[$oneBank->id] = $myBankData;
                    }   
                    if($i == 0){
                        $bankAccountAllData = $this->prepareStorageDataForDay2(1,$incomingAllData[0],$incomingAllData[1],$banksData,$i);

                    }else{
                        $bankAccountAllData = $this->prepareStorageDataForDay2(1,$bankAccountAllData[0],$bankAccountAllData[1],$banksData,$i);
                    }
                }
            }
        }


        $expenses = Expense::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,sum(total) as myTotal,DATE(created_at) as created_at')->groupBy(\DB::raw('Date(created_at),shop_id'))->get(['myTotal','created_at','shop_id']);
        $expenses = reset($expenses);

        $expenseAllData = $this->prepareStorageDataForDay(2,$bankAccountAllData[0],$bankAccountAllData[1],$expenses,0);
        $allData = $expenseAllData[0];
        krsort($allData);

        $balancesFirst = $this->getBalances(@$expenseAllData[1][0]);
        $balancesSecond = $this->getAllBalances(@$expenseAllData[1][1]);
        $balancesFirst = $this->getDeterminedBalances(@$balancesFirst[0],@$expenseAllData[1][2]);

        $dataList = $this->setPaginationForArray($allData);
        $dataList['balancesFirst'] = $balancesFirst;
        $dataList['balancesSecond'] = $balancesSecond;

        $dataList['shops'] = Shop::whereIn('id',$this->shops)->orderBy('id','ASC')->get();
        $dataList['shops_count'] = count($dataList);
        $dataList['currencies'] = $currencyArray;
        $dataList['bankAccounts'] = $bankAccounts;
        $dataList['totals'] = $expenseAllData[1];
        $dataList['data_count'] = count($allData);
        return view('Reports.Views.daily')
            ->with('data', (Object) $dataList);
    }

    public function yearly(){
        $data = [];
        $totals = [];
        $input = \Input::all();

        $outComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,to_id as currency,sum(paid) as myTotal,DATE_FORMAT(created_at,"%m-%Y") as created_at')->groupBy(\DB::raw('DATE_FORMAT(created_at,"%m-%Y"),shop_id,to_id'))->get(['shop_id,currency','myTotal','created_at']);
        $outComingData = reset($outComingData);

        $inComingData = Exchange::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,from_id as currency,sum(amount) as myTotal,DATE_FORMAT(created_at,"%m-%Y") as created_at')->groupBy(\DB::raw('DATE_FORMAT(created_at,"%m-%Y"),shop_id,from_id'))->get(['shop_id','currency','myTotal','created_at']);
        $inComingData = reset($inComingData);

        $currencyArray = Currency::NotDeleted()->orderBy('id','ASC')->pluck('name');
        $currencyArray = reset($currencyArray);

        $outcomingAllData = $this->prepareStorageDataForDay(0,$data,$totals,$outComingData,0);
        $incomingAllData = $this->prepareStorageDataForDay(0,$outcomingAllData[0],$outcomingAllData[1],$inComingData,1);


        $bankAccounts = BankAccount::reportList($this->shops,'M','no_paginate')['data'];
        $banksData = [];
        $bankAccountAllData = [];
        foreach ($bankAccounts as $oneBank) {
            foreach($oneBank->transfers as $one){
                $myType = 0;
                for ($i = 0; $i < count($one) ; $i++) {
                    if(!empty($one[$i])){
                        $myBankData = new \stdClass();
                        $myBankData->shop_id = $one[$i][4];
                        $myBankData->bank_account_id = $oneBank->id;
                        $myBankData->currency = $one[$i][5];
                        $myBankData->myTotal = $one[$i][0];
                        $myBankData->created_at = $one[$i][3];
                        $banksData[$oneBank->id] = $myBankData;
                    }   
                    if($i == 0){
                        $bankAccountAllData = $this->prepareStorageDataForDay2(1,$incomingAllData[0],$incomingAllData[1],$banksData,$i);

                    }else{
                        $bankAccountAllData = $this->prepareStorageDataForDay2(1,$bankAccountAllData[0],$bankAccountAllData[1],$banksData,$i);
                    }
                }
            }
        }

        $expenses = Expense::NotDeleted()->whereIn('shop_id',$this->shops)->where(function($whereQuery) use ($input){
            if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
                $whereQuery->where('created_at','>=',$this->startDate)->where('created_at','<=',$this->endDate);
            }
        })->selectRaw('shop_id,sum(total) as myTotal,DATE_FORMAT(created_at,"%m-%Y") as created_at')->groupBy(\DB::raw('DATE_FORMAT(created_at,"%m-%Y"),shop_id'))->get(['myTotal','created_at','shop_id']);
        $expenses = reset($expenses);

        $expenseAllData = $this->prepareStorageDataForDay(2,$bankAccountAllData[0],$bankAccountAllData[1],$expenses,0);
        $allData = $expenseAllData[0];
        krsort($allData);

        $balancesFirst = $this->getBalances(@$expenseAllData[1][0]);
        $balancesSecond = $this->getAllBalances(@$expenseAllData[1][1]);
        $balancesFirst = $this->getDeterminedBalances(@$balancesFirst[0],@$expenseAllData[1][2]);

        $dataList = $this->setPaginationForArray($allData);
        $dataList['balancesFirst'] = $balancesFirst;
        $dataList['balancesSecond'] = $balancesSecond;

        $dataList['shops'] = Shop::whereIn('id',$this->shops)->orderBy('id','ASC')->get();
        $dataList['shops_count'] = count($dataList);
        $dataList['currencies'] = $currencyArray;
        $dataList['bankAccounts'] = $bankAccounts;
        $dataList['totals'] = $expenseAllData[1];
        $dataList['data_count'] = count($allData);
        return view('Reports.Views.yearly')
            ->with('data', (Object) $dataList);
    }
}
