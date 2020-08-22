<?php namespace App\Http\Controllers;

use App\Models\Delegate;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class CommissionsControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'commission' => 'required',
            'delegate_id' => 'required',
            'valid_until' => 'required',
        ];

        $message = [
            'delegate_id.required' => "يرجي اختيار المندوب",
            'valid_until.required' => 'يرجي ادخال تاريخ الانتهاء',
            'commission.required' => 'يرجي ادخال العمولة',
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Commission::dataList();
        $usersList['delegates'] = Delegate::dataList('no_paginate')['data'];
        return view('DelegateCommission.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Commission::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        $data['data'] = Commission::getData($userObj);
        return view('DelegateCommission.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = Commission::getOne($id);
        if($userObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        $delegateObj = Delegate::getOne($input['delegate_id']);
        if($delegateObj == null){
            \Session::flash('error', 'هذا المندوب غير موجود');
            return redirect()->back();
        }

        $userObj->delegate_id = $input['delegate_id'];
        $userObj->valid_until = $input['valid_until'];
        $userObj->commission = doubleval($input['commission']);
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->updated_at = DATE_TIME;
        $userObj->updated_by = USER_ID;
        $userObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['delegates'] = Delegate::dataList('no_paginate')['data'];
        return view('DelegateCommission.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        $delegateObj = Delegate::getOne($input['delegate_id']);
        if($delegateObj == null){
            \Session::flash('error', 'هذا المندوب غير موجود');
            return redirect()->back();
        }

        $userObj = new Commission;
        $userObj->delegate_id = $input['delegate_id'];
        $userObj->valid_until = $input['valid_until'];
        $userObj->commission = doubleval($input['commission']);
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->created_at = DATE_TIME;
        $userObj->created_by = USER_ID;
        $userObj->save();

        \Session::flash('success', "تبيه! تم اضافة عمولة المندوب");
        return redirect()->to('commissions/edit/' . $userObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Commission::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
