<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VehicleDocuments extends Model{
    use SoftDeletes;
    protected $table = 'vehicle_documents';
    protected $fillable = ['vehicle_id','document_name','document_file'];
    
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getUserDetail(){
    //     return $this->hasOne('App\Models\User', 'id','user_id');
    // }
}