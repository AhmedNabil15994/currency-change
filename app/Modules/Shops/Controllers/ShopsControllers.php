<?php namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ShopsControllers extends Controller {

    use \TraitsFunc;

    protected function validateCourse($input){
        $rules = [
            'title' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $message = [
            'title.required' => "يرجي ادخال اسم الفرع",
            'phone.required' => "يرحي ادخال رقم تليفون الفرع",
            'address.required' => "يرجي ادخال عنوان الفرع",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {
        $dataList = Shop::dataList();
        return view('Shops.Views.index')
            ->with('data', (Object) $dataList);
    }

    public function edit($id) {
        $id = (int) $id;

        $courseObj = Shop::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $data['data'] = Shop::getData($courseObj);
        return view('Shops.Views.edit')->with('data', (object) $data);      
    }

    public function update($id,Request $request) {
        $id = (int) $id;
        $input = \Input::all();
        
        $courseObj = Shop::getOne($id);
        if($courseObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateCourse($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $courseObj->title = $input['title'];
        $courseObj->phone = $input['phone'];
        $courseObj->address = $input['address'];
        $courseObj->updated_by = USER_ID;
        $courseObj->updated_at = DATE_TIME;
        $courseObj->save();

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $images = self::addImage($files, $courseObj->id);
            if ($images == false) {
                \Session::flash('error', "فشل في رفع الصورة");
                return \Redirect::back()->withInput();
            }
        }

        \Session::flash('success', "تنبيه! تم تعديل البيانات بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        return view('Shops.Views.add');
    }

    public function create(Request $request) {
        $input = \Input::all();
        
        $validate = $this->validateCourse($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }   

        $courseObj = new Shop;
        $courseObj->title = $input['title'];
        $courseObj->phone = $input['phone'];
        $courseObj->address = $input['address'];
        $courseObj->created_by = USER_ID;
        $courseObj->created_at = DATE_TIME;
        $courseObj->save();

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $images = self::addImage($files, $courseObj->id);
            if ($images == false) {
                \Session::flash('error', "فشل في رفع الصورة");
                return \Redirect::back()->withInput();
            }
        }

        \Session::flash('success', "تنبيه! تم اضافة الفرع بنجاح");
        return redirect()->to('shops/edit/' . $courseObj->id);
    }

    public function addImage($images, $id) {
        $fileName = \ImagesHelper::UploadImage('shops', $images, $id);
        if($fileName == false){
            return false;
        }
        $courseObj = Shop::find($id);
        $courseObj->image = $fileName;
        $courseObj->save();
       
        return true;
    }

    public function imageDelete($id) {
        $id = (int) $id;
        $courseObj = Shop::find($id);
        $courseObj->image = '';
        $courseObj->save();

        $data['status'] = \TraitsFunc::SuccessResponse("تم حذف الصورة");
        return response()->json($data);
    }

    public function delete($id) {
        $id = (int) $id;
        $courseObj = Shop::getOne($id);
        return \Helper::globalDelete($courseObj);
    }
}
