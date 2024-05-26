<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\TransportTrips;
use App\Models\TransportTripVouchers;

use App\Models\CompanySettings;
use App\Models\Vehicles;
use App\Models\Locations;
use App\Models\Products;
use App\Models\Drivers;
use App\Models\Parties;
use App\Models\PartyTypes;
use App\Models\PartySelectedPartyTypes;
use App\Models\VehicleModelCodes;
use App\Models\VehicleTypes;
use App\Models\TransportTripPaymentTypes;


class MobileapiController extends BaseController {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

   function getTripListByStatus(Request $request){
        $login_user_id = $request->input('user_id');
        try{
         
            if($request->status==''){
                return $this->sendError('status field required !!', []); 
            }elseif($request->status!='pending' && $request->status!='completed'){
                return $this->sendError('status should be pending or completed !!', []); 
            }

            DB::beginTransaction();
            $details = TransportTrips::
               with([
                     'getSelectedVehicle' => function ($query) {
                            $query->select('id','registration_no as vehicle_no');
                     },
                     'getSelectedProduct' => function ($query) {
                        $query->select('id','name as product');
                     },
                     'getSelectedConsignor' => function ($query) {
                        $query->select('id','name as consignor');
                     },
                     'getSelectedConsignee' => function ($query) {
                        $query->select('id','name as consignee');
                     },
                     'getSelectedFromStation' => function ($query) {
                        $query->select('id','name as from_station','place_type as state');
                     },
                     'getSelectedToStation' => function ($query) {
                        $query->select('id','name as to_station','place_type as state');
                     },
                     'getSelectedBackToStation' => function ($query) {
                        $query->select('id','name as back_to_station','place_type as state');
                     },
                     'getSelectedPayableParty' => function ($query) {
                        $query->select('id','name as payable_party');
                     },
                     'getSelectedDriver' => function ($query) {
                        $query->select('id','name as driver','contact as driver_mobile_no');
                     },
                     'getTripCreatedBy' => function ($query) {
                        $query->select('id','name as created_by');
                     },
                     'getSelectedCompany' => function ($query) {
                        $query->select('id','company_name as company');
                     },
                     'getSelectedTransporter' => function ($query) {
                        $query->select('id','name as transporter');
                     },
                    ]
                    );
               
                if($request->status=='pending'){
                    $details->whereNull('transport_trips.unload_datetime');
                }elseif($request->status=='completed'){
                    $details->whereNotNull('transport_trips.unload_datetime');
                }
                $details=$details->get();
           
             DB::commit();
            
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
        
    } //func close

    function addTrip(Request $request){
      if($request->all()){
         $login_user_id = auth('sanctum')->user()->id;
         
         try{
            DB::beginTransaction();
               
               $insertTripData=array(
                            'trip_type'          => $request->trip_type,
                            'lr_date'            => $request->lr_date,
                            'lr_no'              => $request->lr_no,
                            'product_id'         => $request->product_id,
                            'consignor_id'       => $request->consignor_id,
                            'consignee_id'       => $request->consignee_id,
                            'from_station_id'    => $request->from_station_id,
                            'to_station_id'      => $request->to_station_id,
                            'back_to_station_id' => $request->back_to_station_id,
                            'gross_weight'       => ($request->gross_weight!='')?$request->gross_weight:0,
                            'tare_weight'        => ($request->tare_weight!='')?$request->tare_weight:0,
                            'net_weight'         => ($request->net_weight!='')?$request->net_weight:0,
                            'payable_by'         => $request->payable_by,
                            'payable_party_id'   => $request->payable_party_id,
                            'freight_rate'       => ($request->freight_rate!='')?$request->freight_rate:0,
                            // 'reporting_datetime' => $request->reporting_datetime,
                            //'unload_datetime'    => $request->unload_datetime,
                            //'unload_weight'      => ($request->unload_weight!='')?$request->unload_weight:0,
                            //'shortage_weight'    => ($request->shortage_weight!='')?$request->shortage_weight:0,
                            'driver_id'          => $request->driver_id,
                            'driver_mobile_no'   => $request->driver_mobile_no,
                            'trip_created_by'    => $login_user_id,
                            'vehicle_avg'        => ($request->vehicle_avg!='')?$request->vehicle_avg:0,
                            'km'                 => ($request->km!='')?$request->km:0,
                            //'detention_days'     => ($request->detention_days!='')?$request->detention_days:0,
                            //'detention'          => ($request->detention!='')?$request->detention:0,
                            'company_id'         => $request->company_id,
                            'invoice_qty'        => ($request->invoice_qty!='')?$request->invoice_qty:0,
                            'remarks'            => $request->remarks,
                        );

                //lr_scan
                $file1 = $request->file('lr_scan');
                if($file1){
                    $fileName = time().'.'.$file1->extension();  //get name
                    $file1->move(public_path('uploads/lr_scan/'), $fileName); //store
                    $insertTripData['lr_scan'] = $fileName;
                    //store new file
                }
                //lr_scan

                if(isset($request->is_market_lr) && $request->is_market_lr == 1){

                      $vehicle_data = Vehicles::where('registration_no',$request->market_vehicle_no)
                                                ->where('party_id',$request->market_transporter_id)
                                                ->first();
                      
                      if(empty($vehicle_data)){
                        $new_vehicle_data = array(
                                                  'registration_no' =>strtoupper($request->market_vehicle_no),
                                                  'party_id'        =>$request->market_transporter_id,
                                                  'type'            =>'market',
                                                  'vehicle_status'  => 'Available'
                                                );
                        $new_vehicle_id = Vehicles::create($new_vehicle_data)->id;
                      }else{
                        $new_vehicle_id = $vehicle_data->id;
                      }
                      
                      $insertTripData['transporter_id'] = $request->market_transporter_id;
                      $insertTripData['vehicle_id'] = $new_vehicle_id;
                      $insertTripData['market_freight'] = ($request->market_freight!='')?$request->market_freight:0;
                      $insertTripData['is_market_lr'] = $request->is_market_lr;
                
                }else{ //is_market_lr==0
                      // $insertTripData['transporter_id'] = NULL;
                      $insertTripData['vehicle_id'] = $request->vehicle_id;
                      $insertTripData['is_market_lr'] = 0;
                }

                $trip_id = TransportTrips::create($insertTripData)->id;
            
            DB::commit();
            if($trip_id){
              return $this->sendResponse([], 'trip created successfully !!');
            }else{
                return $this->sendError('trip not created!!', []);
            } 

         }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
         }
      } //if close

    }//func close

