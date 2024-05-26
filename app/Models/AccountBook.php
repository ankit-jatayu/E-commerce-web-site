<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;


class AccountBook extends Model{
    use SoftDeletes;
    protected $table = 'account_book';
    protected $guarded = [];
    
    public function getSelectedParty(){
        return $this->hasOne('App\Models\Parties', 'id','party_id');
    }

    public function getSelectedAccountType(){
        return $this->hasOne('App\Models\AccountType', 'id','account_type_id');
    }
    public function getSelectedTransactionHead(){
        return $this->hasOne('App\Models\TransactionHead', 'id','head_type_id');
    }

    public function getCreatedBy(){
        return $this->hasOne('App\Models\User', 'id','created_by');
    }

}