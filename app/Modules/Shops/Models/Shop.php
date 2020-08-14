<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model{

    use \TraitsFunc;

    protected $table = 'shops';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function SalesInvoice(){
        return $this->hasMany('App\Models\SalesInvoice', 'shop_id');
    }

    static function getPhotoPath($id, $photo) {
        return \ImagesHelper::GetImagePath('shops', $id, $photo);
    }

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)->first();
    }


    static function dataList($withPaginate=null) {
        $input = \Input::all();

        $source = self::NotDeleted();

        if (isset($input['title']) && !empty($input['title'])) {
            $source->where('title', 'LIKE', '%' . $input['title'] . '%');
        } 
        if (isset($input['address']) && !empty($input['address'])) {
            $source->where('address', 'LIKE', '%' . $input['address'] . '%');
        } 
        if (isset($input['phone']) && !empty($input['phone'])) {
            $source->where('phone', $input['phone']);
        } 

        if(\Session::get('group_id') != 1){
            $source->where('id',\Session::get('shop_id'));
        }

        $source->orderBy('id','DESC');
        return self::generateObj($source,$withPaginate);
    }

    static function generateObj($source,$withPaginate){
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
        if($withPaginate == null){
            $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        }
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->title = $source->title;
        $data->phone = $source->phone;
        $data->address = $source->address;
        $data->image = self::getPhotoPath($source->id, $source->image);
        $data->created_at = \Helper::formatDateForDisplay($source->created_at);
        return $data;
    }
}
