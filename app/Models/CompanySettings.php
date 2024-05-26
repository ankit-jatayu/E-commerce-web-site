<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;


class CompanySettings extends Model{
    use SoftDeletes;
    protected $table = 'company_settings';
    protected $guarded = [];
    
    // public function getSelectedParty(){
    //     return $this->hasOne('App\Models\Parties', 'id','party_id');
    // }

    // public function getCreatedBy(){
    //     return $this->hasOne('App\Models\User', 'id','created_by');
    // }

    public function CompanyBelongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','company_id','id');
    }

}