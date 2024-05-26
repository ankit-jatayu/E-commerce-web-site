<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes; 

use Illuminate\Database\Eloquent\Model;
    

Use DB;


class Products extends Model{
    use SoftDeletes; 
    protected $table = 'products';
    protected $guarded = [];
    
    // public function getSelectedParty(){
    //     return $this->hasOne('App\Models\Parties', 'id','party_id');
    // }

    //  public function getSelectedCompany(){
    //     return $this->hasOne('App\Models\CompanySettings', 'id','company_id');
    // }

    // public function getCreatedBy(){
    //     return $this->hasOne('App\Models\User', 'id','created_by');
    // }

    public function belongsToTrip(){
        return $this->belongsTo('App\Models\TransportTrips','product_id','id');
    }

}