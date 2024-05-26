<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DriverGuarantors extends Model{
    use SoftDeletes;
    protected $table = 'driver_guarantors';
    protected $fillable = ['driver_id','guarentor1','guarentor1_phone_no','guarentor1_address','guarentor2','guarentor2_phone_no','guarentor2_address'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
}