<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TyreAssigns extends Model{
    use SoftDeletes;
    protected $table = 'tyre_assigns';
    protected $guarded = [];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/
     public function getModelsTyres(){
        return $this->hasOne('App\Models\Tyres','id','tyre_id');
    }
    public function getModelsPositionVehicle(){
        return $this->hasOne('App\Models\PositionVehicle','id','position_vehicle_id');
    }
    public function getModelsVehicles(){
        return $this->hasOne('App\Models\Vehicles','id','vehicle_id');
    }
}