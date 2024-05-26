<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tyres extends Model{
    use SoftDeletes;
    protected $table = 'tyres';
    protected $guarded = [];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/
    public function getSelectedTyreBrands(){
        return $this->hasOne('App\Models\TyreBrands','id','tyre_brand_id');
    }
    
    public function getSelectedTyreAssigns(){
        return $this->hasOne('App\Models\TyreAssigns','tyre_id','id');
    }

    // public function getSeletedPositionVehicle(){
    //     return $this->hasOne('App\Models\PositionVehicle', 'id','fleet_type_id');
    // }
    // public function getModels(){
    //     return $this->hasOne('App\Models\Models', 'id','model_id');
    // }
}