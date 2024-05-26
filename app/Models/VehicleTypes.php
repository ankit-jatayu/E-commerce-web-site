<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VehicleTypes extends Model{
    use SoftDeletes;
    protected $table = 'vehicle_types';
    protected $fillable = ['name'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
}