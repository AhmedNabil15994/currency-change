<?php namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class CurrencyControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'name' => 'required',
            'code' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال اسم العملة",
            'code.required' => "يرجي ادخال كود العملة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Currency::dataList();
        return view('Currency.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Currency::getOne($id);
        if($userObj == null) {  
            return Redirect('404');
        }

        $data['data'] = Currency::getData($userObj);
        return view('Currency.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = Currency::getOne($id);
        if($userObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        if(Currency::checkByName($input['name'], $id) != null){
            \Session::flash('error', "هذه العملة موجودة");
            return redirect()->back();
        }

        if(Currency::checkByCode($input['code'], $id) != null){
            \Session::flash('error', "كود العملة مستخدم من قبل");
            return redirect()->back();
        }

        $userObj->name = $input['name'];
        $userObj->code = strtoupper($input['code']);
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->updated_at = DATE_TIME;
        $userObj->updated_by = USER_ID;
        $userObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        return view('Currency.Views.add');
    }

    public function create() {
        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        if(Currency::checkByName($input['name']) != null){
            \Session::flash('error', "هذا رقم التليفون مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        if(Currency::checkByCode($input['code']) != null){
            \Session::flash('error', "رقم الهوية مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $userId = Currency::createOneCurrency();

        \Session::flash('success', "تبيه! تم اضافة العملة");
        return redirect()->to('currencies/edit/' . $userId);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Currency::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
