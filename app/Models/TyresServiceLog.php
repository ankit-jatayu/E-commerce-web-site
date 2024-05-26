<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TyresServiceLog extends Model{
    use SoftDeletes;
    protected $table = 'tyre_service_log';
    protected $guarded = [];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/
     public function getModelsTyres(){
        return $this->hasOne('App\Models\Tyres','id','tyre_id');
    }
    public function getModelsVehicles(){
        return $this->hasOne('App\Models\Vehicles','id','vehicle_id');
    }
    public function getModelsTyresServiceType(){
        return $this->hasOne('App\Models\TyresServiceType','id','tyre_service_type_id');
    }

    // public function getModels(){
    //     return $this->hasOne('App\Models\Models', 'id','model_id');
    // }
}