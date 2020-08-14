<?php namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use App\Models\Salary;
use App\Models\Profile;
use App\Models\Shop;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ExpensesControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();
        $rules = [
            'type' => 'required',
            'shop_id' => 'required',
            'total' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع المصروف",
            'total.required' => "يرجي ادخال المبلغ",
            'shop_id.required' => "يرجي اختيار الفرع",
        ];
        $validate = \Validator::make($input, $rules, $message);
        return $validate;
    }

    public function index() {
        $usersList = Expense::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['users'] = User::getUserByType(2);
        return view('Expenses.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Expense::NotDeleted()->find($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['users'] = User::getUserByType(2);
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = Expense::getData($userObj);
        return view('Expenses.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $expenseObj = Expense::getOne($id);
        if($expenseObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        $date = DATE_TIME;
        if($input['created_at'] != null){
            $date = $input['created_at'].' '.date('H:i:s');
        }

        $expenseObj->type = $input['type'];
        $expenseObj->user_id = isset($input['user_id']) ? $input['user_id'] : null;
        $expenseObj->shop_id = isset($input['shop_id']) ? $input['shop_id'] : null;
        $expenseObj->currency_id = isset($input['shop_id']) ? $input['currency_id'] : null;
        $expenseObj->total = $input['total'];
        $expenseObj->description = $input['description'];
        $expenseObj->updated_at = DATE_TIME;
        $expenseObj->updated_by = USER_ID;
        $expenseObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['users'] = User::getUserByType(2);
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Expenses.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        $date = DATE_TIME;
        if($input['created_at'] != null){
            $date = $input['created_at'].' '.date('H:i:s');
        }

        $expenseObj = new Expense;
        $expenseObj->type = $input['type'];
        $expenseObj->user_id = isset($input['user_id']) ? $input['user_id'] : null;
        $expenseObj->shop_id = isset($input['shop_id']) ? $input['shop_id'] : null;
        $expenseObj->currency_id = isset($input['currency_id']) ? $input['currency_id'] : null;
        $expenseObj->total = $input['total'];
        $expenseObj->description = $input['description'];
        $expenseObj->created_at = $date;
        $expenseObj->created_by = USER_ID;
        $expenseObj->save();

        \Session::flash('success', "تبيه! تم اضافة المصروف");
        if(\Session::get('group_id') == 2){
            return redirect()->to('/expenses');
        }else{
            return redirect()->to('expenses/edit/' . $expenseObj->id);
        }
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Expense::getOne($id);
        return \Helper::globalDelete($userObj);
    }

    public function getMonths($date1,$date2){
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return $diff;
    }

    public function salary_index() {

        $profiles = Profile::whereHas('User',function($userQuery){
            $userQuery->NotDeleted()->where('is_active',1);
        })->where('group_id',2)->get();

        foreach($profiles as $oneProfile){
            $salary = $oneProfile->salary;
            $start_date = $oneProfile->start_date;
            $diff = $this->getMonths($start_date,date('Y-m-d'));
            for ($i = 1; $i <= $diff ; $i++) {
                $new_start_date = date("Y-m-d",strtotime("+".$i-1 ." months", strtotime($start_date)));
                $end_date = date("Y-m-d",strtotime("+".$i." months", strtotime($start_date)));
                $end_date = date('Y-m-d',strtotime('-1 day',strtotime($end_date)));
                $took = Expense::NotDeleted()->where('type',2)->where('user_id',$oneProfile->user_id)->where('created_at','>=',$new_start_date.' 00:00:00')->where('created_at','<=',$end_date.' 23:59:59')->sum('total');
                $salaryObj = Salary::where('user_id',$oneProfile->user_id)->where('start_date',$new_start_date)->where('end_date',$end_date)->first();
                if($salaryObj == null){
                    $salaryObj = new Salary;
                    $salaryObj->user_id = $oneProfile->user_id;
                    $salaryObj->start_date = $new_start_date;
                    $salaryObj->end_date = $end_date;
                    $salaryObj->paid = 0;
                    $salaryObj->paid_date = null;
                    $salaryObj->status = 1;
                    $salaryObj->created_by = USER_ID;
                    $salaryObj->created_at = DATE_TIME;
                    $salaryObj->save();
                }
            }
        }

        $usersList = Salary::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['users'] = User::getUserByType(2);
        return view('Expenses.Views.salary_index')
            ->with('data', (Object) $usersList);
    }

    public function salary_update($id) {
        $id = (int) $id;

        $salaryObj = Salary::getOne($id);
        if($salaryObj == null || $salaryObj->status == 2) {
            return Redirect('404');
        }
        $profileObj = $salaryObj->User->Profile;
        $took = Expense::NotDeleted()->where('type',2)->where('user_id',$salaryObj->user_id)->where('created_at','>=',$salaryObj->start_date.' 00:00:00')->where('created_at','<=',$salaryObj->end_date.' 23:59:59')->sum('total');
        $paid = $profileObj->salary - $took ;

        $salaryObj->paid = $paid;
        $salaryObj->status = 2;
        $salaryObj->paid_date = DATE_TIME;
        $salaryObj->updated_at = DATE_TIME;
        $salaryObj->updated_by = USER_ID;
        $salaryObj->save();

        $expenseObj = new Expense;
        $expenseObj->type = 1;
        $expenseObj->shop_id = $profileObj->shop_id;
        $expenseObj->currency_id = $profileObj->currency_id;
        $expenseObj->total = $paid;
        $expenseObj->description = 'راتب شهر '.(int)date('m',strtotime($salaryObj->start_date)).' لعامل '.$profileObj->display_name;
        $expenseObj->created_by = USER_ID;
        $expenseObj->created_at = DATE_TIME;
        $expenseObj->save();

        \Session::flash('success', "تنبيه! تم تصفية اجر العامل");
        return \Redirect::back()->withInput();
    }

}
