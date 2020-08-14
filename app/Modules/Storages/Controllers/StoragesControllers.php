<?php namespace App\Http\Controllers;

use App\Models\ShopStorage;
use App\Models\Currency;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class StoragesControllers extends Controller {

    use \TraitsFunc;

    protected function validateInputs(){
        $input = Input::all();

        $rules = [
            'shop_id' => 'required',
        ];

        $message = [
            'shop_id.required' => "يرجي اختيار الفرع",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $usersList = ShopStorage::dataList();
        $usersList['shops'] = Shop::dataList('no_paginate')['data'];
        $usersList['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Storages.Views.index')
            ->with('data', (Object) $usersList);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = ShopStorage::getOne($id);

        if($userObj == null) {
            return Redirect('404');
        }

        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        $data['data'] = ShopStorage::getData($userObj);
        return view('Storages.Views.edit')->with('data', (object) $data);
    }

    public function update($id) {
        $id = (int) $id;

        $bankObj = ShopStorage::getOne($id);
        if($bankObj == null ) {
            return Redirect('404');
        }

        $input = \Input::all();

        $supplyData = json_decode($input['data']);
        if(empty($supplyData)){
            return \TraitsFunc::ErrorMessage("يرجي مراجعة عناصر الصندوق", 400);   
        }

        ShopStorage::where('shop_id',$id)->delete();

        for ($i = 0; $i < count($supplyData) ; $i++) {
            $supplyItemObj = new ShopStorage;
            $supplyItemObj->shop_id = $input['shop_id'];
            $supplyItemObj->is_active = $input['is_active'];
            $supplyItemObj->currency_id = $supplyData[$i][0];
            $supplyItemObj->balance = $supplyData[$i][1];
            $supplyItemObj->created_at = DATE_TIME;
            $supplyItemObj->created_by = USER_ID;
            $supplyItemObj->save();
        }
       
        $statusObj['status'] = \TraitsFunc::SuccessResponse("تنبيه! تم تعديل البيانات بنجاح");
        return \Response::json((object) $statusObj);  
    }

    public function add() {
        $data['shops'] = Shop::dataList('no_paginate')['data'];
        $data['currencies'] = Currency::dataList('no_paginate')['data'];
        return view('Storages.Views.add')->with('data', (object) $data);
    }

    public function create(Request $request) {
        $input = \Input::all();
        $supplyData = json_decode($input['data']);
        if(empty($supplyData)){
            return \TraitsFunc::ErrorMessage("يرجي مراجعة عناصر الصندوق", 400);   
        }

        for ($i = 0; $i < count($supplyData) ; $i++) {
            $storageObj = ShopStorage::NotDeleted()->where('shop_id',$input['shop_id'])->first();
            if($storageObj != null){
                return \TraitsFunc::ErrorMessage("هذا الفرع لديه صندوق من قبل", 400);   
            }
        }

        for ($i = 0; $i < count($supplyData) ; $i++) {
            $supplyItemObj = new ShopStorage;
            $supplyItemObj->shop_id = $input['shop_id'];
            $supplyItemObj->is_active = $input['is_active'];
            $supplyItemObj->currency_id = $supplyData[$i][0];
            $supplyItemObj->balance = $supplyData[$i][1];
            $supplyItemObj->created_at = DATE_TIME;
            $supplyItemObj->created_by = USER_ID;
            $supplyItemObj->save();
        }
        $statusObj['status'] = \TraitsFunc::SuccessResponse("تم ادخال بيانات الصندوق بنجاح");
        return \Response::json((object) $statusObj);  
    }

    public function delete($id) {
        $id = (int) $id;
        $userObj = ShopStorage::getOne($id);
        return \Helper::globalDelete($userObj);
    }
}
