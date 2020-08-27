<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class Expense extends Model{

    use \TraitsFunc;

    protected $table = 'expense';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    function Creator(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency','currency_id');
    }

    static function userExpenses($count){
        $source = self::NotDeleted()->where('type',2)->where('user_id',USER_ID)->orderBy('id','DESC')->take($count);
        return self::generateObj($source,'paginate');
    }

    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['type']) && $input['type'] != 0) {
            $source->where('type', $input['type']);
        }
        if (isset($input['user_id']) && $input['user_id'] != 0) {
            $source->where('user_id', $input['user_id']);
        }
        if (isset($input['shop_id']) && $input['shop_id'] != 0) {
            $source->where('shop_id', $input['shop_id']);
        }
        if (isset($input['created_at']) && !empty($input['created_at'])) {
            $source->where('created_at','LIKE' ,$input['created_at'].'%');
        }
        if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
            $source->where('created_at','>=',$input['from'].' 00:00:00')->where('created_at','<=',$input['to'].' 23:59:59');
        }

        if(!IS_ADMIN){
            $source->where('shop_id',\Session::get('shop_id'));
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$withPaginate);
    }

    static function generateObj($source,$withPaginate=null){
        if($withPaginate != null){
            $sourceArr = $source->get();
        }else{
            $sourceArr = $source->paginate(PAGINATION);
        }

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }
        $data['total'] = $source->sum('total');
        if($withPaginate == null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
            $data['data'] = $list;
            return $data;
        }else{
            return (object) $list;
        }

        return $data;
    }

    static function getType($type){
        $text = '';
        if($type == 1){
            $text = 'مصروف';
        }elseif($type == 2){
            $text= 'سلفة لعامل';
        }elseif($type == 3){
            $text = 'مصاريف انتقالات';
        }

        return $text;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->type = $source->type;
        $data->type_name = self::getType($source->type) ;
        $data->total = $source->total;
        $data->description = $source->description;
        $data->currency_id = $source->currency_id;
        $data->currency_name = $source->Currency->name;
        $data->user_id = $source->user_id;
        $data->user_name = $source->user_id  ? User::find($source->user_id)->name : '-----';
        $data->shop_id = $source->shop_id;
        $data->shop_name = $source->shop_id ? Shop::find($source->shop_id)->title : '-----';
        $data->user_image = $source->user_id ? User::getData($source->User)->image : '-----';
        $data->created_at = $source->created_at;
        $data->creator = $source->Creator->name;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }
}
