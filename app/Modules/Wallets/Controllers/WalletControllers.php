<?php namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Delegate;
use App\Models\Client;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class WalletControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'type' => 'required',
            'shop_id' => 'required',
            'from_id' => 'required',
            'to_id' => 'required',
            'client_id' => 'required',
            'convert_price' => 'required',
            'amount' => 'required',
            'bank_convert_price' => 'required',
            'commission_type' => 'required',
            'commission_value' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع العمولة",
            'shop_id.required' => "يرجي اختيار فرع الايداع",
            'client_id.required' => "يرجي اختيار العميل",
            'convert_price.required' => "يرجي ادخال سعر التغيير",
            'from_id.required' => "يرجي اختيار العملة المحول منها",
            'to_id.required' => "يرجي اختيار العملة المحول اليها",
            'amount.required' => "يرجي ادخال الكمية المراد تحويلها",
            'bank_convert_price.required' => "يرجي ادخال سعر البنك",
            'commission_type.required' => "يرجي اختيار نوع العمولة",
            'commission_value.required' => "يرجي ادخال قيمة العمولة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Wallet::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        $usersList['clients'] = Client::dataList('no_paginate')['data'];
        return view('Wallets.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Wallet::getOne($id);
        if($userObj == null) {  
            return Redirect('404');
        }

        $data['data'] = Wallet::getData($userObj);
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['clients'] = Client::dataList('no_paginate')['data'];
        return view('Wallets.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $walletObj = Wallet::getOne($id);
        if($walletObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        $input = \Input::all();

        if(floatval($input['amount']) == 0 ) {
            \Session::flash('error', "يرجي ادخال كمية صحيحة");
            return redirect()->back()->withInput();
        }

        $shopObj = Shop::getOne($input['shop_id']);
        if($shopObj == null ) {
            \Session::flash('error', "هذا الفرع غير موجود");
            return redirect()->back()->withInput();
        }

        $clientObj = Client::getOne($input['client_id']);
        if($clientObj == null ) {
            \Session::flash('error', "هذا العميل غير موجود");
            return redirect()->back()->withInput();
        }


        $walletObj->type = $input['type'];
        $walletObj->commission_type = $input['commission_type'];
        $walletObj->commission_value = $input['commission_value'];
        $walletObj->commission = $input['commission_type'] == 1 ? floatval($input['commission_value']) : round( (floatval($input['commission_value']) / 100 ) *  floatval($input['amount'])  ,2);
        $walletObj->shop_id = $input['shop_id'];
        $walletObj->client_id = $input['client_id'];
        $walletObj->from_id = $input['from_id'];
        $walletObj->to_id = $input['to_id'];
        $walletObj->convert_price = $input['convert_price'];
        $walletObj->amount = floatval($input['amount']);
        $walletObj->bank_convert_price = floatval($input['bank_convert_price']);
        $walletObj->gain = round(round(floatval($input['amount']) * $input['convert_price'] ,2) - round(floatval($input['amount']) * floatval($input['bank_convert_price']) ,2) ,2);
        $walletObj->total = round(floatval($input['amount']) * floatval($input['convert_price']) ,2);
        $walletObj->updated_at = DATE_TIME;
        $walletObj->updated_by = USER_ID;
        $walletObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['clients'] = Client::dataList('no_paginate')['data'];
        return view('Wallets.Views.add')->with('data',(object) $data);
    }

    public function create() {
        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if(floatval($input['amount']) == 0 ) {
            \Session::flash('error', "يرجي ادخال كمية صحيحة");
            return redirect()->back()->withInput();
        }

        $shopObj = Shop::getOne($input['shop_id']);
        if($shopObj == null ) {
            \Session::flash('error', "هذا الفرع غير موجود");
            return redirect()->back()->withInput();
        }

        $clientObj = Client::getOne($input['client_id']);
        if($clientObj == null ) {
            \Session::flash('error', "هذا العميل غير موجود");
            return redirect()->back()->withInput();
        }

        
        $walletObj = new Wallet;
        $walletObj->type = $input['type'];
        $walletObj->commission_type = $input['commission_type'];
        $walletObj->commission_value = $input['commission_value'];
        $walletObj->commission = $input['commission_type'] == 1 ? floatval($input['commission_value']) : round( (floatval($input['commission_value']) / 100 ) *  floatval($input['amount'])  ,2);
        $walletObj->shop_id = $input['shop_id'];
        $walletObj->client_id = $input['client_id'];
        $walletObj->from_id = $input['from_id'];
        $walletObj->to_id = $input['to_id'];
        $walletObj->convert_price = $input['convert_price'];
        $walletObj->amount = floatval($input['amount']);
        $walletObj->bank_convert_price = floatval($input['bank_convert_price']);
        $walletObj->gain = round(round(floatval($input['amount']) * floatval($input['convert_price']) ,2) - round(floatval($input['amount']) * floatval($input['bank_convert_price']) ,2) ,2);
        $walletObj->total = round(floatval($input['amount']) * floatval($input['convert_price']) ,2);
        $walletObj->created_at = DATE_TIME;
        $walletObj->created_by = USER_ID;
        $walletObj->save();
        \Session::flash('success', "تبيه! تم اضافة بيانات عملية الاستبدال");
        return redirect()->to('wallets/edit/' . $walletObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Wallet::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
