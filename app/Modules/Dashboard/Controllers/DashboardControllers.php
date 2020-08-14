<?php namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ProductDetails;
use App\Models\Expense;
use App\Models\SalesInvoice;
use App\Models\SwitchOperations;
use App\Models\SalesItem;
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

    public function getChartData($start=null,$end=null){
        $input = \Input::all();
        
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
        	$start = $input['from'];
        	$end = $input['to'];
        }

        $datediff = strtotime($end) - strtotime($start);
        $daysCount = round($datediff / (60 * 60 * 24));
        $datesArray = [];

        if($daysCount > 2){
            for($i=0;$i<$daysCount;$i++){
                $datesArray[$i] = date('Y-m-d',strtotime($start.'+'.$i."day") );
            }
            $datesArray[$daysCount] = $end;  
        }else{
        	$datesArray[0] = $start;
            $datesArray[1] = $end;  
        }

        $chartData = [];
        $dataCount = count($datesArray);
        for($i=0;$i<$dataCount;$i++){
            if(IS_ADMIN == true){
            	if($dataCount == 1){
                	$count = SalesInvoice::NotDeleted()->where('status',1)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->sum('paid');
            	}else{
            		if($i < count($datesArray)){
                		$count = SalesInvoice::NotDeleted()->where('status',1)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->sum('paid');
            		}
            	}
            }else{
    			$shop_id = Session::get('shop_id');
            	if(Session::get('group_id') == 3){
            		if($dataCount == 1){
	                	$count = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('status',1)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->sum('paid');
	            	}else{
	            		if($i < count($datesArray)){
	                		$count = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('status',1)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->sum('paid');
	            		}
	            	}
            	}else{
            		if($dataCount == 1){
	                	$count = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('user_id',USER_ID)->where('status',1)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->sum('number_of_items');
	            	}else{
	            		if($i < count($datesArray)){
	                		$count = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('user_id',USER_ID)->where('status',1)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->sum('number_of_items');
	            		}
	            	}
            	}
            }
            $chartData[0][$i] = $datesArray[$i];
            $chartData[1][$i] = $count;
        }
        return $chartData;
    }

    public function calExpenseAndIncome($startDate,$endDate){
    	$safeData = [];
        $totals = [0,0,0];
        foreach ($this->shops as $shop_id) {
            $expensesCollection = Expense::NotDeleted()->where('shop_id',$shop_id)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate);
            $expenses = $expensesCollection->sum('total');
            $incomingCollection = SalesInvoice::NotDeleted()->where('status',1)->where('shop_id',$shop_id)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate);
            $incoming = $incomingCollection->sum('paid');
            $switches = SwitchOperations::NotDeleted()->where('shop_id',$shop_id)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->get();
            $totalSwitches = 0;
            $totalBack = 0;

            foreach ($switches as $value) {
                $switchDate = date('Y-m-d',strtotime($value->created_at));
                $salesObj = SalesInvoice::find($value->invoice_id);
                if($salesObj){
                    $invoiceDate = date('Y-m-d',strtotime($salesObj->created_at));
                    $expenses-= $value->back; 
                    if($switchDate != $invoiceDate){
                        $totalSwitches+= $value->paid;                    
                        $totalBack+= $value->back; 
                    }
                }
            }
            
            foreach ($expensesCollection->get() as $values) {
                if($values->invoice_id > 0 && $values->type){
                    $salesObj = SalesInvoice::find($values->invoice_id);
                    $expenseDate = date('Y-m-d',strtotime($values->created_at));
                    if($salesObj && $values->type == 5){
                        $invoiceDate = date('Y-m-d',strtotime($salesObj->created_at));
                        $expenses-= $values->total;
                        if($expenseDate != $invoiceDate){
                            $totalBack+= $values->total;
                        }
                    }                    
                }
            }

            $allIncoming = $incoming + $totalSwitches;
            $totalExpense = $expenses + $totalBack;
            $safe = $allIncoming - $totalExpense;
            $totals[0] = round( $totals[0] + $allIncoming ,2);
            $totals[1] = round( $totals[1] + $totalExpense ,2);
            $totals[2] = round( $totals[2] + $safe ,2);
        }
        return $totals;
    }

    public function index(){
        dd('dashboard');
    	$input = \Input::all();
    	$now = date('Y-m-d');
    	if(Session::get('group_id') == 1 && IS_ADMIN){   // IS_ADMIN
    		$dataList['sold_products'] = SalesInvoice::NotDeleted()->where('status',1)->where('created_at','>=',$now.' 00:00:00')->where('created_at','<=',$now.' 23:59:59')->count();
    		$dataList['salesInvoices'] = SalesInvoice::NotDeleted()->where('created_at','>=',$now.' 00:00:00')->where('created_at','<=',$now.' 23:59:59')->count();

    		$calExpenseAndIncomeArray = $this->calExpenseAndIncome($this->startDate,$this->endDate);
    		$totals = $calExpenseAndIncomeArray;
    		$dataList['chartData'] = $this->getChartData(date('Y-m-d',strtotime($now.' -7 days')),$now);
    		$dataList['income'] = round($totals[0] ,2);
    		$dataList['expense'] = round($totals[1], 2);
    		$dataList['safe'] = round($totals[2] , 2);
    		$dataList['totalIncome'] = SalesInvoice::NotDeleted()->where('status',1)->sum('paid');
    		$dataList['totalExpense'] = Expense::NotDeleted()->whereNotIn('type',[4,5])->sum('total');
    		$dataList['totalSafe'] = round( $dataList['totalIncome'] - $dataList['totalExpense'] ,2);
    		$dataList['most_sold_products'] = ProductDetails::soldProducts(5)['data'];
    		$dataList['most_sold_shop_products'] = ProductDetails::soldShopProducts(5)['data'];
    		$dataList['most_sold_workers'] = SalesInvoice::soldWorkers(5)['data'];
    	}else{
    		$shop_id = Session::get('shop_id');
    		$dataList['sold_products'] = SalesInvoice::NotDeleted()->where('status',1)->where('shop_id',$shop_id)->where('created_at','>=',$now.' 00:00:00')->where('created_at','<=',$now.' 23:59:59')->count();
    		$dataList['salesInvoices'] = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('created_at','>=',$now.' 00:00:00')->where('created_at','<=',$now.' 23:59:59')->count();

    		$calExpenseAndIncomeArray = $this->calExpenseAndIncome($this->startDate,$this->endDate);
    		$totals = $calExpenseAndIncomeArray;
    		$dataList['chartData'] = $this->getChartData(date('Y-m-d',strtotime($now.' -7 days')),$now);
    		$dataList['income'] = round($totals[0] ,2);
    		$dataList['expense'] = round($totals[1], 2);
    		$dataList['safe'] = round($totals[2] , 2);
            if(Session::get('group_id') == 3){
                $dataList['totalIncome'] = SalesInvoice::NotDeleted()->where('shop_id',$shop_id)->where('status',1)->sum('paid');
                $dataList['totalExpense'] = Expense::NotDeleted()->where('shop_id',$shop_id)->whereNotIn('type',[4,5])->sum('total');
                $dataList['totalSafe'] = round( $dataList['totalIncome'] - $dataList['totalExpense'] ,2);
            }
    		$dataList['most_sold_products'] = ProductDetails::soldProducts(5)['data'];
    		$dataList['most_sold_shop_products'] = ProductDetails::soldShopProducts(5)['data'];
    		$dataList['most_sold_workers'] = SalesInvoice::soldWorkers(5)['data'];
    		if(Session::get('group_id') == 2){ 
    			$dataList['most_last_expenses'] = Expense::userExpenses(5);
    		}
    	}
        return view('Dashboard.Views.dashboard')->with('data', (Object) $dataList);
    }
}
