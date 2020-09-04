<?php namespace App\Http\Controllers;

use App\Models\StorageTransfer;
use App\Models\Currency;
use App\Models\ShopStorage;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class StorageTransfersControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'storage_id' => 'required',
            'type' => 'required',
            'to_id' => 'required',
            'total' => 'required',
            'currency_id' => 'required',
        ];

        $message = [
            'storage_id.required' => "يرجي اختيار الصندوق المحول منه",
            'type.required' => "يرجي اختيار نوع التحويل",
            'to_id.required' => "يرجي اختيار المحول اليه",
            'total.required' => "يرجي ادخال الرصيد المحول",
            'currency_id.required' => "يرجي اختيار نوع العملة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = StorageTransfer::dataList();
        $usersList['storages'] = ShopStorage::dataList('no_paginate')['data'];
        $usersList['bankAccounts'] = BankAccount::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('StorageTransfers.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function get_to($id){
        $id = (int) $id;
        if($id == 1){
            return \Response::json((object) ShopStorage::dataList('no_paginate')['data']);
        }elseif($id == 2){
            return \Response::json((object) BankAccount::dataList('no_paginate')['data']);
        }
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = StorageTransfer::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }
        $data['storages'] = ShopStorage::dataList('no_paginate')['data'];
        $data['bankAccounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = StorageTransfer::getData($userObj);
        return view('StorageTransfers.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $bankObj = StorageTransfer::getOne($id);
        if($bankObj == null ) {
            return Redirect('404');
        }

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $input = \Input::all();
        if($input['storage_id'] == $input['to_id'] && $input['type'] == 1){
            \Session::flash('error', "تنبيه! لا يمكن التحويل لنفس الصندوق");
            return \Redirect::back()->withInput();
        }

        if($input['type'] == 1){
            $storageObj = ShopStorage::NotDeleted()->where('id',$input['to_id'])->first();
            $shop_id = $storageObj->shop_id;
            $newStorageObj = ShopStorage::NotDeleted()->where('shop_id',$shop_id)->where('currency_id',$input['currency_id'])->first();
            $to_id = $newStorageObj->id;
        }else{
            $to_id = $input['to_id'];
        }

        $shop_id2 = ShopStorage::getOneById($input['storage_id'])->shop_id;
        
        $bankObj->storage_id = $input['storage_id'];
        $bankObj->type = $input['type'];
        $bankObj->to_id = $to_id;
        $bankObj->currency_id = $input['currency_id'];
        $bankObj->total = $input['total'];
        $bankObj->to_shop_id = $shop_id;
        $bankObj->from_shop_id = $shop_id2;
        $bankObj->updated_at = DATE_TIME;
        $bankObj->updated_by = USER_ID;
        $bankObj->save();
       
        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['storages'] = ShopStorage::dataList('no_paginate')['data'];
        $data['bankAccounts'] = BankAccount::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('StorageTransfers.Views.add')->with('data', (object) $data);
    }

    public function create() {

        $validate = $this->validateInputs();
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $input = \Input::all();
        if($input['storage_id'] == $input['to_id'] && $input['type'] == 1){
            \Session::flash('error', "تنبيه! لا يمكن التحويل لنفس الصندوق");
            return \Redirect::back()->withInput();
        }

        if($input['type'] == 1){
            $storageObj = ShopStorage::NotDeleted()->where('id',$input['to_id'])->first();
            $shop_id = $storageObj->shop_id;
            $newStorageObj = ShopStorage::NotDeleted()->where('shop_id',$shop_id)->where('currency_id',$input['currency_id'])->first();
            $to_id = $newStorageObj->id;
        }else{
            $to_id = $input['to_id'];
            $shop_id = BankAccount::getOne($input['to_id'])->shop_id;
        }

        $shop_id2 = ShopStorage::getOneById($input['storage_id'])->shop_id;

        $bankObj = new StorageTransfer;
        $bankObj->storage_id = $input['storage_id'];
        $bankObj->type = $input['type'];
        $bankObj->to_id = $to_id;
        $bankObj->currency_id = $input['currency_id'];
        $bankObj->to_shop_id = $shop_id;
        $bankObj->from_shop_id = $shop_id2;
        $bankObj->total = $input['total'];
        $bankObj->created_at = DATE_TIME;
        $bankObj->created_by = USER_ID;
        $bankObj->save();

        \Session::flash('success', "تبيه! تم اضافة الحساب البنكي");
        return redirect()->to('storage-transfers/edit/' . $bankObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = StorageTransfer::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
