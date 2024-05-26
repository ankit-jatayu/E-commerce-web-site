<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverAllocateVehicles extends Model{
    use SoftDeletes;

    protected $table = 'driver_allocated_vehicles';
    protected $fillable = ['driver_id','vehicle_id','from_date','to_date'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getDriverDetail(){
        return $this->hasOne('App\Models\Drivers', 'id','driver_id');
    }

    public function getVehicleDetail(){
        return $this->hasOne('App\Models\Vehicles', 'id','vehicle_id');
    }
}