<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Parties extends Model{
    use SoftDeletes;
    protected $table = 'party';
    protected $guarded = [];
    
    // protected $fillable = ['name'];
    
    public function getPartyAdditionalDetails(){
        return $this->hasMany('App\Models\PartyAdditionalDetails', 'party_id','id');
    }
    public function getPartyDocuments(){
        return $this->hasMany('App\Models\PartyDocuments', 'party_id','id');
    }

    public function getCreatedByDetail(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }

    public function getSelectedPartyTypes(){
        return $this->hasMany('App\Models\PartySelectedPartyTypes', 'party_id','id');
    }

    public function consignorBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','consignor_id','id');
    }

    public function consigneeBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','consignee_id','id');
    }

    public function payablePartyBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','payable_party_id','id');
    }

    public function transporterBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','transporter_id','id');
    }

    public function fuelStationBelongsToTripVoucher(){
        return $this->belongsTo('App\Models\TransportTripVouchers','fuel_station_id','id');
    }
    
}