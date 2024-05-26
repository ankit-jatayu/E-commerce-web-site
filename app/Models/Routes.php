<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Routes extends Model{
    use SoftDeletes;
    protected $table = 'routes';
    protected $guarded = [];

    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
}