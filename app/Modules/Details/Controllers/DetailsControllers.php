<?php namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class DetailsControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'from_id' => 'required',
            'to_id' => 'required',
            'type' => 'required',
            'rate' => 'required',
        ];

        $message = [
            'from_id.required' => "يرجي اختيار العملة (من)",
            'to_id.required' => "يرجي اختيار العملة (الي)",
            'type.required' => "يرجي اختيار نوع العمولة",
            'rate.required' => "يرجي ادخال قيمة العمولة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = Details::dataList();
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Details.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = Details::getOne($id);
        if($userObj == null) {  
            return Redirect('404');
        }

        $data['data'] = Details::getData($userObj);
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Details.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $userObj = Details::getOne($id);
        if($userObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();

        $fromObj = Currency::getOne($input['from_id']);
        if($fromObj == null ) {
            \Session::flash('error', "العملة (من) غير موجودة");
            return redirect()->back()->withInput();
        }

        $toObj = Currency::getOne($input['to_id']);
        if($toObj == null ) {
            \Session::flash('error', "العملة (الي) غير موجودة");
            return redirect()->back()->withInput();
        }

        if($input['from_id'] == $input['to_id']){
            \Session::flash('error', "يرجي اختيار نوع عملات مختلفة");
            return redirect()->back()->withInput();
        }

        if(Details::checkRecord($input['from_id'],$input['to_id'],$id) != null){
            \Session::flash('error', "هذا رقم التليفون مستخدم من قبل");
            return redirect()->back()->withInput();
        }

        $userObj->from_id = $input['from_id'];
        $userObj->to_id = $input['to_id'];
        $userObj->type = $input['type'];
        $userObj->rate = floatval($input['rate']);
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->updated_at = DATE_TIME;
        $userObj->updated_by = USER_ID;
        $userObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Details.Views.add')->with('data',(object) $data);
    }

    public function create() {
        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();

        $fromObj = Currency::getOne($input['from_id']);
        if($fromObj == null ) {
            \Session::flash('error', "العملة (من) غير موجودة");
            return redirect()->back()->withInput();
        }

        $toObj = Currency::getOne($input['to_id']);
        if($toObj == null ) {
            \Session::flash('error', "العملة (الي) غير موجودة");
            return redirect()->back()->withInput();
        }

        if($input['from_id'] == $input['to_id']){
            \Session::flash('error', "يرجي اختيار نوع عملات مختلفة");
            return redirect()->back()->withInput();
        }

        if(Details::checkRecord($input['from_id'],$input['to_id']) != null){
            \Session::flash('error', "هذا التحويلة موجودة من قبل");
            return redirect()->back()->withInput();
        }

        $detailsObj = new Details;
        $detailsObj->from_id = $input['from_id'];
        $detailsObj->to_id = $input['to_id'];
        $detailsObj->type = $input['type'];
        $detailsObj->rate = floatval($input['rate']);
        $detailsObj->is_active = isset($input['active']) ? 1 : 0;
        $detailsObj->created_at = DATE_TIME;
        $detailsObj->created_by = USER_ID;
        $detailsObj->save();
        \Session::flash('success', "تبيه! تم اضافة البيانات");
        return redirect()->to('details/edit/' . $detailsObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = Details::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
