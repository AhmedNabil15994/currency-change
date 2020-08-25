<?php namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Currency;
use App\Models\Delegate;
use App\Models\Client;
use App\Models\Shop;
use App\Models\Details;
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
            'amount' => 'required',
            'details_id' => 'required',
        ];

        $message = [
            'type.required' => "يرجي اختيار نوع العمولة",
            'shop_id.required' => "يرجي اختيار الفرع",
            'amount.required' => "يرجي ادخال الكمية المراد تحويلها",
            'details_id.required' => "يرجي اختيار نوع التحويل",
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
        $data['currencies'] = Details::dataList('no_paginate')['data'];
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

        if($input['amount'] == 0 ) {
            \Session::flash('error', "يرجي ادخال كمية صحيحة");
            return redirect()->back()->withInput();
        }

        $shopObj = Shop::getOne($input['shop_id']);
        if($shopObj == null ) {
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


        $detailsObj = Details::getOne($input['details_id']);
        if($detailsObj == null ) {
            \Session::flash('error', "نوع عملية التحويل غير موجود");
            return redirect()->back()->withInput();
        }
        $detailsObj = Details::getData($detailsObj);
        
        $exchangeObj->type = $input['type'];
        $exchangeObj->shop_id = $input['shop_id'];
        $exchangeObj->details_id = $input['details_id'];
        $exchangeObj->user_id = $user_id;
        $exchangeObj->from_id = $detailsObj->from_id;
        $exchangeObj->to_id = $detailsObj->to_id;
        $exchangeObj->convert_price = $detailsObj->convert;
        $exchangeObj->amount = $input['amount'];
        $exchangeObj->paid = round($input['amount'] * $detailsObj->convert ,2);
        $exchangeObj->updated_at = DATE_TIME;
        $exchangeObj->updated_by = USER_ID;
        $exchangeObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['currencies'] = Details::dataList('no_paginate')['data'];
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

        if($input['amount'] == 0 ) {
            \Session::flash('error', "يرجي ادخال كمية صحيحة");
            return redirect()->back()->withInput();
        }

        $shopObj = Shop::getOne($input['shop_id']);
        if($shopObj == null ) {
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


        $detailsObj = Details::getOne($input['details_id']);
        if($detailsObj == null ) {
            \Session::flash('error', "نوع عملية التحويل غير موجود");
            return redirect()->back()->withInput();
        }
        $detailsObj = Details::getData($detailsObj);
        
        $exchangeObj = new Exchange;
        $exchangeObj->type = $input['type'];
        $exchangeObj->shop_id = $input['shop_id'];
        $exchangeObj->details_id = $input['details_id'];
        $exchangeObj->user_id = $user_id;
        $exchangeObj->from_id = $detailsObj->from_id;
        $exchangeObj->to_id = $detailsObj->to_id;
        $exchangeObj->convert_price = $detailsObj->convert;
        $exchangeObj->amount = $input['amount'];
        $exchangeObj->paid = round($input['amount'] * $detailsObj->convert ,2);
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
