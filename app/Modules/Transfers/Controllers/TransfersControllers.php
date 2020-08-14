<?php namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Currency;
use App\Models\BankAccount;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class TransfersControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'type' => 'required',
            'company' => 'required',
            'client_id' => 'required',
            'bank_account_id' => 'required',
            'balance' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع الحوالة البنكية",
            'company.required' => "يرجي ادخال اسم الشركة",
            'client_id.required' => "يرجي اختيار العميل",
            'bank_account_id.required' => "يرجي اختيار الحساب البنكي",
            'balance.required' => "يرجي ادخال الرصيد",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Transfer::dataList();
        $usersList['clients'] = Client::dataList('no_paginate')['data'];
        $usersList['accounts'] = BankAccount::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Transfers.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Transfer::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['clients'] = Client::dataList('no_paginate')['data'];
        $data['accounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = Transfer::getData($userObj);
        return view('Transfers.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $bankObj = Transfer::getOne($id);
        if($bankObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        $bankAccount = BankAccount::getOne($input['bank_account_id']);
        if($bankAccount == null){
            \Session::flash('error', "هذا الحساب البنكي غير موجود");
        }

        $bankObj->type = $input['type'];
        $bankObj->client_id = $input['client_id'];
        $bankObj->company = $input['company'];
        $bankObj->currency_id = $bankAccount->currency_id;
        $bankObj->balance = $input['balance'];
        $bankObj->bank_account_id = $input['bank_account_id'];
        $bankObj->is_active = isset($input['active']) ? 1 : 0;
        $bankObj->updated_at = DATE_TIME;
        $bankObj->updated_by = USER_ID;
        $bankObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['clients'] = Client::dataList('no_paginate')['data'];
        $data['accounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Transfers.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        $bankAccount = BankAccount::getOne($input['bank_account_id']);
        if($bankAccount == null){
            \Session::flash('error', "هذا الحساب البنكي غير موجود");
        }

        $bankObj = new Transfer;
        $bankObj->type = $input['type'];
        $bankObj->client_id = $input['client_id'];
        $bankObj->company = $input['company'];
        $bankObj->currency_id = $bankAccount->currency_id;
        $bankObj->balance = $input['balance'];
        $bankObj->bank_account_id = $input['bank_account_id'];
        $bankObj->is_active = isset($input['active']) ? 1 : 0;
        $bankObj->created_at = DATE_TIME;
        $bankObj->created_by = USER_ID;
        $bankObj->save();

        \Session::flash('success', "تبيه! تم اضافة الحساب البنكي");
        return redirect()->to('transfers/edit/' . $bankObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Transfer::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
