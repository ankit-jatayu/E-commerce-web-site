<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;


class SalaryVouchers extends Model{
    use SoftDeletes;
    protected $table = 'salary_voucher';
    // protected $fillable = ['name'];
    protected $guarded = [];
    
    public function getVehicleDetail(){
        return $this->hasOne('App\Models\Vehicles', 'id','vehicle_id');
    }

    public function getDriverDetail(){
        return $this->hasOne('App\Models\Drivers', 'id','driver_id');
    }
    public function getCreatedBy(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }

}