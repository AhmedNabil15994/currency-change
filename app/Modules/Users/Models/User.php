<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class User extends Model{

    use \TraitsFunc;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Profile(){
        return $this->hasOne('App\Models\Profile', 'user_id');
    }


    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('users', $id, $photo);
    }

    static function usersList() {
        $input = \Input::all();

        $source = self::NotDeleted()->orderBy('last_login', 'desc')->with('Profile')
            ->whereHas('Profile', function($queryProfile) use ($input) {
                if (isset($input['name']) && !empty($input['name'])) {
                    $queryProfile->where('first_name', 'LIKE', '%' . $input['name'] . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $input['name'] . '%');
                }
                if (isset($input['group_id']) && $input['group_id'] != 0) {
                    $queryProfile->where('group_id', $input['group_id']);
                }
                if (isset($input['shop_id']) && $input['shop_id'] != 0) {
                    $queryProfile->where('shop_id', $input['shop_id']);
                }
                if (isset($input['phone']) && $input['phone'] != 0) {
                    $queryProfile->where('phone', $input['phone']);
                }
                if (isset($input['start_date']) && $input['start_date'] != 0) {
                    $queryProfile->where('start_date', $input['start_date']);
                }
                if(\Session::get('group_id') != 1){
                    $queryProfile->where('shop_id',\Session::get('shop_id'));
                }

            });

        if (isset($input['email']) && !empty($input['email'])) {
            $source->where('email', 'LIKE', '%' . $input['email'] . '%');
        }

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['groups'] = Group::getList();
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function selectImage($source){
        
        if($source->Profile->image != null){
            return self::getPhotoPath($source->id, $source->Profile->image);
        }else{
            return asset('/assets/images/avatar.png');
        }
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->name = $source->Profile != null ? ucwords($source->Profile->display_name) : '';
        $data->first_name = $source->Profile != null ? $source->Profile->first_name : '';
        $data->last_name = $source->Profile != null ? $source->Profile->last_name : '';
        $data->image = self::selectImage($source);
        $data->group = $source->Profile->Group != null ? $source->Profile->Group->title : '';
        $data->gender = $source->Profile != null ? $source->Profile->gender : '';
        $data->group_id = $source->Profile->group_id;
        $data->shop_id = $source->Profile->shop_id;
        $data->shop = $source->Profile->shop_id != null ? $source->Profile->Shop->title : '';
        $data->phone = $source->Profile != null ? $source->Profile->phone: '';
        $data->salary = $source->Profile != null ? $source->Profile->salary: 0;
        $data->start_date = $source->Profile != null ? $source->Profile->start_date: '';
        $data->address = $source->Profile != null ? $source->Profile->address: '';
        $data->email = $source->email;
        $data->currency_id = $source->Profile->currency_id;
        $data->currency_name = $source->Profile->currency_id > 0  ? $source->Profile->Currency->name : '';
        $data->last_login = \Helper::formatDateForDisplay($source->last_login, true);
        $data->extra_rules = unserialize($source->Profile->extra_rules) != null || unserialize($source->Profile->extra_rules) != '' ? unserialize($source->Profile->extra_rules) : [];
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        $data->deleted_by = $source->deleted_by;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->with('Profile')
            ->where('id', $id)
            ->first();
    }

    static function getLoginUser($email){
        $userObj = self::NotDeleted()
            ->with('Profile')
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if($userObj == null) {
            return false;
        }
        return $userObj;
    }

    static function getUserByType($type,$shop_id=null){
        $userObj = self::NotDeleted()->whereHas('Profile',function($profileQuery) use ($type,$shop_id){
            $profileQuery->where('group_id',$type);
            if(\Session::get('group_id') != 1){
                $profileQuery->where('shop_id',\Session::get('shop_id'));
            }
            if($shop_id != null){
                $profileQuery->where('shop_id',$shop_id);
            }
        })->where('is_active',1)->get();

        return $userObj;
    }

    static function checkUserPermissions($userObj) {
        $endPermissionUser = [];
        $endPermissionGroup = [];

        $groupObj = $userObj->Profile->Group;
        $profileObj = $userObj->Profile;
        $groupPermissions = $groupObj != null ? $groupObj->permissions : null;

        $userPermissionValue = unserialize($profileObj->extra_rules);
        $groupPermissionValue = unserialize($groupPermissions);

        if($userPermissionValue != false){
            $endPermissionUser = $userPermissionValue;
        }

        if($groupPermissionValue != false){
            $endPermissionGroup = $groupPermissionValue;
        }

        $permissions = (array) array_unique(array_merge($endPermissionUser, $endPermissionGroup));

        return $permissions;
    }

    static function userPermission(array $rule){

        if(IS_ADMIN == false) {
            return count(array_intersect($rule, PERMISSIONS)) > 0;
        }

        return true;
    }

    static function checkUserByEmail($email, $notId = false){
        $dataObj = self::NotDeleted()
            ->where('email', $email);

        if ($notId != false) {
            $dataObj->whereNotIn('id', [$notId]);
        }

        return $dataObj->first();
    }

    static function checkUserByPhone($phone, $notId = false){
        $dataObj = self::NotDeleted()
            ->whereHas('Profile', function($profileQuery) use($phone) {
                $profileQuery->where('phone','!=',null)->where('phone',$phone);
            });

        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function createOneUser($group_id=null){
        $input = \Input::all();

        $userObj = new User();
        $userObj->email = $input['email'];
        $userObj->name = $input['first_name'].' '.$input['last_name'];
        $userObj->is_active = isset($input['active']) ? 1 : 0;
        $userObj->password = \Hash::make($input['password']);
        $userObj->save();

        self::saveProfile($userObj,$group_id);
        return $userObj->id;
    }

    static function saveProfile($userObj,$group_id=null) {
        $input = \Input::all();

        $profileObj = $userObj->Profile;

        if($profileObj == null){
            $profileObj = new Profile();
        }
        if(isset($input['permissions'])){
            $profileObj->extra_rules = serialize($input['permissions']);
        }
        $profileObj->user_id = $userObj->id;
        $profileObj->first_name = $input['first_name'];
        $profileObj->last_name = $input['last_name'];
        $profileObj->phone = $input['phone'];
        $profileObj->currency_id = $input['currency_id'];
        $profileObj->address = isset($input['address']) && !empty($input['address']) ? $input['address'] : '' ;
        $profileObj->salary = isset($input['salary']) && !empty($input['salary']) ? $input['salary'] : 0 ;
        $profileObj->start_date = isset($input['start_date']) && !empty($input['start_date']) ? $input['start_date'] : null ;
        $profileObj->shop_id = isset($input['shop_id']) && !empty($input['shop_id']) ? $input['shop_id'] : (\Session::get('group_id') != 1 ? \Session::get('shop_id') : null) ;
        $profileObj->display_name = $input['first_name'].' '.$input['last_name'];
        $profileObj->group_id = $group_id != null ? $group_id : $input['group_id'];
        $profileObj->save();
    }

}
