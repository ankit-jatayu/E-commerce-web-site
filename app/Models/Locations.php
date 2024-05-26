<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Locations extends Model{
    use SoftDeletes;
    protected $table = 'places';
    protected $guarded = [];

    // protected $fillable = ['name','place_type'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
    
    public function getSelectedState(){
        return $this->hasOne('App\Models\States', 'name','place_type');
    }

    public function FromStationBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','from_station_id','id');
    }
    
    public function ToStationBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','to_station_id','id');
    }

    public function BackToStationBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','back_to_station_id','id');
    }

} //class close