    function addTripVoucher(Request $request){
        if($request->all()){
            $login_user_id = auth('sanctum')->user()->id;
            try{
                DB::beginTransaction();
                    $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
                    $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;

                    $insertData=array(
                        'trip_id'           => $request->trip_id,
                        'is_party_advance'  => isset($request->is_party_advance)?$request->is_party_advance:0,
                        'voucher_no'        => $voucher_no,
                        'branch'            => $request->branch,
                        'voucher_entry_date'=> $request->voucher_entry_date,
                        'voucher_date'      => $request->voucher_date,
                        'vehicle_id'        => $request->vehicle_id,
                        'payment_type_id'   => $request->payment_type_id,
                        'payment_mode'      => $request->payment_mode,
                        'fuel_station_id'   => $request->fuel_station_id,
                        'fuel_qty'          => ($request->fuel_qty != '')?$request->fuel_qty:0,
                        'fuel_rate'         =>($request->fuel_rate != '')?$request->fuel_rate:0,
                        'amount'            => $request->amount,
                        'remarks'            => $request->remarks,
                        'voucher_created_by'=> $login_user_id
                    );

                    $voucher_id = TransportTripVouchers::create($insertData)->id;
          
                DB::commit();
                if($voucher_id){
                  return $this->sendResponse([], 'trip voucher created successfully !!');
                }else{
                    return $this->sendError('trip voucher not created !!', []);
                } 
            }catch(Exception $e) {
                DB::rollback();
                return $this->sendError('something went wrong!!', $e->errorInfo[2]);
            }

        }//if close
    }//func close

