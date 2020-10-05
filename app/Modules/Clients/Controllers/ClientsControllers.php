<?php namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ClientsControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'name' => 'required',
            // 'identity' => 'required',
            'phone' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال اسم العميل",
            // 'identity.required' => "يرجي ادخال رقم الهوية",
            'phone.required' => 'يرجي ادخال رقم التليفون',
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Client::dataList();
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Clients.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Client::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['data'] = Client::getData($userObj);
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Clients.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = Client::getOne($id);
        if($userObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(Client::checkUserByPhone($input['phone'], $id) != null){
            \Session::flash('error', "هذا البريد الالكتروني مستخدم من قبل");
            return redirect()->back();
        }

        if(Client::checkUserByIdentity($input['identity'], $id) != null){
            \Session::flash('error', "رقم الهوية مستخدم من قبل");
            return redirect()->back();
        }

        $userObj->name = $input['name'];
        $userObj->phone = $input['phone'];
        $userObj->identity = $input['identity'];
        $userObj->currency_id = $input['currency_id'];
        $userObj->balance = $input['balance'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->updated_at = DATE_TIME;
        $userObj->updated_by = USER_ID;
        $userObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Clients.Views.add')->with('data',(object) $data);
    }

    public function create() {
        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if(Client::checkUserByPhone($input['phone']) != null){
            \Session::flash('error', "هذا رقم التليفون مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        if(Client::checkUserByIdentity($input['identity']) != null){
            \Session::flash('error', "رقم الهوية مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $userId = Client::createOneClient();

        \Session::flash('success', "تبيه! تم اضافة العميل");
        return redirect()->to('clients/edit/' . $userId);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Client::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
