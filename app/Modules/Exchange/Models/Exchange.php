<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model{

    use \TraitsFunc;

    protected $table = 'exchanges';
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
        if (isset($input['type']) && !empty($input['type'])) {
            $source->where('type', $input['type']);
        }
        if (isset($input['client_id']) && !empty($input['client_id'])) {
            $source->where('type',1)->where('user_id', $input['client_id']);
        }
        if (isset($input['delegate_id']) && !empty($input['delegate_id'])) {
            $source->where('type',2)->where('user_id', $input['delegate_id']);
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
        $myType = '';
        $user_name = '';
        if($source->type == 1){
            $myType = 'عميل';
            $user_name = Client::find($source->user_id)->name;
        }else{
            $myType = 'مندوب';
            $user_name = Delegate::find($source->user_id)->name;
        }

        $data->id = $source->id;
        $data->type = $source->type;
        $data->type_text = $myType;
        $data->user_name = $user_name;
        $data->user_id = $source->user_id;
        $data->from_id = $source->from_id;
        $data->from = $source->FromCurrency;
        $data->details_id = $source->details_id;
        $data->to_id = $source->to_id;
        $data->to = $source->ToCurrency;
        $data->convert_price = $source->convert_price;
        $data->amount = $source->amount;
        $data->paid = $source->paid;
        $data->created_at = $source->created_at;
        return $data;
    }

    static function getOne($id) {
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

}
