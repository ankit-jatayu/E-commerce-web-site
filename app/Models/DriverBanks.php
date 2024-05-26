<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DriverBanks extends Model{
    use SoftDeletes;
    protected $table = 'driver_banks';
    protected $fillable = ['driver_id','bank_name','ac_no','state','place','ifsc','micr'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
}