<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RouteRates extends Model{
    use SoftDeletes;
    protected $table = 'route_rates';
    protected $guarded = [];

    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    public function getSelectedParty(){
        return $this->hasOne('App\Models\Parties', 'id','party_id');
    }
    public function getSelectedRoute(){
        return $this->hasOne('App\Models\Routes', 'id','route_id');
    }

}