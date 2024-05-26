<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TyresServiceType extends Model{
    use SoftDeletes;
    protected $table = 'tyre_service_type';
    protected $guarded = [];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getModels(){
    //     return $this->hasOne('App\Models\Models', 'id','model_id');
    // }
}