<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Drivers extends Model{
    use SoftDeletes;
    protected $table = 'drivers';
    protected $fillable = ['name','app_date','contact','driver_pic','home_contact','driver_status','local_address','permanent_address','adani_getpass'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getLastestVehicle(){
        return $this->hasOne('App\Models\DriverAllocateVehicles', 'driver_id','id')
                    ->whereNULL('to_date')
                    ->orderBy('driver_allocated_vehicles.id','DESC');
    }
    public function getDriverBank(){
        return $this->hasOne('App\Models\DriverBanks', 'driver_id','id');
    }

    public function DriverBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','driver_id','id');
    }
}