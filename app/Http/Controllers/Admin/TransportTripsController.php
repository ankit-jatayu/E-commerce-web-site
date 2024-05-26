<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use DataTables;
use Storage;

use App\Models\Vehicles;
use App\Models\Locations;
use App\Models\Products;
use App\Models\Drivers;
use App\Models\TransportTrips;
use App\Models\Parties;
use App\Models\PartyTypes;
use App\Models\CompanySettings;
use App\Models\PartySelectedPartyTypes;
use App\Models\VehicleModelCodes;
use App\Models\VehicleTypes;
use App\Models\UserProjectModules;


class TransportTripsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index(){
        $viewData['title'] = 'ALL TRIPS';
        
        // $viewData['parties']=Parties::where('party.ledger_type_id',4)->get();
        // $viewData['transporter']=Parties::where('party.ledger_type_id',2)->get();
        // $viewData['vehicles']=Vehicles::get();
        // $viewData['routes']=Routes::get();
          
        // $viewData['market_trip']= (isset($_GET['is_market_lr']))?$_GET['is_market_lr']:''; 
        // $viewData['bill_panding']= (isset($_GET['bill_id']))?$_GET['bill_id']:''; 

        //$viewData['authories_list']=User::where('is_authorised',1)->get();
        return view('admin.transport_trips.transport_trip_list',$viewData);
    }

    function TransportTripPaginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "transport_trips.id !='0' ";

            $login_user_role = Auth::user()->role_id;
            // if($login_user_role == 12){
            //     $login_user_party = Auth::user()->party_id;
            //     $all_vehicles = Vehicles::where('party_id' ,'=' ,$login_user_party)->pluck('id')->toArray();

            //     $wherestr .= " AND transport_trips.vehicle_id  IN  (".implode(",",$all_vehicles).") ";
            // }

            if($request->lr_no!= ''){
                $wherestr .= " AND transport_trips.lr_no =  '".$request->lr_no."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
            }

            // if($request->party_id!= ''){
            //     $wherestr .= " AND transport_trips.transporter_id =  '".$request->party_id."'";
            // }

            // if($request->vehicle_id!= ''){
            //     $wherestr .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
            // }

            // if($request->route_id!= ''){
            //     $wherestr .= " AND transport_trips.route_id =  '".$request->route_id."'";
            // }

            
            // if($request->is_market_lr!= ''){
            //     $wherestr .= " AND transport_trips.is_market_lr =  '".$request->is_market_lr."'";
            // }

            //   if($request->bill_id!= ''){
            //     $wherestr .= " AND transport_trips.bill_id =  '".$request->bill_id."'";
            // }

            
            $data=TransportTrips::whereRaw($wherestr)->orderby('id','desc');
            $user_id = Auth::user()->id;
            $all_access_rights = UserProjectModules::where(['user_id' => $user_id])->get();
            $TripModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==5;})
                                                  ->first();

            return Datatables::of($data,$TripModuleRights)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use($TripModuleRights) { 
                            $btn ='';
                            if(isset($TripModuleRights) && $TripModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<a href="'.route('transport.trip.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm ml-1"><i class="feather icon-edit"></i></a>';
                            }
                           
                            $btn .= '&nbsp;&nbsp;<a class="edit btn btn-warning btn-sm ml-1" onclick="printLr('.$row->id.')"><i class="feather icon-printer" style="color: white;"></i></a>';

                            if(isset($TripModuleRights) && $TripModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }

                           return $btn;
                    })
                  
                    // ->addColumn('trip_detail', function($row) {
                    //     $btn = '';
                    //     if($row['trip_created_by'] > 0){
                    //         $btn .= '<span> Created By  : '.$row->getTripCreatedBy->name.'</span><br>';
                    //     }
                    //     if($row->is_loading=='Pending'){
                    //         $btn .= '<span> Loading Status  : <label class="label label-danger">'.$row->is_loading.'</label></span><br>';
                    //     }else{
                    //         $btn .= '<span> Loading Status  : <label class="label label-success">'.$row->is_loading.'</label></span><br>';
                    //     }

                    //     return $btn;
                    // })
                    ->addColumn('lr_date', function($row) {
                        return isset($row->lr_date)?helperConvertYmdTodmY($row->lr_date):'';
                    
                    })->addColumn('vehicle_id', function($row) {
                        return ($row->getSelectedVehicle)?$row->getSelectedVehicle->registration_no:'';
                    
                    })->addColumn('product_id', function($row) {
                        return ($row->getSelectedProduct)?$row->getSelectedProduct->name:'';
                    
                    })->addColumn('consignor_id', function($row) {
                        return ($row->getSelectedConsignor)?$row->getSelectedConsignor->name:'';
                    
                    })->addColumn('consignee_id', function($row) {
                        return ($row->getSelectedConsignee)?$row->getSelectedConsignee->name:'';
                    
                    })->addColumn('from_station_id', function($row) {
                        return ($row->getSelectedFromStation)?$row->getSelectedFromStation->name:'';
                    
                    })->addColumn('to_station_id', function($row) {
                        return ($row->getSelectedToStation)?$row->getSelectedToStation->name:'';
                    
                    })->addColumn('back_to_station_id', function($row) {
                        return ($row->getSelectedBackToStation)?$row->getSelectedBackToStation->name:'';
                    
                    })->addColumn('payable_party_id', function($row) {
                        return isset($row->getSelectedPayableParty)?$row->getSelectedPayableParty->name:'';
                    
                    })->addColumn('reporting_datetime', function($row) {
                      return (isset($row->reporting_datetime))?helperConvertDateTimeYmdTodmY($row->reporting_datetime):'';
                    })->addColumn('unload_datetime', function($row) {
                        return isset($row->unload_datetime)?helperConvertDateTimeYmdTodmY($row->unload_datetime):'';
                    
                    })->addColumn('driver_detail', function($row) {
                        $driverName=($row->getSelectedDriver)?$row->getSelectedDriver->name:'';
                        $driverMobileNo=($row->getSelectedDriver)?$row->getSelectedDriver->contact:'';
                        if($driverName!=''){
                            return $driverName.'|'.$driverMobileNo;
                        }else{
                            return '';
                        }
                    })->addColumn('trip_created_by', function($row) {
                        return ($row->getTripCreatedBy)?$row->getTripCreatedBy->name:'';
                    
                    })->addColumn('company_id', function($row) {
                        return ($row->getSelectedCompany)?$row->getSelectedCompany->company_name:'';
                    
                    })->addColumn('transporter_id', function($row) {
                        return ($row->getSelectedTransporter)?$row->getSelectedTransporter->name:''; 

                    })->addColumn('is_market_lr', function($row) {
                        return ($row->is_market_lr==1)?'YES':'NO'; 
                    })->rawColumns(['action','lr_date','vehicle_id','product_id','consignor_id','consignee_id',
                                'from_station_id','to_station_id','back_to_station_id','payable_party_id',
                                'reporting_datetime','unload_datetime',
                                'driver_detail','trip_created_by','company_id','transporter_id','is_market_lr'])
                  ->make(true);
        }
    }
    
    function TransportTripAdd(Request $request){
        if(!empty($request->all())){
            $login_user_id = Auth::user()->id;
            
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
                            'reporting_datetime' => $request->reporting_datetime,
                            'unload_datetime'    => $request->unload_datetime,
                            'unload_weight'      => ($request->unload_weight!='')?$request->unload_weight:0,
                            'shortage_weight'    => ($request->shortage_weight!='')?$request->shortage_weight:0,
                            'driver_id'          => $request->driver_id,
                            'driver_mobile_no'   => $request->driver_mobile_no,
                            'trip_created_by'    => $login_user_id,
                            'vehicle_avg'        => ($request->vehicle_avg!='')?$request->vehicle_avg:0,
                            'km'                 => ($request->km!='')?$request->km:0,
                            'detention_days'     => ($request->detention_days!='')?$request->detention_days:0,
                            'detention'          => ($request->detention!='')?$request->detention:0,
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
                    return redirect('transport-trip-list')->with('success', 'Trip Created !!');
                }else{
                    return redirect('transport-trip-list')->with('error', 'Please try again!');
                }
            }catch(Exception $e) {
                DB::rollback();
                return redirect('transport-trip-list')->with('error', $e->errorInfo[2]);
            }

            
            //old

             //lr_scan
            // $file1 = $request->file('lr_scan');
            // if($file1){
            //     $fileName = time().'.'.$file1->extension();  //get name
            //     $file1->move(public_path('uploads/lr_scan/'), $fileName); //store
            //     $tripData['lr_scan'] = $fileName;
            //     //store new file
            // }
            //lr_scan

            
            
                
            // // $trip_id = TransportTrips::create($tripData)->id;

            // // if($driver_advance!= '' && $driver_advance >0){

            // // $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            // // $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
            // // $login_user_id = Auth::user()->id;
            // // $voucherData=array(
            // //     'trip_id'           => $trip_id,
            // //     'is_direct_entry'  =>  1,
            // //     'is_party_advance'  => 0,
            // //     'voucher_no'        => $voucher_no,
            // //     'branch'            => 'Gandhidham',
            // //     'voucher_entry_date'=> $lr_date,
            // //     'voucher_date'      => $lr_date,
            // //     'vehicle_id'        =>  $tripData['vehicle_id'],
            // //     'payment_type_id'   => 1,
            // //     'payment_mode'   => 'Cash',
            // //     'amount'            => $request->input('driver_advance'),
            // //     'voucher_created_by'=> $login_user_id
            // // );
            // // $response = TransportTripVouchers::create($voucherData);

            // // }

            // // if($diesel != '' && $diesel >0){

            // // $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            // // $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
            // // $login_user_id = Auth::user()->id;
            // // $voucherData=array(
            // //     'trip_id'           => $trip_id,
            // //     'is_direct_entry'  =>  1,
            // //     'is_party_advance'  => 0,
            // //     'voucher_no'        => $voucher_no,
            // //     'branch'            => 'Gandhidham',
            // //     'voucher_entry_date'=> $lr_date,
            // //     'voucher_date'      => $lr_date,
            // //     'vehicle_id'        =>  $tripData['vehicle_id'] ,
            // //     'payment_type_id'   => 2,
            // //     'payment_mode'   => 'Cash',
            // //     'amount'            => $request->input('diesel'),
            // //     'voucher_created_by'=> $login_user_id
            // // );
            // // $response = TransportTripVouchers::create($voucherData);

            // // }


            // if($trip_id!= ''){
            //     return redirect('/transport-trip-edit/'.base64_encode($trip_id))->with('success', 'Transport trip Updated successfully!');
            // }else{
            //     return redirect('/transport-trip-edit/'.base64_encode($trip_id))->with('error', 'Please try again!');
            // }
        }

        $viewData['title']="ADD TRIP";
       // where('vehicle_status','Available')->
        $viewData['vehicles']=Vehicles::where('type','owner')->get();
        $viewData['drivers']=Drivers::get(['id','name','contact']);

        $viewData['products']=Products::get();
        $viewData['locations']=Locations::get();
        
        $viewData['consignorParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',1)  //1==Lr Consignor Parties   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);              
        
        $viewData['consigneeParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',2)  //2==Lr Consignee Parties   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);     
        
        $viewData['payableParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);     
        
        $viewData['transporterParties']=Parties::
                                        leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                        ->where('pspt.party_type_id',3) // 3==Transporter Parties   
                                        ->groupBy('party.id')
                                        ->get(['party.id','party.name']);
        
        $selectedParties = PartySelectedPartyTypes::where('party_type_id',3)
                                                  ->orWhere('party_type_id',4)
                                                  ->groupBy('party_id')
                                                  ->get(['party_id'])
                                                  ->pluck('party_id');
        if($selectedParties){
            $viewData['parties']=Parties::whereIN('id',$selectedParties->toArray())->get(['id','name']);
        }else{
            $viewData['parties']=[];    
        }                                       
        
        $viewData['VehicleModelCodes']=VehicleModelCodes::get();
        $viewData['VehicleTypes']=VehicleTypes::get();
        // $viewData['billing_parties']=Parties::where('party.ledger_type_id',4)->get();
        $viewData['companySettingsData']=CompanySettings::get(['id','company_name']);
        $viewData['partyTypes']=PartyTypes::get(['id','name']);    
        return view('admin.transport_trips.transport_trip_add',$viewData);
    }

    function TransportTripEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            $login_user_id = Auth::user()->id;
           
            extract($request->all());
            try{
                DB::beginTransaction();
                
                $updateTripData=array(
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
                            'reporting_datetime' => $request->reporting_datetime,
                            'unload_datetime'    => $request->unload_datetime,
                            'unload_weight'      => ($request->unload_weight!='')?$request->unload_weight:0,
                            'shortage_weight'    => ($request->shortage_weight!='')?$request->shortage_weight:0,
                            'driver_id'          => $request->driver_id,
                            'driver_mobile_no'   => $request->driver_mobile_no,
                            'trip_created_by'    => $login_user_id,
                            'vehicle_avg'        => ($request->vehicle_avg!='')?$request->vehicle_avg:0,
                            'km'                 => ($request->km!='')?$request->km:0,
                            'detention_days'     => ($request->detention_days!='')?$request->detention_days:0,
                            'detention'          => ($request->detention!='')?$request->detention:0,
                            'company_id'         => $request->company_id,
                            'invoice_qty'        => ($request->invoice_qty!='')?$request->invoice_qty:0,
                            'remarks'            => $request->remarks,
                        );
                $file1 = $request->file('lr_scan');
                if($file1){
                    $lastData=TransportTrips::find($id);
                    if($lastData->lr_scan!=null){
                        unlink(public_path('uploads/lr_scan/'.$lastData->lr_scan));
                    }

                    $fileName = time().'.'.$file1->extension();  //get name
                    $file1->move(public_path('uploads/lr_scan/'), $fileName); //store
                    $updateTripData['lr_scan'] = $fileName;
                    //store new file
                }
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
                      
                      $updateTripData['transporter_id'] = $request->market_transporter_id;
                      $updateTripData['vehicle_id'] = $new_vehicle_id;
                      $updateTripData['market_freight'] = ($request->market_freight!='')?$request->market_freight:0;
                      $updateTripData['is_market_lr'] = $request->is_market_lr;
                
                }else{ //is_market_lr == 0
                      // $updateTripData['transporter_id'] = NULL;
                      $updateTripData['vehicle_id'] = $request->vehicle_id;
                      $updateTripData['is_market_lr'] = 0;
                }

                $res = TransportTrips::where('id',$id)->update($updateTripData);
                DB::commit();

                if($res){
                    return redirect('transport-trip-list')->with('success', 'Trip Updated !!');
                }else{
                    return redirect('transport-trip-list')->with('error', 'Please try again!');
                }
            }catch(Exception $e) {
                DB::rollback();
                return redirect('transport-trip-list')->with('error', $e->errorInfo[2]);
            }

        }  //if close


        $editData = TransportTrips::find($id);
        $viewData['title'] = 'Edit Trip';
        $viewData['editData'] = $editData;

        $viewData['vehicles']=Vehicles::where('type','owner')->get();
        $viewData['drivers']=Drivers::get(['id','name','contact']);

        $viewData['products']=Products::get();
        $viewData['locations']=Locations::get();
        $selectedParties = PartySelectedPartyTypes::where('party_type_id',3)
                                                  ->orWhere('party_type_id',4)
                                                  ->groupBy('party_id')
                                                  ->get(['party_id'])
                                                  ->pluck('party_id');
        if($selectedParties){
            $viewData['parties']=Parties::whereIN('id',$selectedParties->toArray())->get(['id','name']);
        }else{
            $viewData['parties']=[];    
        }                                       
        
        $viewData['VehicleModelCodes']=VehicleModelCodes::get();
        $viewData['VehicleTypes']=VehicleTypes::get();
        $viewData['consignorParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',1)  //1==Lr Consignor Parties   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);              
        
        $viewData['consigneeParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',2)  //2==Lr Consignee Parties   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);     
        
        $viewData['payableParties']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);     
        
        $viewData['transporterParties']=Parties::
                                        leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                        ->where('pspt.party_type_id',3) // 3==Transporter Parties   
                                        ->groupBy('party.id')
                                        ->get(['party.id','party.name']);
        
        
        $viewData['companySettingsData']=CompanySettings::get(['id','company_name']);
        $viewData['partyTypes']=PartyTypes::get(['id','name']);   
        return view('admin.transport_trips.transport_trip_add',$viewData);
    }

    function deleteTransportTrip(Request $request){
        $id=(isset($request->id))?$request->id:'';
        if($id!=''){
            $response=TransportTrips::where('id',$id)->delete();
            if($response){
                return redirect('transport-trip-list')->with('success', 'Transport Trip deleted successfully!');
            }else{
                return redirect('transport-trip-list')->with('error', 'Please try again!');
            }
        }
    }


    function exportTransportTrip(Request $request){

        $paramData=json_decode($request->data,true);
    
        extract($paramData);

            
            $wherestr = " transport_trips.id !=0 ";

            
            // $login_user_role = Auth::user()->role_id;
            
            if($lr_no!= ''){
               $wherestr .= " AND transport_trips.lr_no =  '".$lr_no."'";
            }

            if($from_date!= ''){
               $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$from_date."'";
            }

            if($to_date!= ''){
               $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$to_date."'";
            }

            // if($party_id!= ''){
            //     $wherestr .= " AND transport_trips.party_id =  '".$party_id."'";
            // }

            // if($transporter_id!= ''){
            //     $wherestr .= " AND transport_trips.transporter_id =  '".$transporter_id."'";
            // }

            // if($vehicle_id!= ''){
            //     $wherestr .= " AND transport_trips.vehicle_id =  '".$vehicle_id."'";
            // }

            // if($route_id!= ''){
            //     $wherestr .= " AND transport_trips.route_id =  '".$route_id."'";
            // }

            $data=TransportTrips::whereRaw($wherestr)->select('transport_trips.*')->get();
          
            $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=transport_trips.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


            $columns = array('Sr No.','LR Date','LR No', 'Owner Vehicle No','Product','Consignor','Consignee','From Station','To Station','Back To Station','Gross Weight','Tare Weight','Net Weight','Payable By','Payable By Party','Freight Rate','Reporting Date&Time','Unload Date&Time','Unload Weight','Shortage Weight','Driver','Driver Mobile','Trip Created By');

            $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    ($row->lr_date!=null)?date('d/m/Y',strtotime($row->lr_date)):'',
                                    $row->lr_no,
                                    ($row->getSelectedVehicle)?$row->getSelectedVehicle->registration_no:'',
                                    ($row->getSelectedProduct)?$row->getSelectedProduct->name:'',
                                    ($row->getSelectedConsignor)?$row->getSelectedConsignor->name:'',
                                    ($row->getSelectedConsignee)?$row->getSelectedConsignee->name:'',
                                    ($row->getSelectedFromStation)?$row->getSelectedFromStation->name:'',
                                    ($row->getSelectedToStation)?$row->getSelectedToStation->name:'',
                                    ($row->getSelectedBackToStation)?$row->getSelectedBackToStation->name:'',
                                    $row->gross_weight,
                                    $row->tare_weight,
                                    $row->net_weight,
                                    $row->payable_by,
                                    ($row->getSelectedPayableParty)?$row->getSelectedPayableParty->name:'',
                                    $row->freight_rate,
                                    ($row->reporting_datetime!=null)?date('d/m/Y H:i A',strtotime($row->reporting_datetime)):'',
                                    ($row->unload_datetime!=null)?date('d/m/Y H:i A',strtotime($row->unload_datetime)):'',
                                    $row->unload_weight,
                                    $row->shortage_weight,
                                    ($row->getSelectedDriver)?$row->getSelectedDriver->name:'',
                                    ($row->getSelectedDriver)?$row->getSelectedDriver->contact:'',
                                    ($row->getTripCreatedBy)?$row->getTripCreatedBy->name:'',
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    }

    function printLr(Request $request){
        
        $trip_id = ($request->id)?$request->id:'';;
        $viewData=[];
        $tripData = TransportTrips::where('transport_trips.id',$trip_id)->first();
        $viewData['tripData'] = $tripData;
        $viewData['getCompanyData'] = CompanySettings::where('id',$tripData->company_id)->first();
        
        return view('admin.transport_trips.lr_print',$viewData);
    }


    ///old 


    function addFromLocation(Request $request){
        if(!empty($request->all())){
            $check=Locations::where('name',$request->name)
                              ->where('place_type',$request->place_type)
                              ->count();
            if($check>0){
                echo 0;
            }else{
              $data = array();
              $data['name'] = $request->input('name');
              $data['place_type'] = $request->input('place_type');
              $place_id = Locations::create($data)->id;  
              echo $place_id;
            }
        }
    }

    function  addConsignorTrip(Request $request){
        if(!empty($request->all())){
            $party_name = $request->name;
            $party_type_id_arr = $request->party_type_id;
            
            $check=Parties::where('name',$party_name)->count();
            if($check>0){
                echo 0;
            }else{
                $data = array();
                $data['name'] = $party_name;
                $party_id = Parties::create($data)->id;
                
                if(isset($party_type_id_arr) && !empty($party_type_id_arr)){
                    foreach($party_type_id_arr as $party_type_id){
                        $insertData=['party_id'=>$party_id,'party_type_id'=>$party_type_id];
                        PartySelectedPartyTypes::create($insertData);
                    }//loop close
                } //if close 
                echo $party_id;
            } //else close 
        }
    }

    function  addProduct(Request $request){
        if(!empty($request->all())){
            $check=Products::where('name',$request->input('product_name'))->count();
            if($check>0){
                echo 0;
            }else{
                $data = array();
                $data['name'] = $request->input('product_name');
                $id = Products::create($data)->id;
                echo $id;
            }
        }   
    }

    function  addDriver(Request $request){
        if(!empty($request->all())){
            $check=Drivers::where('contact',$request->input('contact'))->count();
            if($check>0){
                echo 0;
            }else{
                $data = array();
                $data['name'] = $request->input('driver_name');
                $data['contact'] = $request->input('contact');
                $data['home_contact'] = $request->input('home_contact');
                $data['local_address'] = $request->input('local_address');
                $data['permanent_address'] = $request->input('permanent_address');
                $driver_id = Drivers::create($data)->id;
                echo $driver_id;
            }
        } 
    }

    function addVehicleTrip(Request $request){
        if(!empty($request->all())){
            $formData = array();
            parse_str($request->formdata, $formData);   
            
             $check=Vehicles::where('registration_no',$formData['registration_no'])->where('party_id',$formData['party_id'])->count();
            if($check>0){
                echo json_encode([]);

            }else{ 
                $data = array();
                $data['registration_no'] = strtoupper($formData['registration_no']);
                $data['party_id'] = $formData['party_id'];
                $data['vehicle_alias'] = (isset($formData['vehicle_alias']) && $formData['vehicle_alias']!='')?$formData['vehicle_alias']:NULL;
                $data['registration_date'] = (isset($formData['registration_date']) && $formData['registration_date']!='')?$formData['registration_date']:NULL;
                $data['model_code'] = (isset($formData['model_code']) && $formData['model_code']!='')?$formData['model_code']:NULL;
                $data['rto_auth'] = (isset($formData['rto_auth']) && $formData['rto_auth']!='')?$formData['rto_auth']:NULL;
                $data['chassis_no'] = (isset($formData['chassis_no']) && $formData['chassis_no']!='')?$formData['chassis_no']:NULL;
                $data['engine_no'] = (isset($formData['engine_no']) && $formData['engine_no']!='')?$formData['engine_no']:NULL;
                $data['manufacture_year'] = (isset($formData['manufacture_year']) && $formData['manufacture_year']!='')?$formData['manufacture_year']:NULL;
                $data['manufacture_month'] = (isset($formData['manufacture_month']) && $formData['manufacture_month']!='')?$formData['manufacture_month']:NULL;
                $data['purchase_date'] = (isset($formData['purchase_date']) && $formData['purchase_date']!='')?$formData['purchase_date']:NULL;
                $data['purchase_amount'] = (isset($formData['purchase_amount']) && $formData['purchase_amount']!='')?$formData['purchase_amount']:NULL;
                $data['sale_date'] = (isset($formData['sale_date']) && $formData['sale_date']!='')?$formData['sale_date']:NULL;
                $data['sale_amount'] = (isset($formData['sale_amount']) && $formData['sale_amount']!='')?$formData['sale_amount']:NULL;
                $data['gvw_in_kg'] = (isset($formData['gvw_in_kg']) && $formData['gvw_in_kg']!='')?$formData['gvw_in_kg']:NULL;
                $data['ulw_in_kg'] = (isset($formData['ulw_in_kg']) && $formData['ulw_in_kg']!='')?$formData['ulw_in_kg']:NULL;
                $data['vehicle_type'] = (isset($formData['vehicle_type']) && $formData['vehicle_type']!='')?$formData['vehicle_type']:NULL;
                $data['stephanie'] = (isset($formData['stephanie']) && $formData['stephanie']!='')?$formData['stephanie']:'No';
                $data['type'] = (isset($formData['type']) && $formData['type']!='')?$formData['type']:'owner';
                $data['fuel'] = (isset($formData['fuel']) && $formData['fuel']!='')?$formData['fuel']:'petrol';
                $data['remarks'] = (isset($formData['remarks']) && $formData['remarks']!='')?$formData['remarks']:NULL;
                $data['f_t_type'] = (isset($formData['f_t_type']) && $formData['f_t_type']!='')?$formData['f_t_type']:NULL;
                $data['f_total_tyre'] = (isset($formData['f_total_tyre']) && $formData['f_total_tyre']!='')?$formData['f_total_tyre']:NULL;
                $data['b_t_type'] = (isset($formData['b_t_type']) && $formData['b_t_type']!='')?$formData['b_t_type']:NULL;
                $data['b_total_tyre'] = (isset($formData['b_total_tyre']) && $formData['b_total_tyre']!='')?$formData['b_total_tyre']:NULL;
                $data['f_size'] = (isset($formData['f_size']) && $formData['f_size']!='')?$formData['f_size']:NULL;
                $data['b_size'] = (isset($formData['b_size']) && $formData['b_size']!='')?$formData['b_size']:NULL;
                $data['equipment_vehicle'] =(isset($formData['equipment_vehicle']) && $formData['equipment_vehicle']!='')?$formData['equipment_vehicle']:0;
               
                $response = Vehicles::create($data);
                
                echo json_encode($response);
            }
        } 
    }


    //old
    


    function getLastAlloctedDriver(){
        //$wherestr = " (vehicle_status = 'Available' OR v.id = '".$_POST['vehicle_id']. "' AND to_date IS NULL)";
        $wherestr = " v.id = '".$_POST['vehicle_id']. "' AND to_date IS NULL ";

        $vehicle_data = DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($wherestr)
                              ->select(
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->first();
        
        echo json_encode($vehicle_data);
    }  

    function getServiceReqTransData(){
        $transport_job_id=$_POST['transport_job_id'];
        $trip_id=$_POST['trip_id'];

        $serviceReqTransData=TransportJobs::leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                              ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                              ->leftjoin('party as pt','pt.id','sr.party_id')
                                              ->leftjoin('container_type as ct','ct.id','srt.container_type_id')
                                              ->leftjoin('routes as r','r.id','srt.route_id')
                                              ->leftjoin('materials as mtrl','mtrl.id','srt.material_id')
                                              ->leftjoin('units as u','u.id','srt.unit_id')
                                              ->where('transport_jobs.id',$transport_job_id)
                                              ->select(
                                                       'srt.id as servicereq_trans_id',
                                                       'srt.movement_type',
                                                       'srt.job_type',
                                                       'pt.name as party_name',
                                                       \DB::raw("IF(r.back_place!='',CONCAT(r.from_place,'-',r.to_place,'-',r.back_place),CONCAT(r.from_place,'-',r.to_place)) as route_name"),
                                                       'mtrl.name as material_name',
                                                       'srt.budgeted_advance',
                                                       'srt.budgeted_diesel',
                                                       'ct.name as container_type',
                                                       'srt.no_of_container',
                                                       'srt.container_size',
                                                       'srt.is_market_service_request',
                                                       'srt.weight',
                                                       'srt.unit_id as service_request_unit_id',
                                                       'u.name as unit_name',
                                                       \DB::raw("srt.remaining_qty as remaining_qty"),
                                                       'srt.pickup',
                                                       'srt.drop_location',
                                                       'srt.stuffing_location',
                                                       'srt.pol',
                                                       'srt.pod',
                                                       'srt.consignor',
                                                       'srt.consignor_address',
                                                       'srt.consignee',
                                                       'srt.consignee_address',
                                                      )
                                              ->first()
                                              ->toArray();

        $trip_data = array();
        $alloted_container = array();
        if(isset($trip_id) && $trip_id!=''){

            $trip_data = TransportTrips::where('id' ,'=' ,$trip_id)->first();

            $alloted_container_1 = TransportTrips::where('transport_job_id' ,'=' ,$transport_job_id)->where('lr_status' ,'=' ,1)->where('id' ,'!=' ,$trip_id)->pluck('job_container_1_id')->toArray();

            $alloted_container_2 = TransportTrips::where('transport_job_id' ,'=' ,$transport_job_id)->where('lr_status' ,'=' ,1)->where('job_container_2_id' ,'!=' ,'')->where('id' ,'!=' ,$trip_id)->pluck('job_container_2_id')->toArray();

        }else{
          $alloted_container_1 = TransportTrips::where('transport_job_id' ,'=' ,$transport_job_id)->where('lr_status' ,'=' ,1)->pluck('job_container_1_id')->toArray();
          $alloted_container_2 = TransportTrips::where('transport_job_id' ,'=' ,$transport_job_id)->where('lr_status' ,'=' ,1)->where('job_container_2_id' ,'!=' ,'')->pluck('job_container_2_id')->toArray();
        }
        
        $alloted_container = array_merge($alloted_container_1,$alloted_container_2);
        
        $jobContainers=TransportJobContainers::where('transport_job_id',$transport_job_id)->where('container_status','!=','Cancel')->where('container_no','!=','')->whereNotIn('id', $alloted_container)->get()->toArray();

        if(isset($trip_id) && $trip_id!=''){
          $vehicle_id = $trip_data['vehicle_id'];
          $wherestr = " (vehicle_status = 'Available' OR v.id = '".$vehicle_id. "' AND to_date IS NULL)";
        }else{
          $wherestr = " vehicle_status = 'Available' AND to_date IS NULL";
        }
        
        if($serviceReqTransData['job_type'] =='Container' AND $serviceReqTransData['container_size'] == '40FT'){
          $wherestr .= " AND vehicle_type = '".$serviceReqTransData['container_size']."'";
        }else{
          $wherestr .= " AND (vehicle_type = '40FT' OR  vehicle_type = '20FT')";
        }

        // $vehicle_data = Vehicles::leftjoin('party','party.id','vehicles.party_id')
        //                       ->leftjoin('driver_allocated_vehicles as dav','dav.vehicle_id','vehicles.id')
        //                       ->leftjoin('drivers as d','d.id','dav.driver_id')
        //                       ->whereRaw($wherestr)
        //                       ->select(
        //                             'vehicles.id',
        //                             'vehicles.registration_no',
        //                             'vehicles.vehicle_type',
        //                             'party.id as transporter_party_id',
        //                             'party.name as transporter_party_name',
        //                             'd.id as driver_id',
        //                             'd.name as driver_name',
        //                         )
        //                       ->get()
        //                       ->toArray();

        $vehicle_data = DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($wherestr)
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->get()
                              ->toArray();
        
        echo json_encode(['service_req_trans_data'=>$serviceReqTransData,'job_container_data'=>$jobContainers,'trip_data'=>$trip_data,'vehicle_data'=>$vehicle_data]);
    }
    public function updateTransportTripDropDate(Request $request) {
        extract($_POST);
        
        $tripData=array(
            'pickup_date_time'  =>$pickup_date_time,
            'drop_date_time'    =>($drop_date_time!='')?$drop_date_time:NULL,
        );

        $res=TransportTrips::where('id',$trip_id)->update($tripData);
        echo 1;
    }

    public function checkTransportRunningTrip(Request $request) {
        extract($_POST);
        if(isset($trip_id) && $trip_id!=''){
          $previous_trip=TransportTrips::where('vehicle_id',$vehicle_id)->where('id','!=',$trip_id)->where('drop_date_time','=',NULL)->where('lr_status','=',1)->get()->toArray();
        }else{
          $previous_trip=TransportTrips::where('vehicle_id',$vehicle_id)->where('drop_date_time','=',NULL)->where('lr_status','=',1)->get()->toArray();
        }
        
        if(count($previous_trip) == 2){
          echo json_encode($previous_trip);
        }else{
          echo json_encode(array());
        }
    }

    public function checkMarketTransportRunningTrip(Request $request) {
        extract($_POST);
        $vehicle_ids =  Vehicles::where('registration_no',$market_vehicle_no)->pluck('id')->toArray();
        
        if(isset($trip_id) && $trip_id!=''){
          $previous_trip=TransportTrips::whereIn('vehicle_id', $vehicle_ids)->where('id','!=',$trip_id)->where('end_trip_time','=',NULL)->get()->toArray();
        }else{
          $previous_trip=TransportTrips::whereIn('vehicle_id', $vehicle_ids)->where('end_trip_time','=',NULL)->get()->toArray();
        }
        
        if(count($previous_trip) > 0){
          echo json_encode($previous_trip);
        }else{
          echo json_encode(array());
        }
    }

   

    public function detention(){
        $viewData['title']='DETENTION TRIPS';
        $viewData['parties']=Parties::where('party.ledger_type_id',1)->get();
        $viewData['vehicles']=Vehicles::get();
        $viewData['routes']=Routes::get();

        return view('admin.transport_trips.transport_trip_detention_list',$viewData);
    }

    function transportTripDetentionPaginate(Request $request){
        if ($request->ajax()) {
            $current_date = date('Y-m-d h:i:s');

            $wherestr = "transport_trips.end_trip_time IS NULL AND TIMESTAMPDIFF(HOUR,transport_trips.created_at,NOW()) > srt.estimeted_time";
            
            if($request->party_id!= ''){
                $wherestr .= " AND transport_trips.transporter_id =  '".$request->party_id."'";
            }

            if($request->vehicle_id!= ''){
                $wherestr .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
            }
            
            if($request->route_id!= ''){
                $wherestr .= " AND srt.route_id =  '".$request->route_id."'";
            }

            if($request->job_no!= ''){
                $wherestr .= " AND transport_jobs.job_no =  '".$request->job_no."'";
            }

            if($request->lr_no!= ''){
                $wherestr .= " AND transport_trips.lr_no =  '".$request->lr_no."'";
            }

            if($request->is_market_lr!= ''){
                $wherestr .= " AND transport_trips.is_market_lr =  '".$request->is_market_lr."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
            }
            

            // $data=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
            //                       ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
            //                       ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
            //                       ->leftJoin('routes as r', 'r.id', '=', 'srt.route_id')
            //                       ->leftJoin('party', 'party.id', '=', 'sr.party_id')
            //                       ->leftJoin('vehicles as v', 'v.id', '=', 'transport_trips.vehicle_id')
            //                       ->leftJoin('drivers as d', 'd.id', '=', 'transport_trips.driver_id')
            //                       ->whereRaw($wherestr)
            //                       ->select(
            //                         'transport_trips.id',
            //                         'transport_trips.lr_no',
            //                         'transport_jobs.job_no',
            //                         'srt.movement_type',
            //                         'srt.estimeted_time',
            //                         'party.name as party_name',
            //                         'd.name as driver_name',
            //                         'v.registration_no as vehicle_no',
            //                         'transport_trips.detention_remarks',
            //                         \DB::raw("
            //                             DATE_FORMAT(transport_trips.lr_date,'%d/%m/%Y') as lr_date,
            //                             DATE_FORMAT(transport_trips.pickup_date_time,'%d/%m/%Y %H:%i') as pickup_date,
            //                             DATE_FORMAT(transport_trips.drop_date_time,'%d/%m/%Y %H:%i') as drop_date,
            //                             IF(r.back_place!='',CONCAT(r.from_place,'-',r.to_place,'-',r.back_place),CONCAT(r.from_place,'-',r.to_place)) as route_name,
            //                             TIMESTAMPDIFF(HOUR,transport_trips.pickup_date_time,NOW()) as hour_diff
            //                         ")
            //                         )
            //                       ->orderby('id','desc');

            $data=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                  ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                  ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                  ->whereRaw($wherestr)
                                  ->select('transport_trips.*',\DB::raw("TIMESTAMPDIFF(HOUR,transport_trips.pickup_date_time,NOW()) as hour_diff "))
                                  ->orderby('transport_trips.id','desc');

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                           $btn = '<input type="checkbox" id="lr_'.$row['id'].'" class="check_lr" value="'.$row['id'].'">';

                           return $btn;
                    })->addColumn('hour_diff', function($row) {
                           $estimeted_time=0;
                           if($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->estimeted_time){
                             $estimeted_time = $row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->estimeted_time;
                           }
                           $btn = $row['hour_diff'] - $estimeted_time;

                           return $btn;
                    })->addColumn('lr_date', function($row) {
                      return ($row->lr_date!='')?date('d/m/Y',strtotime($row->lr_date)):'';
                    })->addColumn('job_no', function($row) {
                      return isset($row->getTransportJobs->job_no)?$row->getTransportJobs->job_no:'';
                    })->addColumn('estimeted_time', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                        return $row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->estimeted_time;
                      }else{
                        return '';
                      }
                    })->addColumn('party_name', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getPartyDetail)){
                        return $row->getTransportJobs->getServiceRequestDetail->getPartyDetail->name;
                      }else{
                        return '';
                      }
                    })->addColumn('vehicle_no', function($row) {
                      if(isset($row->getVehicle)){
                        return $row->getVehicle->registration_no;
                      }else{
                        return '';
                      }
                    })->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function updateTransportTripDetention(Request $request) {
        extract($_POST);
        $all_trip_ids =  explode(',', $request->trip_ids);
        foreach ($all_trip_ids as $key => $value) {
          if($value > 0){
              $tripData = array();
              if(isset($detention_charges) &&  $detention_charges != ''){
                $tripData['detention_charges']  = $detention_charges;
              }

              if(isset($final_detention_hour) &&  $final_detention_hour != ''){
                $tripData['final_detention_hour']  = $final_detention_hour;
              }

              if(isset($detention_authorised_by) &&  $detention_authorised_by != ''){
                $tripData['detention_authorised_by']  = $detention_authorised_by;
              }

              if(isset($detention_remarks) &&  $detention_remarks != ''){
                $tripData['detention_remarks']  = $detention_remarks;
              }
              
              $res=TransportTrips::where('id',$value)->update($tripData);
          }
        }
        
        echo 1;
  }
   
   function authorisedByMarketTripsPaginate(Request $request){
        if ($request->ajax()) {
            $login_user_id = Auth::user()->id;

            $wherestr = "transport_trips.id !='0'  AND transport_trips.market_freight_authorised_by='".$login_user_id."' AND transport_trips.market_freight_authorised_check='0' ";
            
            if($request->party_id!= ''){
                $wherestr .= " AND transport_trips.transporter_id =  '".$request->party_id."'";
            }

            if($request->vehicle_id!= ''){
                $wherestr .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->route_id!= ''){
                $wherestr .= " AND srt.route_id =  '".$request->route_id."'";
            }

            if($request->job_no!= ''){
                $wherestr .= " AND transport_jobs.job_no =  '".$request->job_no."'";
            }

            if($request->lr_no!= ''){
                $wherestr .= " AND transport_trips.lr_no =  '".$request->lr_no."'";
            }

            if($request->movement_type!= ''){
                $wherestr .= " AND srt.movement_type =  '".$request->movement_type."'";
            }

            if($request->dropdate_missing!= ''){
                if($request->dropdate_missing == 1){
                  $wherestr .= " AND transport_trips.drop_date_time IS NULL ";
                }else{
                  $wherestr .= " AND transport_trips.drop_date_time IS NOT NULL ";
                }
            }

            if($request->is_market_lr!= ''){
                $wherestr .= " AND transport_trips.is_market_lr =  '".$request->is_market_lr."'";
            }

            if($request->market_freight_authorised_by!= ''){
                $wherestr .= " AND transport_trips.market_freight_authorised_by = '".$request->market_freight_authorised_by."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
            }
            
           
           $data=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                  ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                  ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                  ->whereRaw($wherestr)
                                  ->select(
                                    'transport_trips.*',
                                    \DB::raw("
                                        DATE_FORMAT(transport_trips.pickup_date_time,'%d/%m/%Y %H:%i') as pickup_date,
                                        DATE_FORMAT(transport_trips.end_trip_time,'%d/%m/%Y %H:%i') as end_trip
                                    ")
                                    )
                                  ->orderby('id','desc');
            
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                       $btn = '<input type="checkbox" id="row_checkbox_'.$row['id'].'" class="check_single" value="'.$row['id'].'">';
                       return $btn;
                    })
                    ->addColumn('pickup_date', function($row) {
                           $btn = '<div ondblclick="getTrip('.$row['id'].');"><b>Pickup Date: </b><span pickup="'.$row['pickup_date_time'].'" drop="'.$row['end_trip'].'" lrno="'.$row['id'].'" id="pdt'.$row['id'].'">'.$row['pickup_date'].'</span><br><b>End Date: </b><span id="ddt'.$row['id'].'">'.$row['end_trip'].'</span></div>';

                           return $btn;
                    })
                    ->addColumn('trip_detail', function($row) {
                          $btn = '';
                          if($row['trip_created_by'] > 0){
                            $btn .= '<span> Created By  : '.$row->getTripCreatedBy->name.'</span><br>';
                          }

                          if($row['trip_unloading_by'] > 0){
                            $btn .= '<span> Unloading By  : '.$row->getTripUnloadingBy->name.'</span><br>';
                          }

                          if($row['trip_end_by'] > 0){
                            $btn .= '<span> Free By  : '.$row->getTripEndBy->name.'</span><br>';
                          }

                           return $btn;
                    })->addColumn('lr_date', function($row) {
                      return isset($row->lr_date)?date('d/m/Y',strtotime($row->lr_date)):'';
                    })->addColumn('job_no', function($row) {
                      return isset($row->getTransportJobs->job_no)?$row->getTransportJobs->job_no:'';
                    })->addColumn('pickup', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                        return $row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->pickup;
                      }else{
                        return '';
                      }
                    })->addColumn('party_name', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getPartyDetail)){
                        return $row->getTransportJobs->getServiceRequestDetail->getPartyDetail->name;
                      }else{
                        return '';
                      }
                  })->addColumn('driver_name', function($row) {
                      if(isset($row->getDriver->name)){
                        return $row->getDriver->name;
                      }else{
                        return '';
                      }
                  })->addColumn('vehicle_no', function($row) {
                      if(isset($row->getVehicle)){
                        return $row->getVehicle->registration_no;
                      }else{
                        return '';
                      }
                  })->addColumn('route_name', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail)){
                        $routeData= $row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail;
                        if($routeData->back_place!=''){
                            return $routeData->from_place.'-'.$routeData->to_place.'-'.$routeData->back_place;
                        }else{
                            return $routeData->from_place.'-'.$routeData->to_place;
                        }
                      }else{
                        return '';
                      }
                  })->addColumn('container_no_or_wght', function($row) {
                    if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                            $job_type=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->job_type;
                            $no_of_container=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->no_of_container;
                            $weight=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->weight;
                            if($job_type=='Container'){
                                return $no_of_container;
                            }else{
                                return $weight;
                            }
                        }else{
                            return '';
                        }
                  })->addColumn('size', function($row) {
                      if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                            $job_type=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->job_type;
                            $container_size=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->container_size;
                            $material_name=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getMaterialDetail->name;
                            if($job_type=='Container'){
                                return $container_size;
                            }else{
                                return $material_name;
                            }
                        }else{
                            return '';
                        } 
                  })->addColumn('our_rate', function($row) {
                      $service_charge=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->service_charge;
                      $party_commission=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->party_commission;
                        return ($service_charge-$party_commission);
                  })->addColumn('market_booking_rate', function($row) {
                      return $row->market_freight;
                  })->addColumn('transporter', function($row) {
                    if(isset($row->getTransporter)){
                        return $row->getTransporter->name;
                    }else{
                        return '';
                    }
                  })->rawColumns(['action','pickup_date','trip_detail'])
                    ->make(true);
        }

        $viewData['title']='AUTHORISED BY MARKET FREIGHT TRIPS';
        $viewData['parties']=Parties::where('party.ledger_type_id',1)->get();
        $viewData['vehicles']=Vehicles::get();
        $viewData['routes']=Routes::get();
        return view('admin.transport_trips.authorised_by_market_trip_list',$viewData);

   }

    function updateAuthorisedByMarketTrips(Request $request){
        $dataArr =  explode(',', $request->id);
        
        $login_user_id = Auth::user()->id;
        
        foreach ($dataArr as $key => $value) {
            if($value > 0){
                $updateData=array('market_freight_authorised_check'=>1);
                $res=TransportTrips::where('id',$value)->update($updateData);
            }
        }
        echo  1;
    }

    function checkContainerNo(){
        $container_no = str_split($_POST['container_no']);
        $allData = array(
                        '0'=> 0,
                        '1'=> 1,
                        '2'=> 2,
                        '3'=> 3,
                        '4'=> 4,
                        '5'=> 5,
                        '6'=> 6,
                        '7'=> 7,
                        '8'=> 8,
                        '9'=> 9,
                        'A'=> 10,
                        'B'=> 12,
                        'C'=> 13,
                        'D'=> 14,
                        'E'=> 15,
                        'F'=> 16,
                        'G'=> 17,
                        'H'=> 18,
                        'I'=> 19,
                        'J'=> 20,
                        'K'=> 21,
                        'L'=> 23,
                        'M'=> 24,
                        'N'=> 25,
                        'O'=> 26,
                        'P'=> 27,
                        'Q'=> 28,
                        'R'=> 29,
                        'S'=> 30,
                        'T'=> 31,
                        'U'=> 32,
                        'V'=> 34,
                        'W'=> 35,
                        'X'=> 36,
                        'Y'=> 37,
                        'Z'=> 38,
                        );

        $factorData = array(
                        '0'=>1,
                        '1'=>2,
                        '2'=>4,
                        '3'=>8,
                        '4'=>16,
                        '5'=>32,
                        '6'=>64,
                        '7'=>128,
                        '8'=>256,
                        '9'=>512
                        );

        $final_arr = array();
        
        if(count($container_no) > 10){
            foreach ($container_no as $key => $value) {
                if($key < 10 ){
                    $digit = $allData[$value];
                    $factor = $factorData[$key];
                    $test = $digit * $factor; 

                    array_push($final_arr, $test);
                }else{
                    $lastDigit = $allData[$value];
                }
            }

            $sumAll =  array_sum($final_arr);
            $divisionData = ((int)($sumAll/11))*11;
            $lastData = $sumAll - $divisionData;
            
            if($lastDigit == 0){
                echo 1;
            }else{
                if($lastData == $lastDigit){
                    echo 1;
                }else{
                    echo 0;
                } 
            }
        }else{
            echo 0;
        }   
    }

} //class close