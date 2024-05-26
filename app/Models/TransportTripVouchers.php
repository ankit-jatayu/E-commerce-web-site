<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportTripVouchers extends Model{
    use SoftDeletes;
    protected $table = 'trip_voucher';
    protected $guarded = [];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getSelectedTripVoucherCreatedBy(){
        return $this->hasOne('App\Models\User', 'id','voucher_created_by');
    }

    public function getSelectedTransportTrip(){
        return $this->hasOne('App\Models\TransportTrips', 'id','trip_id');
    }
    
    public function getSelectedVehicle(){
        return $this->hasOne('App\Models\Vehicles', 'id','vehicle_id');
    }

    public function getSelectedPaymentType(){
        return $this->hasOne('App\Models\TransportTripPaymentTypes', 'id','payment_type_id');
    }

    // public function getSelectedVoucherCheckedByUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','voucher_check_by');
    // }

    function getSelectedFuelStation(){
        return $this->hasOne('App\Models\Parties', 'id','fuel_station_id');
    }
    
}