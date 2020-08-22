<?php namespace App\Http\Controllers;

use App\Models\Delegate;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class DelegatesControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'name' => 'required',
            'shop_id' => 'required',
            'phone' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال اسم المندوب",
            'shop_id.required' => "يرجي اختيار الفرع",
            'phone.required' => 'يرجي ادخال رقم التليفون',
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Delegate::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        return view('Delegates.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Delegate::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['data'] = Delegate::getData($userObj);
        return view('Delegates.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = Delegate::getOne($id);
        if($userObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(Delegate::checkUserByPhone($input['phone'], $id) != null){
            \Session::flash('error', "هذا البريد الالكتروني مستخدم من قبل");
            return redirect()->back();
        }

        $userObj->name = $input['name'];
        $userObj->phone = $input['phone'];
        $userObj->address = $input['address'];
        $userObj->shop_id = implode(',', $input['shop_id']);
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->updated_at = DATE_TIME;
        $userObj->updated_by = USER_ID;
        $userObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        return view('Delegates.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if(Delegate::checkUserByPhone($input['phone']) != null){
            \Session::flash('error', "هذا رقم التليفون مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $userId = Delegate::createOneDelegate();

        \Session::flash('success', "تبيه! تم اضافة المندبو");
        return redirect()->to('delegates/edit/' . $userId);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Delegate::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
