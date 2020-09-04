<?php namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Currency;
use App\Models\BankAccount;
use App\Models\Details;
use App\Models\Exchange;
use App\Models\Delegate;
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
            'delegate_id' => 'required',
            'currency_id' => 'required',
            'bank_account_id' => 'required',
            'balance' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع الحوالة البنكية",
            'company.required' => "يرجي ادخال اسم الشركة",
            'delegate_id.required' => "يرجي اختيار المندوب",
            'bank_account_id.required' => "يرجي اختيار الحساب البنكي",
            'currency_id.required' => "يرجي اختيار نوع العملة",
            'balance.required' => "يرجي ادخال الرصيد",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Transfer::dataList();
        $usersList['delegates'] = Delegate::dataList('no_paginate')['data'];
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

        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        $data['accounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['currencies2'] = Details::dataList('no_paginate')['data'];
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

        $commission_value = null;
        if($input['type'] == 2){
            if(!isset($input['details_id']) || empty($input['details_id'])){
                \Session::flash('error', 'يرجي اختيار نوع تحويل العملات');
                return redirect()->back()->withInput();
            }

            $detailsObj = Details::getOne($input['details_id']);
            if($detailsObj == null ) {
                \Session::flash('error', "نوع عملية التحويل غير موجود");
                return redirect()->back()->withInput();
            }
            $detailsObj = Details::getData($detailsObj);
            if(isset($input['commission_rate']) && isset($detailsObj->rate)){
                $commission_value = round($input['commission_rate'] / 100 * ($input['balance'] * $detailsObj->rate) ,2);
            }   

            $exchangeObj = Exchange::getOne($bankObj->exchange_id);
            $exchangeObj->type = 2;
            $exchangeObj->shop_id = $bankAccount->shop_id;
            $exchangeObj->details_id = $input['details_id'];
            $exchangeObj->user_id = 0;
            $exchangeObj->from_id = $detailsObj->from_id;
            $exchangeObj->to_id = $detailsObj->to_id;
            $exchangeObj->convert_price = $detailsObj->rate;
            $exchangeObj->amount = $input['balance'];
            $exchangeObj->paid = round(($input['balance'] * $detailsObj->rate) + $commission_value ,2);
            $exchangeObj->updated_at = DATE_TIME;
            $exchangeObj->updated_by = USER_ID;
            $exchangeObj->save();
        }
        
        $bankObj->type = $input['type'];
        $bankObj->delegate_id = $input['delegate_id'];
        $bankObj->company = $input['company'];
        $bankObj->company_account = $input['company_account'];
        $bankObj->currency_id = $bankAccount->currency_id;
        $bankObj->exchange_id = isset($exchangeObj->id) ? $exchangeObj->id : null;
        $bankObj->details_id = isset($input['details_id']) ? $input['details_id'] : null;
        $bankObj->new_currency_id = isset($detailsObj->to_id) ? $detailsObj->to_id : null;
        $bankObj->rate = isset($detailsObj->rate) ? $detailsObj->rate : null;
        $bankObj->total = isset($exchangeObj->paid) ? $exchangeObj->paid : null;
        $bankObj->balance = $input['balance'];
        $bankObj->commission_rate = isset($input['commission_rate']) ? $input['commission_rate'] : null;
        $bankObj->commission_value = $commission_value;
        $bankObj->bank_account_id = $input['bank_account_id'];
        $bankObj->updated_at = DATE_TIME;
        $bankObj->updated_by = USER_ID;
        $bankObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        $data['accounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['currencies2'] = Details::dataList('no_paginate')['data'];
        return view('Transfers.Views.add')->with('data', (object) $data);
    }

    public function getBanksAccounts($id){
        $id = (int) $id;
        return \Response::json((object) BankAccount::dataList(null,$id));
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
        $commission_value = null;
        if($input['type'] == 2){
            if(!isset($input['details_id']) || empty($input['details_id'])){
                \Session::flash('error', 'يرجي اختيار نوع تحويل العملات');
                return redirect()->back()->withInput();
            }

            $detailsObj = Details::getOne($input['details_id']);
            if($detailsObj == null ) {
                \Session::flash('error', "نوع عملية التحويل غير موجود");
                return redirect()->back()->withInput();
            }
            $detailsObj = Details::getData($detailsObj);
            
            if(isset($input['commission_rate']) && isset($detailsObj->rate)){
                $commission_value = round($input['commission_rate'] / 100 * ($input['balance'] * $detailsObj->rate) ,2);
            }   

            $exchangeObj = new Exchange;
            $exchangeObj->type = 2;
            $exchangeObj->shop_id = $bankAccount->shop_id;
            $exchangeObj->details_id = $input['details_id'];
            $exchangeObj->user_id = 0;
            $exchangeObj->from_id = $detailsObj->from_id;
            $exchangeObj->to_id = $detailsObj->to_id;
            $exchangeObj->convert_price = $detailsObj->rate;
            $exchangeObj->amount = $input['balance'];
            $exchangeObj->paid = round(($input['balance'] * $detailsObj->rate) + $commission_value ,2);
            $exchangeObj->created_at = DATE_TIME;
            $exchangeObj->created_by = USER_ID;
            $exchangeObj->save();
        }

        $bankObj = new Transfer;
        $bankObj->type = $input['type'];
        $bankObj->delegate_id = $input['delegate_id'];
        $bankObj->company = $input['company'];
        $bankObj->company_account = $input['company_account'];
        $bankObj->currency_id = $bankAccount->currency_id;
        $bankObj->exchange_id = isset($exchangeObj->id) ? $exchangeObj->id : null;
        $bankObj->details_id = isset($input['details_id']) ? $input['details_id'] : null;
        $bankObj->new_currency_id = isset($detailsObj->to_id) ? $detailsObj->to_id : null;
        $bankObj->rate = isset($detailsObj->rate) ? $detailsObj->rate : null;
        $bankObj->total = isset($exchangeObj->paid) ? $exchangeObj->paid : null;
        $bankObj->balance = $input['balance'];
        $bankObj->commission_rate = isset($input['commission_rate']) ? $input['commission_rate'] : null;
        $bankObj->commission_value = $commission_value;
        $bankObj->bank_account_id = $input['bank_account_id'];
        $bankObj->created_at = DATE_TIME;
        $bankObj->created_by = USER_ID;
        $bankObj->save();

        \Session::flash('success', "تبيه! تم اضافة الحساب البنكي");
        return redirect()->to('transfers/edit/' . $bankObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Transfer::getOne($id);
        if($userObj->type == 2){
            \Helper::globalDelete(Exchange::getOne($userObj->exchange_id));
        }
        return \Helper::globalDelete($userObj);
    }
}
