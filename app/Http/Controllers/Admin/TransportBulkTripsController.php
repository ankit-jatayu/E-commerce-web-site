<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;
use Storage;

use App\Models\Vehicles;
use App\Models\Parties;
use App\Models\Routes;
use App\Models\User;
use App\Models\CompanySettings;

use App\Models\Drivers;
use App\Models\DriverAllocateVehicles;
use App\Models\Consignees;

// use App\Models\Locations;
// use App\Models\Containertypes;
// use App\Models\Material;
// use App\Models\Units;

// use App\Models\ServiceRequest;
// use App\Models\ServiceRequestType;
// use App\Models\ServiceRequestTransport;
// use App\Models\ServiceRequestAllotment;

// use App\Models\TransportJobs;
// use App\Models\TransportJobContainers;

use App\Models\TransportTrips;
use App\Models\VehicleTypes;
use App\Models\TransportTripVouchers;
use App\Models\Materials;

class TransportBulkTripsController extends Controller
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
        $viewData['title']='ALL BULK TRIPS';
        
        $viewData['parties']=Parties::where('party.ledger_type_id',4)->get();
        $viewData['transporter']=Parties::where('party.ledger_type_id',2)->get();
        $viewData['vehicles']=Vehicles::get();
        $viewData['routes']=Routes::get();
          
        $viewData['market_trip']= (isset($_GET['is_market_lr']))?$_GET['is_market_lr']:''; 
        $viewData['bill_panding']= (isset($_GET['bill_id']))?$_GET['bill_id']:''; 

        //$viewData['authories_list']=User::where('is_authorised',1)->get();
        return view('admin.bulk_transport_trips.bulk_transport_trip_list',$viewData);
    }

    function TransportTripPaginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "transport_trips.trip_type = 'Material' AND transport_trips.id !='0' ";

            $login_user_role = Auth::user()->role_id;
            // if($login_user_role == 12){
            //     $login_user_party = Auth::user()->party_id;
            //     $all_vehicles = Vehicles::where('party_id' ,'=' ,$login_user_party)->pluck('id')->toArray();

            //     $wherestr .= " AND transport_trips.vehicle_id  IN  (".implode(",",$all_vehicles).") ";
            // }

            if($request->party_id!= ''){
                $wherestr .= " AND transport_trips.transporter_id =  '".$request->party_id."'";
            }

            if($request->vehicle_id!= ''){
                $wherestr .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->route_id!= ''){
                $wherestr .= " AND transport_trips.route_id =  '".$request->route_id."'";
            }

            if($request->lr_no!= ''){
                $wherestr .= " AND transport_trips.lr_no =  '".$request->lr_no."'";
            }

            if($request->is_market_lr!= ''){
                $wherestr .= " AND transport_trips.is_market_lr =  '".$request->is_market_lr."'";
            }

              if($request->bill_id!= ''){
                $wherestr .= " AND transport_trips.bill_id =  '".$request->bill_id."'";
            }


            if($request->from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
            }
            
            $data=TransportTrips::whereRaw($wherestr)->select('transport_trips.*')->orderby('id','desc');
    
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                            $user_id = Auth::user()->id;
                            $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                            $TripModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==5;})->first();
                            $btn ='';
                            if(isset($TripModuleRights) && $TripModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<a href="'.route('bulk.transport.trip.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            }
                            // $btn .= '&nbsp;&nbsp;<a class="edit btn btn-warning btn-sm ml-1" onclick="printLr('.$row->id.')"><i class="feather icon-printer" style="color: white;"></i></a>';

                            if(isset($TripModuleRights) && $TripModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }

                           return $btn;
                    })
                  
                    ->addColumn('trip_detail', function($row) {
                        $btn = '';
                        if($row['trip_created_by'] > 0){
                            $btn .= '<span> Created By  : '.$row->getTripCreatedBy->name.'</span><br>';
                        }
                        if($row->is_loading=='Pending'){
                            $btn .= '<span> Loading Status  : <label class="label label-danger">'.$row->is_loading.'</label></span><br>';
                        }else{
                            $btn .= '<span> Loading Status  : <label class="label label-success">'.$row->is_loading.'</label></span><br>';
                        }

                        return $btn;
                    })->addColumn('lr_date', function($row) {
                      return isset($row->lr_date)?date('d/m/Y',strtotime($row->lr_date)):'';
                    })->addColumn('transporter_name', function($row) {
                      return (isset($row->getTransporter))?$row->getTransporter->name:'';
                    })->addColumn('party_name', function($row) {
                      return (isset($row->getBillingParty))?$row->getBillingParty->name:'';
                    })->addColumn('vehicle_no', function($row) {
                      if(isset($row->getVehicle)){
                        return $row->getVehicle->registration_no;
                      }else{
                        return '';
                      }
                  })->addColumn('route_name', function($row) {
                      if(isset($row->getRoute)){
                        $routeData= $row->getRoute;
                        $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                        $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                        $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                        return $RouteName;
                      }else{
                        return '';
                      }
                  })
                  ->rawColumns(['action','trip_detail'])
                  ->make(true);
        }
    }
    
    function deleteTransportTrip(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=TransportTrips::where('id',$id)->delete();
            if($response){
                return redirect('bulk-transport-trip-list')->with('success', 'Transport Trip deleted successfully!');
            }else{
                return redirect('bulk-transport-trip-list')->with('error', 'Please try again!');
            }
        }
    }

    function TransportTripAdd(Request $request){
        if(!empty($request->all())){
            extract($request->all());


            $login_user_id = Auth::user()->id;
            $getLRData = TransportTrips::select('lr_no')->orderBy('lr_no','DESC')->first();
            $lr_no=(!empty($getLRData))?($getLRData->lr_no+1):1;
            

            $tripData=array(
                            'lr_no'             =>$lr_no,
                            'lr_date'           =>$lr_date,
                            'route_id'          =>$route_id,
                            'party_id'          =>$party_id,
                            'material_id'          =>$material_id,
                            'consigness_id'          =>$consigness_id,
                            'consigner_id'          =>$consigner_id,
                            'trip_type'          =>'Material',
                            'rate'          =>$rate,
                            'l_mt'          =>$l_mt,
                            'u_mt'          =>$u_mt,
                            'driver_id'         =>($driver_id!='')?$driver_id:'',
                            'market_freight'       =>($market_freight!='')?$market_freight:0,
                            'freight'        =>($freight!='')?$freight:0,
                            'is_market_lr'      =>(isset($is_market_lr)  && $is_market_lr!='')?$is_market_lr:0,
                            'trip_created_by'   =>$login_user_id,
                        );

             //lr_scan
            $file1 = $request->file('lr_scan');
            if($file1){
                $fileName = time().'.'.$file1->extension();  //get name
                $file1->move(public_path('uploads/lr_scan/'), $fileName); //store
                $tripData['lr_scan'] = $fileName;
                //store new file
            }
            //lr_scan

            if(isset($is_market_lr) && $is_market_lr == 1){

              $vehicle_data = Vehicles::where('registration_no',$market_vehicle_no)->where('party_id',$market_transporter_id)->first();
              
              if(empty($vehicle_data)){
                $new_vehicle_data = array(
                                          'registration_no' =>$market_vehicle_no,
                                          'party_id'        =>$market_transporter_id,
                                          'type'            =>'market',
                                          'vehicle_status'  => 'Available'
                                        );
                $new_vehicle_id = Vehicles::create($new_vehicle_data)->id;
              }else{
                $new_vehicle_id = $vehicle_data->id;
              }
              
              $tripData['transporter_id'] = $market_transporter_id;
              $tripData['vehicle_id'] = $new_vehicle_id;
            
            }else{
              $tripData['transporter_id'] = $transporter_id;
              $tripData['vehicle_id'] = $vehicle_id;
            }
            
                
            $trip_id = TransportTrips::create($tripData)->id;

            if($driver_advance!= '' && $driver_advance >0){

            $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
            $login_user_id = Auth::user()->id;
            $voucherData=array(
                'trip_id'           => $trip_id,
                'is_direct_entry'  =>  1,
                'is_party_advance'  => 0,
                'voucher_no'        => $voucher_no,
                'branch'            => 'Gandhidham',
                'voucher_entry_date'=> $lr_date,
                'voucher_date'      => $lr_date,
                'vehicle_id'        => $request->input('vehicle_id'),
                'payment_type_id'   => 1,
                'payment_mode'   => 'Cash',
                'amount'            => $request->input('driver_advance'),
                'voucher_created_by'=> $login_user_id
            );
            $response = TransportTripVouchers::create($voucherData);

            }

            if($diesel != '' && $diesel >0){

            $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
            $login_user_id = Auth::user()->id;
            $voucherData=array(
                'trip_id'           => $trip_id,
                'is_direct_entry'  =>  1,
                'is_party_advance'  => 0,
                'voucher_no'        => $voucher_no,
                'branch'            => 'Gandhidham',
                'voucher_entry_date'=> $lr_date,
                'voucher_date'      => $lr_date,
                'vehicle_id'        => $request->input('vehicle_id'),
                'payment_type_id'   => 2,
                'payment_mode'   => 'Cash',
                'amount'            => $request->input('diesel'),
                'voucher_created_by'=> $login_user_id
            );
            $response = TransportTripVouchers::create($voucherData);

            }


            if($trip_id!= ''){
                return redirect('/bulk-transport-trip-edit/'.base64_encode($trip_id))->with('success', 'Transport trip Updated successfully!');
            }else{
                return redirect('/bulk-transport-trip-edit/'.base64_encode($trip_id))->with('error', 'Please try again!');
            }
        }

        $viewData['title']="ADD BULK TRIP";
       // where('vehicle_status','Available')->
        $viewData['Vehicles']=DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
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
        $viewData['Routes']=Routes::get();
        $viewData['materials']=Materials::get();
        $viewData['consignees']=Consignees::get();
        $viewData['billing_parties']=Parties::where('party.ledger_type_id',4)->get();
        $viewData['transport_parties']=Parties::where('party.ledger_type_id',2)->get();
        
        return view('admin.bulk_transport_trips.bulk_transport_trip_add',$viewData);
    }

    function TransportTripEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($request->all());
            
            // $tripData=array(
            //                 'entry_date'        =>str_replace("T"," ",$entry_date),
            //                 'lr_date'           =>$lr_date,
            //                 'route_id'          =>$route_id,
            //                 'party_id'          =>$party_id,
            //                 'driver_id'         =>($driver_id!='')?$driver_id:'',
            //                 'rate'              =>$rate,
            //                 'gross_weight'      =>($gross_weight!='')?$gross_weight:0,
            //                 'tare_weight'       =>($tare_weight!='')?$tare_weight:0,
            //                 'net_weight'        =>($net_weight!='')?$net_weight:0,
            //                 'unload_weight'     =>($unload_weight!='')?$unload_weight:0,
            //                 'short_weight'      =>($short_weight!='')?$short_weight:0,
            //                 'damage_amount'     =>($damage_amount!='')?$damage_amount:0,
            //                 'shortage_amount'   =>($shortage_amount!='')?$shortage_amount:0,
            //                 'tds_amount'        =>($tds_amount!='')?$tds_amount:0,
            //                 'freight'           =>($freight!='')?$freight:0,
            //                 'is_market_lr'      =>(isset($is_market_lr)  && $is_market_lr!='')?$is_market_lr:0,
            //                 'market_freight'    =>$market_freight,
            //             );

                 $tripData=array(
                            'lr_date'           =>$lr_date,
                            'route_id'          =>$route_id,
                            'party_id'          =>$party_id,
                            'material_id'          =>$material_id,
                            'consigness_id'          =>$consigness_id,
                            'consigner_id'          =>$consigner_id,
                            'trip_type'          =>'Material',
                            'rate'          =>$rate,
                            'l_mt'          =>$l_mt,
                            'u_mt'          =>$u_mt,
                            'driver_id'         =>($driver_id!='')?$driver_id:'',
                            'market_freight'       =>($market_freight!='')?$market_freight:0,
                            'freight'        =>($freight!='')?$freight:0,
                            'is_market_lr'      =>(isset($is_market_lr)  && $is_market_lr!='')?$is_market_lr:0,
                        );
             //lr_scan
            $file1 = $request->file('lr_scan');
            if($file1){
                $lastData=TransportTrips::find($id);
                if($lastData->lr_scan!=null){
                    unlink(public_path('uploads/lr_scan/'.$lastData->lr_scan));
                }

                $fileName = time().'.'.$file1->extension();  //get name
                $file1->move(public_path('uploads/lr_scan/'), $fileName); //store
                $tripData['lr_scan'] = $fileName;
                //store new file
            }
            //lr_scan
            
            if(isset($is_market_lr) && $is_market_lr == 1){

              $vehicle_data = Vehicles::where('registration_no',$market_vehicle_no)->where('party_id',$market_transporter_id)->first();
              
              if(empty($vehicle_data)){
                $new_vehicle_data = array(
                                          'registration_no' =>$market_vehicle_no,
                                          'party_id'        =>$market_transporter_id,
                                          'type'            =>'market',
                                          'vehicle_status'  => 'Available'
                                        );
                $new_vehicle_id = Vehicles::create($new_vehicle_data)->id;
              }else{
                $new_vehicle_id = $vehicle_data->id;
              }
              
              $tripData['transporter_id'] = $market_transporter_id;
              $tripData['vehicle_id'] = $new_vehicle_id;
            
            }else{
              $tripData['transporter_id'] = $transporter_id;
              $tripData['vehicle_id'] = $vehicle_id;
            }

            $res=TransportTrips::where('id',$id)->update($tripData);

            if($driver_advance != '' && $driver_advance >0){
                $voucherData=array(
                    'trip_id'           => $id,
                    'is_direct_entry'  =>  1,
                    'is_party_advance'  => 0,
                    'branch'            => 'Gandhidham',
                    'voucher_entry_date'=> $lr_date,
                    'voucher_date'      => $lr_date,
                    'vehicle_id'        => $request->input('vehicle_id'),
                    'payment_type_id'   => 1,
                    'payment_mode'   => 'Cash',
                    'amount'            => $request->input('driver_advance'),
                );


                $checkVoucher1Cnt=TransportTripVouchers::where('trip_id',$id)
                                                   ->where('is_direct_entry',1)
                                                   ->where('payment_type_id',1)
                                                   ->count();
                if($checkVoucher1Cnt>0){
                    $response = TransportTripVouchers::where('trip_id',$id)
                                                   ->where('is_direct_entry',1)
                                                   ->where('payment_type_id',1)//driver advance payment type
                                                   ->update($voucherData);
                }elseif($checkVoucher1Cnt==0){
                    $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
                    $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
                    $login_user_id = Auth::user()->id;
                    $voucherData['voucher_no']=$voucher_no;
                    $voucherData['voucher_created_by']=$login_user_id;
                    TransportTripVouchers::create($voucherData);

                }                                   
                            
                

            }

            if($diesel != '' && $diesel >0){

                $voucherData=array(
                    'trip_id'           => $id,
                    'is_direct_entry'  =>  1,
                    'is_party_advance'  => 0,
                    'branch'            => 'Gandhidham',
                    'voucher_entry_date'=> $lr_date,
                    'voucher_date'      => $lr_date,
                    'vehicle_id'        => $request->input('vehicle_id'),
                    'payment_type_id'   => 2,
                    'payment_mode'   => 'Cash',
                    'amount'            => $request->input('diesel'),
                );

                 $checkVoucher1Cnt=TransportTripVouchers::where('trip_id',$id)
                                                   ->where('is_direct_entry',1)
                                                   ->where('payment_type_id',2)//diesel payment type
                                                   ->count();
                if($checkVoucher1Cnt>0){
                    $response = TransportTripVouchers::where('trip_id',$id)
                                                   ->where('is_direct_entry',1)
                                                   ->where('payment_type_id',2)//diesel payment type
                                                   ->update($voucherData);
                }elseif($checkVoucher1Cnt==0){
                    $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
                    $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;
                    $login_user_id = Auth::user()->id;
                    $voucherData['voucher_no']=$voucher_no;
                    $voucherData['voucher_created_by']=$login_user_id;
                    TransportTripVouchers::create($voucherData);

                }              


            }

            if($res!= ''){
                return redirect('/bulk-transport-trip-list')->with('success', 'Transport trip Updated successfully!');
            }else{
                return redirect('/bulk-transport-trip-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = TransportTrips::find($id);
        $viewData['editData'] = $editData;
        $viewData['edit_selected_driver_name'] = (isset($editData->getSelectedDriver))?$editData->getSelectedDriver->name:'';
        $viewData['market_vehicle_no'] = (isset($editData->getVehicle))?$editData->getVehicle->registration_no:'';
        $viewData['edit_selected_vehicle_transporter_name'] = (isset($editData->getVehicle->getTransporter))?$editData->getVehicle->getTransporter->name:'';

        
        $viewData['title']='LR NO : '.$editData->lr_no;
        // where('vehicle_status','Available')->
        $viewData['Vehicles']=DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name'
                                )
                              ->get();
        // $viewData['Vehicles']=Vehicles::where('type','owner')->get();
        $viewData['Routes']=Routes::get();
        // $viewData['billing_parties']=Parties::where('party.ledger_type_id',1)->get();
        $viewData['billing_parties']=Parties::where('party.ledger_type_id',4)->get();
        $viewData['transport_parties']=Parties::where('party.ledger_type_id',2)->get();

        $viewData['materials']=Materials::get();
        $viewData['consignees']=Consignees::get();
        $tripDriverAdvVchrData=TransportTripVouchers::where('trip_id',$id)->where('is_direct_entry',1)->where('payment_type_id',1)->first();
        $tripDieselVchrData=TransportTripVouchers::where('trip_id',$id)->where('is_direct_entry',1)->where('payment_type_id',2)->first();
        $viewData['editTripVchrDriverAdvAmt']=(isset($tripDriverAdvVchrData->amount) && $tripDriverAdvVchrData->amount!='')?$tripDriverAdvVchrData->amount:0;
        $viewData['editTripVchrDieselAmt']=(isset($tripDieselVchrData->amount) && $tripDieselVchrData->amount!='')?$tripDieselVchrData->amount:0;
        //$viewData['authories_list']=User::where('is_authorised',1)->get();

        return view('admin.bulk_transport_trips.bulk_transport_trip_add',$viewData);
    }

    function  addMaterial(Request $request){
        if(!empty($request->all())){

            $data = array();
            $data['name'] = $request->input('material_name');
            

            $response = Materials::create($data);
        }
        $getData=Materials::get();
        echo json_encode($getData);
    }

    function  addConsigness(Request $request){
        if(!empty($request->all())){

            $data = array();
            $data['company_name'] = $request->input('company_name');
            $data['gst_no'] = $request->input('gst_no');
            $data['address'] = $request->input('address');
            

            $response = Consignees::create($data);
        }
        $getData=Consignees::get();
        echo json_encode($getData);
    }

    function  addConsigner(Request $request){
        if(!empty($request->all())){

            $data = array();
            $data['company_name'] = $request->input('c_name');
            $data['gst_no'] = $request->input('g_no');
            $data['address'] = $request->input('consigner_address');
            

            $response = Consignees::create($data);
        }
        $getData=Consignees::get();
        echo json_encode($getData);
    }

    function printLr(){
        
        $trip_id = $_GET['id'];
        // $viewData['trip_data']=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
        //                           ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
        //                           ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
        //                           ->leftJoin('materials as m', 'm.id', '=', 'srt.material_id')
        //                           ->leftjoin('units as u','u.id','transport_trips.unit_id')
        //                           ->leftJoin('routes as r', 'r.id', '=', 'srt.route_id')
        //                           ->leftJoin('party', 'party.id', '=', 'transport_trips.transporter_id')
        //                           ->leftJoin('vehicles as v', 'v.id', '=', 'transport_trips.vehicle_id')
        //                           ->leftJoin('drivers as d', 'd.id', '=', 'transport_trips.driver_id')
        //                           ->leftJoin('transport_job_containers as tjc', 'tjc.id', '=', 'transport_trips.job_container_1_id')
        //                           ->leftJoin('transport_job_containers as tjc2', 'tjc2.id', '=', 'transport_trips.job_container_2_id')
        //                           ->where('transport_trips.id',$trip_id)
        //                           ->select(
        //                             'transport_trips.id',
        //                             'transport_trips.lr_no',
        //                             'transport_trips.market_lr_no',
        //                             'transport_jobs.job_no',
        //                             'srt.movement_type',
        //                             'srt.container_size',
        //                             'srt.consignee',
        //                             'srt.consignee_address',
        //                             'srt.consignor',
        //                             'srt.consignor_address',
        //                             'party.name as party_name',
        //                             'd.name as driver_name',
        //                             'v.registration_no as vehicle_no',
        //                             'tjc.container_no as container1',
        //                             'tjc2.container_no as container2',
        //                             'm.name as material_name',
        //                             'u.name as unit_name',
        //                             'transport_trips.weight as transport_trip_weight',
        //                             'transport_trips.trip_remarks',
        //                             \DB::raw("
        //                                 CONCAT(DATE_FORMAT(transport_trips.entry_date,'%d/%m/%Y'),' ',DATE_FORMAT(transport_trips.entry_date,'%r')) as job_date,
        //                                 DATE_FORMAT(transport_trips.lr_date,'%d-%m-%Y') as lr_date,
        //                                 r.from_place,
        //                                 r.to_place,
        //                                 r.back_place,
        //                                 IF(r.back_place!='',CONCAT(r.from_place,'-',r.to_place,'-',r.back_place),CONCAT(r.from_place,'-',r.to_place)) as route_name
        //                             ")
        //                             )
        //                           ->first();
        // print_r('<pre>');
        // print_r($trip_data);
        // exit;
        $viewData=[];
        $viewData['getCompamyData'] = CompanySettings::where('id',1)->first();
        return view('admin.bulk_transport_trips.lr_print',$viewData);
    }


    function exportTransportTrip(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "transport_trips.trip_type = 'Material' ";
            
            $login_user_role = Auth::user()->role_id;
            

            if($party_id!= ''){
                $wherestr .= " AND transport_trips.party_id =  '".$party_id."'";
            }

            if($transporter_id!= ''){
                $wherestr .= " AND transport_trips.transporter_id =  '".$transporter_id."'";
            }

            if($vehicle_id!= ''){
                $wherestr .= " AND transport_trips.vehicle_id =  '".$vehicle_id."'";
            }

            if($route_id!= ''){
                $wherestr .= " AND transport_trips.route_id =  '".$route_id."'";
            }

            if($lr_no!= ''){
                $wherestr .= " AND transport_trips.lr_no =  '".$lr_no."'";
            }

            if($from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$from_date."'";
            }

            if($to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$to_date."'";
            }
            

            $data=TransportTrips::whereRaw($wherestr)->select('transport_trips.*')->get();
          
            $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=transport_trips.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


            $columns = array('Sr Np.','Party','Lr No','Lr Date', 'Vehicle No', 'Driver' , 'Route','Rate','L MT','Fright', 'Transporter','Market freight');

            $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                $route_name='';
                if(isset($row->getRoute)){
                    $routeData= $row->getRoute;
                    $route_name=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                    $route_name.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                    $route_name.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                }

                fputcsv($file,array($sr,
                                    $row->getBillingParty->name,
                                    $row->lr_no,
                                    ($row->lr_date!=null)?$row->lr_date:'',
                                    (isset($row->getVehicle))?$row->getVehicle->registration_no:'',
                                    (isset($row->getSelectedDriver))?$row->getSelectedDriver->name:'',
                                    $route_name,
                                    $row->rate,
                                    $row->l_mt,
                                    $row->freight,
                                    (isset($row->getTransporter))?$row->getTransporter->name:'',
                                    $row->market_freight,
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    }

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

        return view('admin.bulk_transport_trips.bulk_transport_trip_detention_list',$viewData);
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
        return view('admin.bulk_transport_trips.authorised_by_market_trip_list',$viewData);

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

} //class close