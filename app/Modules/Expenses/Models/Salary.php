<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class Salary extends Model{

    use \TraitsFunc;

    protected $table = 'salaries';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    static function dataList() {
        $input = \Input::all();
        $source = self::NotDeleted();
        if (isset($input['status']) && $input['status'] != 0) {
            $source->where('status', $input['status']);
        }
        if (isset($input['user_id']) && $input['user_id'] != 0) {
            $source->where('user_id', $input['user_id']);
        }
         if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('start_date','>=',$input['from'])->where('end_date','<=',$input['to']);
        }
        if (isset($input['shop_id']) && $input['shop_id'] != 0) {
            $source->whereHas('User',function($userQuery) use ($input){
                $userQuery->whereHas('Profile',function($profileQuery) use ($input){
                    $profileQuery->where('shop_id',$input['shop_id']);
                });
            });
        }
        if(\Session::get('group_id') != 1){
            $source->whereHas('User',function($userQuery) use ($input){
                $userQuery->whereHas('Profile',function($profileQuery) use ($input){
                    $profileQuery->where('shop_id',\Session::get('shop_id'));
                });
            });
        }

        if (isset($input['paid_date']) && !empty($input['paid_date'])) {
            $source->where('paid_date','LIKE' ,$input['paid_date'].'%');
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->paginate(PAGINATION);

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getType($type){
        $text = '';
        if($type == 1){
            $text = 'لم تتم التصفية';
        }elseif($type == 2){
            $text= 'تمت التصفية';
        }

        return $text;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->user_id = $source->user_id;
        $data->user_name = $source->User->name;
        $data->shop_name = $source->User->Profile->Shop->title;
        $data->salary = $source->User->Profile->salary;
        $data->start_date = $source->start_date;
        $data->end_date = $source->end_date;
        $data->took = Expense::where('type',2)->where('user_id',$source->user_id)->where('created_at','>=',$source->start_date.' 00:00:00')->where('created_at','<=',$source->end_date.' 23:59:59')->sum('total');
        $data->paid = $source->paid;
        $data->rest = $source->paid != 0 ? $source->paid : $data->salary - $data->took;
        $data->paid_date = $source->paid_date;
        $data->status = self::getType($source->status);
        $data->status_id = $source->status;
        $data->created_at = $source->created_at;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }
}
