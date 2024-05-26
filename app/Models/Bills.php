<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;


class Bills extends Model{
    use SoftDeletes;
    protected $table = 'bills';
    protected $guarded = [];
    
    public function getSelectedParty(){
        return $this->hasOne('App\Models\Parties', 'id','party_id');
    }

     public function getSelectedCompany(){
        return $this->hasOne('App\Models\CompanySettings', 'id','company_id');
    }

    public function getCreatedBy(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }

}