<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Vehicles extends Model{
    use SoftDeletes;
    protected $table = 'vehicles';
    // protected $fillable = ['registration_no','party_id','vehicle_alias','registration_date','rto_auth','model_code','chassis_no','engine_no','manufacture_year','manufacture_month','purchase_date','purchase_amount','sale_date','sale_amount','gvw_in_kg','ulw_in_kg','vehicle_type','stephanie','type','fuel','f_t_type','f_total_tyre','b_t_type','b_total_tyre','tyre_type','f_size','b_size','equipment_vehicle','vehicle_status','remarks'];
    protected $guarded = [];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getTransporter(){
        return $this->hasOne('App\Models\Parties', 'id','party_id');
    }

    public function getLastestDriver(){
        return $this->hasOne('App\Models\DriverAllocateVehicles', 'vehicle_id','id')
                    ->whereNULL('to_date')
                    ->orderBy('driver_allocated_vehicles.id','DESC');
    }

    public function belongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','vehicle_id','id');
    }
    public function belongsToTripVoucher(){
        return $this->belongsTo('App\Models\TransportTripVouchers','vehicle_id','id');
    }
    
}//func close