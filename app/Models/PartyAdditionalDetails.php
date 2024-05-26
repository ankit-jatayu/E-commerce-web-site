<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PartyAdditionalDetails extends Model{
    use SoftDeletes;
    protected $table = 'party_additional_detail';
    protected $guarded = [];
    
    // protected $fillable = ['name'];
    
    // public function getPartyAdditionalDetail(){
    //     return $this->hasMany('App\Models\PartyAdditionalDetails', 'party_id','id');
    // }

    // public function getCreatedByDetail(){
    //     return $this->hasOne('App\Models\User', 'id','created_by');
    // }
}