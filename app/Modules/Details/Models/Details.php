<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Details extends Model{

    use \TraitsFunc;

    protected $table = 'currency_details';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function FromCurrency(){
        return $this->belongsTo('\App\Models\Currency','from_id');
    }

    public function ToCurrency(){
        return $this->belongsTo('\App\Models\Currency','to_id');
    }

    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['from_id']) && !empty($input['from_id'])) {
            $source->where('from_id', $input['from_id']);
        }
        if (isset($input['to_id']) && !empty($input['to_id'])) {
            $source->where('to_id', $input['to_id']);
        }
        if (isset($input['is_active']) && $input['is_active'] != -1) {
            $source->where('is_active', $input['is_active']);
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
        if($withPaginate==null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;
        return $data;
    }


    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->from_id = $source->from_id;
        $data->from = $source->FromCurrency;
        $data->to_id = $source->to_id;
        $data->to = $source->ToCurrency;
        $data->type = $source->type;
        $data->rate = $source->rate;
        $data->type_name = 'مبلغ';
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function getOneConvertion($from_id,$to_id){
        return self::NotDeleted()->where('from_id',$from_id)->where('to_id',$to_id)->where('is_active',1)->first();
    }

    static function checkRecord($from_id,$to_id, $notId = false){
        $dataObj = self::NotDeleted()->where('from_id',$from_id)->where('to_id',$to_id)->where('is_active',1);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

}
