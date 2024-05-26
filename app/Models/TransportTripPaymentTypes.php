<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TransportTripPaymentTypes extends Model{
    use SoftDeletes;
    protected $table = 'trip_payment_types';
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getModels(){
    //     return $this->hasOne('App\Models\Models', 'id','model_id');
    // }

    public function PaymentTypeBelongsToTripVoucher(){
        return $this->belongsTo('App\Models\TransportTripVouchers','payment_type_id','id');
    }

}