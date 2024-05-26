<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Units extends Model{
    use SoftDeletes;
    protected $table = 'units';
    protected $fillable = ['name'];
    /*public function getCustomerEducations(){
        return $this->hasMany('App\Models\CustomerEducation', 'customer_id');
    }*/

    // public function getModels(){
    //     return $this->hasOne('App\Models\Models', 'id','model_id');
    // }
}