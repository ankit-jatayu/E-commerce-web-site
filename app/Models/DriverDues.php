<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DriverDues extends Model{
    use SoftDeletes;
    protected $table = 'driver_dues';
    protected $fillable = ['driver_id','due_name','validity'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getDriverDetail(){
        return $this->hasOne('App\Models\Drivers', 'id','driver_id');
    }
}