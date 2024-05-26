<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartySelectedPartyTypes extends Model {
    use SoftDeletes;

    protected $table = 'party_selected_party_types';
    protected $guarded = [];
        
    // protected $fillable = ['name'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getSinglePartyType(){
        return $this->hasOne('App\Models\PartyTypes', 'id','party_type_id');
    }
    
}