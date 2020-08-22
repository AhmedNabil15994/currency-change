<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model{

    use \TraitsFunc;

    protected $table = 'delegate_commission';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Delegate(){
        return $this->belongsTo('\App\Models\Delegate','delegate_id');
    }

    static function dataList() {
        $input = \Input::all();

        $source = self::NotDeleted();
        if (isset($input['commission']) && $input['commission'] != 0) {
            $source->where('commission', $input['commission']);
        }
        if (isset($input['valid_until']) && $input['valid_until'] != 0) {
            $source->where('valid_until', $input['valid_until']);
        }
        if (isset($input['delegate_id']) && !empty($input['delegate_id'])) {
            $source->where('delegate_id', $input['delegate_id']);
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

    static function checkDelegate($delegate_id,$valid_until, $notId = false){
        $dataObj = self::NotDeleted()->where('delegate_id',$delegate_id)->where('valid_until','>',$valid_until)->where('is_active',1);
            
        if ($notId != false) {
            $notId = (array) $notId;
            $dataObj->whereNotIn('id', $notId);
        }

        return $dataObj->first();
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->delegate_id = $source->delegate_id;
        $data->delegate_name = ucwords($source->Delegate->name);
        $data->valid_until = $source->valid_until;
        $data->commission = $source->commission;
        $data->active = $source->is_active == 1 ? "مفعل" : "غير مفعل";
        $data->is_active = $source->is_active;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }
}
