<?php namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class BankAccountsControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'shop_id' => 'required',
            'name' => 'required',
            'account_number' => 'required',
            'balance' => 'required',
            'currency_id' => 'required',
        ];

        $message = [
            'shop_id.required' => "يرجي اختيار الفرع",
            'name.required' => "يرجي ادخال اسم البنك",
            'account_number.required' => "يرجي ادخال رقم الحساب",
            'balance.required' => "يرجي ادخال الرصيد الموجود في الحساب البنكي",
            'currency_id.required' => "يرجي اختيار نوع العملة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = BankAccount::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('BankAccounts.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = BankAccount::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = BankAccount::getData($userObj);
        return view('BankAccounts.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $bankObj = BankAccount::getOne($id);
        if($bankObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(BankAccount::checkByAccountNumber($input['account_number'], $id) != null){
            \Session::flash('error', "هذا رقم الحساب مستخدم من قبل");
            return redirect()->back();
        }

        $bankObj->name = $input['name'];
        $bankObj->shop_id = $input['shop_id'];
        $bankObj->currency_id = $input['currency_id'];
        $bankObj->balance = $input['balance'];
        $bankObj->account_number = $input['account_number'];
        $bankObj->is_active = isset($input['active']) ? 1 : 0;
        $bankObj->updated_at = DATE_TIME;
        $bankObj->updated_by = USER_ID;
        $bankObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('BankAccounts.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if(BankAccount::checkByAccountNumber($input['account_number']) != null){
            \Session::flash('error', "هذا رقم الحساب مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $bankObj = new BankAccount;
        $bankObj->name = $input['name'];
        $bankObj->shop_id = $input['shop_id'];
        $bankObj->currency_id = $input['currency_id'];
        $bankObj->balance = $input['balance'];
        $bankObj->account_number = $input['account_number'];
        $bankObj->is_active = isset($input['active']) ? 1 : 0;
        $bankObj->created_at = DATE_TIME;
        $bankObj->created_by = USER_ID;
        $bankObj->save();

        \Session::flash('success', "تبيه! تم اضافة الحساب البنكي");
        return redirect()->to('bank-accounts/edit/' . $bankObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = BankAccount::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
