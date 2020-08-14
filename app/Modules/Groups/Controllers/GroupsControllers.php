<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GroupsControllers extends Controller {

    use \TraitsFunc;

    protected function validateGroup($input){
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => "يرجي ادخال الاسم",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index() {

        $groupsList = Group::groupsList();
        return view('Groups.Views.index')
            ->with('data', (Object) $groupsList);
    }

    public function edit($id) {
        $id = (int) $id;

        $groupObj = Group::NotDeleted()->find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        
        $dataObj = new \stdClass();
        $dataObj->id = $groupObj->id;
        $dataObj->title = $groupObj->title;
        $dataObj->permissions = $groupObj->permissions !=null ? unserialize($groupObj->permissions) : [];

        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout','register']);
        $data['data'] = $dataObj;
        return view('Groups.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Input::all();

        $groupObj = Group::NotDeleted()->find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if(Group::checkGroupByTitle($input['title'], $id) != null){
            \Session::flash('error', "هذا الاسم موجود بالفعل");
            return redirect()->back();
        }

        $groupObj->title = $input['title'];

        if(isset($input['permissions'])){
            $groupObj->permissions = serialize($input['permissions']);
        }else{
            $groupObj->permissions = null;
        }

        $groupObj->save();

        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['permissions'] = array_diff(array_unique(config('permissions')), ['general','doLogin','login','logout','register']);
        return view('Groups.Views.add')->with('data',(object) $data);
    }

    public function create() {
        $input = \Input::all();
        
        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if(Group::checkGroupBytitle($input['title']) != null){
            \Session::flash('error', "هذا الاسم موجود من قبل");
            return redirect()->back();
        }
        
        
        $groupObj = new Group();
        $groupObj->title = $input['title'];
        $groupObj->permissions = isset($input['permission']) ? serialize($input['permissions']) : null;
        $groupObj->save();

        \Session::flash('success', "تنبيه! تم اضافة المجموعة");
        return redirect()->to('groups/edit/' . $groupObj->id);
    }

    public function delete($id) {
        $id = (int) $id;
        $groupObj = Group::getOne($id);
        return \Helper::globalDelete($groupObj);
    }
}
