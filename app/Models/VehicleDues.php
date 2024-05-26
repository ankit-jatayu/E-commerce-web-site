<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VehicleDues extends Model{
    use SoftDeletes;
    protected $table = 'vehicle_dues';
    protected $fillable = ['vehicle_id','due_id','validity'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
    
    public function getVehicleDetail(){
        return $this->hasOne('App\Models\Vehicles', 'id','vehicle_id');
    }
    public function getDueDetail(){
        return $this->hasOne('App\Models\Dues', 'id','due_id');
    }
}