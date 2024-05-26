<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VehicleRepairs extends Model{
    use SoftDeletes;
    protected $table = 'vehicle_repairs';
    protected $guarded = [];

    // protected $fillable = ['vehicle_id','vehicle_id','start_date'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getFollowupByDetail(){
        return $this->hasOne('App\Models\User', 'id','followup_by');
    }
}