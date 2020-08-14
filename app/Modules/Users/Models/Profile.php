<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model{

    use \TraitsFunc;

    protected $table = 'profiles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Group(){
        return $this->belongsTo('App\Models\Group', 'group_id');
    }

    function Currency(){
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    function Shop(){
        return $this->belongsTo('App\Models\Shop', 'shop_id');
    }

}
