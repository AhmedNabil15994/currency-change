<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthControllers extends Controller {

    use \TraitsFunc;

    public function login() {
        if(\Session::has('user_id')){
            return redirect('/exchanges');
        }
        return view('login');
    }

	public function doLogin() {

        $input = \Input::all();

        $rules = array(
            'email' => 'required',
            'password' => 'required',
        );

        $message = array(
            'email.required' => "يرجي ادخال البريد الالكتروني",
            'password.required' => "يرجي ادخال كلمة المرور",
        );

        $validate = \Validator::make($input, $rules,$message);

        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect('/login');
        }

        $email = $input['email'];
        $userObj = User::getLoginUser($email);
        
        if ($userObj == null) {
            \Session::flash('error', "هذا المستخدم غير موجود او غير مفعل");
            return redirect('/login');
        }

        $checkPassword = Hash::check($input['password'], $userObj->password);

        if ($checkPassword == null) {
            \Session::flash('error', "كلمة المرور خاطئة");
            return redirect('/login');  
        }

        $dateTime = DATE_TIME;
        $userObj->last_login = $dateTime;
        $userObj->save();

        $profile = $userObj->Profile;
        $group = $profile->Group;

        $isAdmin = in_array($profile->group_id, [1, 2]) ? true : false;

        $dataObj = new \stdClass();
        $dataObj->email = $userObj->email;
        $dataObj->first_name = $profile->first_name;
        $dataObj->last_name = $profile->last_name;
        $dataObj->full_name = $profile->display_name;
        $dataObj->last_login = $userObj->last_login;
        $dataObj->group_id = (int) $profile->group_id;

        session(['group_id' => $dataObj->group_id]);
        session(['last_login' => $dataObj->last_login]);
        session(['user_id' => $userObj->id]);
        session(['first_name' => $dataObj->first_name]);
        session(['last_name' => $dataObj->last_name]);
        session(['full_name' => $dataObj->full_name]);
        session(['email' => $dataObj->email]);
        session(['is_admin' => $isAdmin]);
        session(['group_name' => $profile->Group->title]);
        session(['shop_id' => $profile->shop_id]);
        if($profile->shop_id != null){
            session(['shop_name' => $profile->Shop->title]);
        }

        \Session::flash('success', "مرحبا بك " . $dataObj->first_name);
        return redirect('/exchanges');
	}

	public function logout() {
        \Auth::logout();
        session()->flush();

        \Session::flash('success', "نراك قريبا ;)");
        return redirect('/login');
	}
}