    function getCompanies(Request $request){
        try{
            DB::beginTransaction();
            $details = CompanySettings::get(['id','company_name']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getTripTypes(Request $request){
        try{
            DB::beginTransaction();
            $details = [
                         0=>['id'=>'Empty Trip','name'=>'Empty Trip'],
                         1=>['id'=>'Loaded Trip','name'=>'Loaded Trip'],
                         2=>['id'=>'Loaded/Empty Trip','name'=>'Loaded/Empty Trip'],
                       ];
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getVehicles(Request $request){
        try{
            DB::beginTransaction();
            $details = Vehicles::where('type','owner')->get(['id','registration_no as vehicle_no']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getDrivers(Request $request){
        try{
            DB::beginTransaction();
            $details = Drivers::get(['id','name','contact']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getProducts(Request $request){
        try{
            DB::beginTransaction();
            $details = Products::get(['id','name']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getLocations(Request $request){
        try{
            DB::beginTransaction();
            $details = Locations::get(['id','name','place_type as state']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getConsignors(Request $request){
        try{
            DB::beginTransaction();
            $details = Parties::leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                ->where('pspt.party_type_id',1)  //1==Lr Consignor Parties   
                                ->groupBy('party.id')
                                ->get(['party.id','party.name']);    
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getConsignees(Request $request){
        try{
            DB::beginTransaction();
            $details = Parties::leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',2)  //2==Lr Consignee Parties   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);   
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getPayableBy(Request $request){
        try{
            DB::beginTransaction();
            $details = [
                         0=>['id'=>'CONSIGNOR','name'=>'CONSIGNOR'],
                         1=>['id'=>'CONSIGNEE','name'=>'CONSIGNEE'],
                         2=>['id'=>'OTHER','name'=>'OTHER'],
                       ];
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getPayableParties(Request $request){
        try{
            DB::beginTransaction();
            $details = Parties::leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);   
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getTransporters(Request $request){
        try{
            DB::beginTransaction();
            $details = Parties::leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                        ->where('pspt.party_type_id',3) // 3==Transporter Parties   
                                        ->groupBy('party.id')
                                        ->get(['party.id','party.name']);  
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getBranches(Request $request){
        try{
            DB::beginTransaction();
            $details = [
                         0=>['id'=>'Gandhidham','name'=>'Gandhidham'],
                       ];
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getPaymentModes(Request $request){
        try{
            DB::beginTransaction();
            $details = [
                         0=>['id'=>'Cash','name'=>'Cash'],
                         1=>['id'=>'Credit','name'=>'Credit'],
                         2=>['id'=>'Bank','name'=>'Bank'],
                       ];
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    
    }//func close

    function getTripsByVehicle(Request $request){
        try{
            DB::beginTransaction();
            if($request->vehicle_id==''){
               return $this->sendError('vehicle_id required for fetch trips', []);
            }
           
            $details = TransportTrips::with([
                                             'getSelectedFromStation' => function ($query) {
                                                $query->select('id','name as from_station','place_type as state');
                                             },
                                             'getSelectedToStation' => function ($query) {
                                                $query->select('id','name as to_station','place_type as state');
                                             },
                                             'getSelectedBackToStation' => function ($query) {
                                                $query->select('id','name as back_to_station','place_type as state');
                                             },
                                             'getSelectedDriver' => function ($query) {
                                                $query->select('id','name as driver','contact as driver_mobile_no');
                                             },
                                            ]
                                        )
                        ->where('transport_trips.vehicle_id',$request->vehicle_id)
                        //->select('transport_trips.id','transport_trips.lr_no','transport_trips.lr_date')
                        ->orderby('transport_trips.id','desc')->get();
               
                DB::commit();
                if($details){
                  return $this->sendResponse($details, 'data found');
                }else{
                    return $this->sendError('something went wrong!!', []);
                } 
        }catch(Exception $e) {
                DB::rollback();
                return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
    }//func close

    function getPaymentTypes(Request $request){
        try{
            DB::beginTransaction();
            $details = TransportTripPaymentTypes::get(['id','name']);
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
              return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
                DB::rollback();
                return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }

    }//func close

    function getFuelStations(Request $request){
        try{
            DB::beginTransaction();
            $details = Parties::leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                ->where('pspt.party_type_id',5)  //5==Fuel Station   
                                ->groupBy('party.id')
                                ->get(['party.id','party.name']);   
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
              return $this->sendError('something went wrong!!', []);
            } 
        }catch(Exception $e) {
                DB::rollback();
                return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
        
    }//func close

    function getTripVouchers(Request $request){
        try{
            DB::beginTransaction();
                $details = TransportTripVouchers::
                                    select('trip_voucher.*')
                                    ->with([
                                        'getSelectedTripVoucherCreatedBy' => function ($query) {
                                            $query->select('id','name as voucher_created_by');
                                        },
                                        'getSelectedTransportTrip' => function ($query) {
                                            $query->select('id','lr_no','lr_date');
                                        },
                                        'getSelectedVehicle' => function ($query) {
                                            $query->select('id','registration_no as vehicle_no');
                                        },
                                        'getSelectedPaymentType' => function ($query) {
                                            $query->select('id','name as payment_type');
                                        },
                                        'getSelectedFuelStation' => function ($query) {
                                            $query->select('id','name as fuel_station');
                                        },
                                    ]
                                 );
                $details=$details->get();
            DB::commit();
            if($details){
              return $this->sendResponse($details, 'data found');
            }else{
                return $this->sendError('something went wrong!!', []);
            }
        }catch(Exception $e) {
            DB::rollback();
            return $this->sendError('something went wrong!!', $e->errorInfo[2]);
        }
        
    }

    //old
    // function followTryCache(){
    //   try{
    //         DB::beginTransaction();
    //             $details = TransportTrips::whereNull('unload_datetime')->get();
    //         DB::commit();
    //         if($details){
    //           return $this->sendResponse($details, 'data found');
    //         }else{
    //             return $this->sendError('something went wrong!!', []);
    //         } 
    //     }catch(Exception $e) {
    //         DB::rollback();
    //         return $this->sendError('something went wrong!!', $e->errorInfo[2]);
    //     }
        


    //     public function DriverBelongsToManyTrip(){
    //     return $this->belongsToMany('App\Models\TransportTrips','driver_id','id');
    //   }
    // }

} //class close
