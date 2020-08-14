<?php namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Shop;
use App\Models\Group;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class UsersControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ];

        $message = [
            'first_name.required' => "يرجي ادخال الاسم الاةل",
            'last_name.required' => "يرجي ادخال الاسم الاخير",
            'email.required' => "يرحي ادخال البريد الالكتروني",
            'email.format' => "يرحي ادخال البريد الالكتروني بصيغة صحيحة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateProfile(){
        $input = Input::all();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'gender' => 'required',
        ];

        $message = [
            'first_name.required' => "يرجي ادخال الاسم الاةل",
            'last_name.required' => "يرجي ادخال الاسم الاخير",
            'phone.required' => "يرجي ادخال رقم التليفون",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validatePassword(){
        $input = Input::all();

        $rules = [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];

        $message = [
            'password.required' => "يرجي ادخال كلمة مرور جديدة",
            'password.min' => "يجب ان تكون كلمة المرور علي الاقل 6 حروف",
            'password.confirmed' => "كلمة المرور غير متطابقة",
            'password_confirmation.required' => "يرجي تأكيد كلمة المرور",
            'password_confirmation.min' => "يجب ان تكون كلمة المرور علي الاقل 6 حروف",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = User::usersList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        return view('Users.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()
            ->with('Profile')
            ->whereHas('Profile', function() {})
            ->find($id);

        if($userObj == null) {
            return Redirect('404');
        }

        if (GROUP_ID != 1 && $userObj->group_id == 1) {
            \Session::flash('error', "ليس ليدك صلاحية لتعديل هذا المستخدم");
            return redirect()->back();
        }

        $data['groups'] = Group::getList();
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout']);
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = User::getData($userObj);
        return view('Users.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = User::getOne($id);
        if($userObj == null) {
            return Redirect('404');
        }

        $profileObj = $userObj->Profile;

        if (GROUP_ID != 1 && $profileObj->group_id == 1) {
            \Session::flash('error', "ليس ليدك صلاحية لتعديل هذا المستخدم");
            return redirect()->back();
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(User::checkUserByEmail($input['email'], $id) != null){
            \Session::flash('error', "هذا البريد الالكتروني مستخدم من قبل");
            return redirect()->back();
        }

        $userObj->email = $input['email'];

        if (isset($input['password'])) {
            $userObj->password = \Hash::make($input['password']);
        }

        if(isset($input['permissions'])){
            $profileObj->extra_rules = serialize($input['permissions']);
            $profileObj->save();
        }else{
            $profileObj->extra_rules = null;
            $profileObj->save();
        }
        
        $userObj->name = $input['first_name'].' '.$input['last_name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->save();

        User::saveProfile($userObj);
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $groupsList = Group::getList();
        $data['groups'] = $groupsList;
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout']);
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        return view('Users.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if (!isset($input['password'])) {
            \Session::flash('error', "يرجي ادخال كلمة المرور");
            return \Redirect::back()->withInput();
        }

        if(User::checkUserByPhone($input['phone']) != null){
            \Session::flash('error', "هذا رقم التليفون مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        if(User::checkUserByEmail($input['email']) != null){
            \Session::flash('error', "هذا البريد الالكتروني مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $userId = User::createOneUser();

        \Session::flash('success', "تبيه! تم اضافة المستخدم");
        return redirect()->to('users/edit/' . $userId);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = User::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
