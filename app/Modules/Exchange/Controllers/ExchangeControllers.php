<?php namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Currency;
use App\Models\Delegate;
use App\Models\Client;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ExchangeControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'type' => 'required',
            'shop_id' => 'required',
            'from_id' => 'required',
            'to_id' => 'required',
            'price' => 'required',
            'to_shop_id' => 'required',
            'amount' => 'required',
            'bank_price' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع العمولة",
            'shop_id.required' => "يرجي اختيار فرع الايداع",
            'from_id.required' => "يرجي اختيار العملة المحول منها",
            'to_id.required' => "يرجي اختيار العملة المحول اليها",
            'price.required' => "يرجي ادخال سعر التغيير",
            'to_shop_id.required' => "يرجي اختيار فرع السحب",
            'amount.required' => "يرجي ادخال الكمية المراد تحويلها",
            'bank_price.required' => "يرجي ادخال سعر البنك",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Exchange::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        $usersList['delegates'] = Delegate::dataList('no_paginate')['data'];
        $usersList['clients'] = Client::dataList('no_paginate')['data'];
        return view('Exchange.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Exchange::getOne($id);
        if($userObj == null) {  
            return Redirect('404');
        }

        $data['data'] = Exchange::getData($userObj);
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        $data['clients'] = Client::dataList('no_paginate')['data'];
        return view('Exchange.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $exchangeObj = Exchange::getOne($id);
        if($exchangeObj == null ) {
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

        $toShopObj = Shop::getOne($input['to_shop_id']);
        if($toShopObj == null ) {
            \Session::flash('error', "هذا الفرع غير موجود");
            return redirect()->back()->withInput();
        }

        $user_id = 0 ;
        if($input['type'] == 1){
            if($input['client_id'] == 0){
                if(empty($input['client_name']) ) {
                    \Session::flash('error', "يرجي ادخال اسم العميل");
                    return redirect()->back()->withInput();
                }
                if(empty($input['client_phone']) ) {
                    \Session::flash('error', "يرجي ادخال رقم تليفون العميل");
                    return redirect()->back()->withInput();
                }

                $clientObj = Client::NotDeleted()->where('name',$input['client_name'])->where('phone',$input['client_phone'])->first();
                if($clientObj == null){
                    $clientObj = new Client;
                    $clientObj->phone = $input['client_phone'];
                    $clientObj->identity = '';
                    $clientObj->name = $input['client_name'];
                    $clientObj->is_active = 1;
                    $clientObj->created_at = DATE_TIME;
                    $clientObj->created_by = USER_ID;
                    $clientObj->save();
                }
            }else{
                $clientObj = Client::getOne($input['client_id']);
                if($clientObj == null ) {
                    \Session::flash('error', "هذا العميل غير موجود");
                    return redirect()->back()->withInput();
                }
            }
            $user_id = $clientObj->id;
        }elseif($input['type'] == 2){
            $delegateObj = Delegate::getOne($input['delegate_id']);
            if($delegateObj == null ) {
                \Session::flash('error', "هذا المندوب غير موجود");
                return redirect()->back()->withInput();
            }
            $user_id = $delegateObj->id;
        }

        
        $exchangeObj->type = $input['type'];
        $exchangeObj->shop_id = $input['shop_id'];
        $exchangeObj->to_shop_id = $input['to_shop_id'];
        $exchangeObj->bank_price = $input['bank_price'];
        $exchangeObj->user_id = $user_id;
        $exchangeObj->from_id = $input['from_id'];
        $exchangeObj->to_id = $input['to_id'];
        $exchangeObj->convert_price = $input['price'];
        $exchangeObj->amount = floatval($input['amount']);
        $exchangeObj->paid = round(floatval($input['amount']) * $input['price'] ,2);
        $exchangeObj->updated_at = DATE_TIME;
        $exchangeObj->updated_by = USER_ID;
        $exchangeObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['clients'] = Client::dataList('no_paginate')['data'];
        return view('Exchange.Views.add')->with('data',(object) $data);
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

        $toShopObj = Shop::getOne($input['to_shop_id']);
        if($toShopObj == null ) {
            \Session::flash('error', "هذا الفرع غير موجود");
            return redirect()->back()->withInput();
        }

        $user_id = 0 ;
        if($input['type'] == 1){
            if($input['client_id'] == 0){
                if(empty($input['client_name']) ) {
                    \Session::flash('error', "يرجي ادخال اسم العميل");
                    return redirect()->back()->withInput();
                }
                if(empty($input['client_phone']) ) {
                    \Session::flash('error', "يرجي ادخال رقم تليفون العميل");
                    return redirect()->back()->withInput();
                }

                $clientObj = Client::NotDeleted()->where('name',$input['client_name'])->where('phone',$input['client_phone'])->first();
                if($clientObj == null){
                    $clientObj = new Client;
                    $clientObj->phone = $input['client_phone'];
                    $clientObj->identity = '';
                    $clientObj->name = $input['client_name'];
                    $clientObj->is_active = 1;
                    $clientObj->created_at = DATE_TIME;
                    $clientObj->created_by = USER_ID;
                    $clientObj->save();
                }
            }else{
                $clientObj = Client::getOne($input['client_id']);
                if($clientObj == null ) {
                    \Session::flash('error', "هذا العميل غير موجود");
                    return redirect()->back()->withInput();
                }
            }
            $user_id = $clientObj->id;
        }elseif($input['type'] == 2){
            $delegateObj = Delegate::getOne($input['delegate_id']);
            if($delegateObj == null ) {
                \Session::flash('error', "هذا المندوب غير موجود");
                return redirect()->back()->withInput();
            }
            $user_id = $delegateObj->id;
        }

        
        $exchangeObj = new Exchange;
        $exchangeObj->type = $input['type'];
        $exchangeObj->shop_id = $input['shop_id'];
        $exchangeObj->to_shop_id = $input['to_shop_id'];
        $exchangeObj->bank_price = $input['bank_price'];
        $exchangeObj->user_id = $user_id;
        $exchangeObj->from_id = $input['from_id'];
        $exchangeObj->to_id = $input['to_id'];
        $exchangeObj->convert_price = $input['price'];
        $exchangeObj->amount = floatval($input['amount']);
        $exchangeObj->paid = round(floatval($input['amount']) * $input['price'] ,2);
        $exchangeObj->created_at = DATE_TIME;
        $exchangeObj->created_by = USER_ID;
        $exchangeObj->save();
        \Session::flash('success', "تبيه! تم اضافة بيانات عملية الاستبدال");
        return redirect()->to('exchanges/edit/' . $exchangeObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Exchange::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
