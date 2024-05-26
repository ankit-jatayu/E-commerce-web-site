<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;


class TransportTrips extends Model{
    use SoftDeletes;
    protected $table = 'transport_trips';
    // protected $fillable = ['name'];
    protected $guarded = [];  
    
    
    public function getSelectedVehicle(){
        return $this->hasOne('App\Models\Vehicles', 'id','vehicle_id');
    }
    
    public function getSelectedProduct(){
        return $this->hasOne('App\Models\Products', 'id','product_id');
    }

    public function getSelectedConsignor(){
        return $this->hasOne('App\Models\Parties', 'id','consignor_id');
    }

    public function getSelectedConsignee(){
        return $this->hasOne('App\Models\Parties', 'id','consignee_id');
    }

    public function getSelectedFromStation(){
        return $this->hasOne('App\Models\Locations', 'id','from_station_id');
    }

    public function getSelectedToStation(){
        return $this->hasOne('App\Models\Locations', 'id','to_station_id');
    }
    
    public function getSelectedBackToStation(){
        return $this->hasOne('App\Models\Locations', 'id','back_to_station_id');
    }

    public function getSelectedPayableParty(){
        return $this->hasOne('App\Models\Parties', 'id','payable_party_id');
    }

    public function getSelectedDriver(){
        return $this->hasOne('App\Models\Drivers', 'id','driver_id');
    }

    public function getTripCreatedBy(){
        return $this->hasOne('App\Models\User', 'id','trip_created_by');
    }

    public function getSelectedCompany(){
        return $this->hasOne('App\Models\CompanySettings', 'id','company_id');
    }

    public function getSelectedTransporter(){
        return $this->hasOne('App\Models\Parties', 'id','transporter_id');
    }

    public static  function getPartyAdvanced($trip_id){

         $party_advanced =DB::table('trip_voucher')
                        ->where('trip_voucher.trip_id','=' ,$trip_id)
                        ->where('trip_voucher.is_party_advance','=',1)
                        ->sum('amount'); 

        return $party_advanced;
    }
    
    public function TripBelongsToTripVoucher(){
        return $this->belongsTo('App\Models\TransportTripVouchers','trip_id','id');
    }
        
    // public function getTransporter(){
    //     return $this->hasOne('App\Models\Parties', 'id','transporter_id');
    // }
    
    // public function getRoute(){
    //     return $this->hasOne('App\Models\Routes', 'id','route_id');
    // }

    
    // public function getBillingParty(){
    //     return $this->hasOne('App\Models\Parties', 'id','party_id');
    // }

    

    // public function getDriver(){
    //     return $this->hasOne('App\Models\Drivers', 'id','driver_id');
    // }

   

    // public function getTripUnloadingBy(){
    //     return $this->hasOne('App\Models\User', 'id','trip_unloading_by');
    // }

    // public function getTripEndBy(){
    //     return $this->hasOne('App\Models\User', 'id','trip_end_by');
    // }
    // public function getTripCheckedBy(){
    //     return $this->hasOne('App\Models\User', 'id','trip_check_by');
    // }

    // public function getDetentionAuthorisedBy(){
    //     return $this->hasOne('App\Models\User', 'id','detention_authorised_by');
    // }
    
    // public function getTripExpenseDetail(){
    //     return $this->hasOne('App\Models\TransportTripExpenses', 'trip_id','id');                    
    // }

    // public function getTripExpense(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')->select(DB::raw('sum(total_amount) as total_exp'),'payment_type_id')->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByDriverAdvance(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('sum(total_amount) as total_driver_advance'),'payment_type_id')
    //                     ->where('payment_type_id','=',1)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByDiesel(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('sum(total_amount) as total_deisel_amount'),'payment_type_id')
    //                     ->where('payment_type_id','=',2)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByDieselQty(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(qty + additional_qty) as total_deisel_qty'),'payment_type_id')
    //                     ->where('payment_type_id','=',2)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByAdani(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_adani'),'payment_type_id')
    //                     ->where('payment_type_id','=',3)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByWeighment(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_weighment'),'payment_type_id')
    //                     ->where('payment_type_id','=',5)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByTyre(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_tyre'),'payment_type_id')
    //                     ->where('payment_type_id','=',6)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByLoading(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_loading'),'payment_type_id')
    //                     ->where('payment_type_id','=',7)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByUnloading(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_unloading'),'payment_type_id')
    //                     ->where('payment_type_id','=',8)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByToll(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_toll'),'payment_type_id')
    //                     ->where('payment_type_id','=',9)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByRTO(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_rto'),'payment_type_id')
    //                     ->where('payment_type_id','=',10)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByRepairing(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_repairing'),'payment_type_id')
    //                     ->where('payment_type_id','=',11)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByPolice(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_rto'),'payment_type_id')
    //                     ->where('payment_type_id','=',12)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByBorderChallan(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_border_challan'),'payment_type_id')
    //                     ->where('payment_type_id','=',13)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByUrea(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_urea'),'payment_type_id')
    //                     ->where('payment_type_id','=',14)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripExpenseByFastag(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_fastag'),'payment_type_id')
    //                     ->where('payment_type_id','=',15)
    //                     ->groupBy('trip_id');                    
    // }

    // public function getTripPartyAdvance(){
    //     return $this->hasOne('App\Models\TransportTripVouchers', 'trip_id','id')
    //                     ->select(DB::raw('SUM(total_amount) as total_party_advance'))
    //                     ->where('is_party_advance','=',1)
    //                     ->groupBy('trip_id');                    
    // }

}