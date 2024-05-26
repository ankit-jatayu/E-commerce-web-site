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

use App\Models\TransportTrips;
use App\Models\TransportTripVouchers;
use App\Models\VehicleTypes;

class ReportsController extends Controller
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
    function commonViewData(){
        $viewData=[];
        $viewData['vehiclesData']=helperGetAllVehicles();
        // $viewData['parties']=Parties::where('party.ledger_type_id',2)->get();
        // $viewData['routes']=Routes::get();
        return $viewData;
    }
    
    function dieselReport(){
        $viewData=$this->commonViewData();
        $viewData['title']='DIESEL REPORT';
        return view('admin.reports.diesel_report',$viewData);
    }

    function dieselReportData(Request $request){
        $whereStrTrip =' transport_trips.id!=0 ';
        if($request->vehicle_id!= ''){
            $whereStrTrip .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
        }

        if($request->from_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
        }

        if($request->to_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
        }
        
        $tripData=TransportTrips::leftJoin('places as from_station','from_station.id','transport_trips.from_station_id')
                                    ->leftJoin('places as to_station','to_station.id','transport_trips.to_station_id')
                                    ->whereRaw($whereStrTrip)
                                    ->select('transport_trips.*','from_station.name as from_station','to_station.name as to_station')
                                    ->get();

        
        $whereStrTripVoucher =' trip_voucher.id!=0 AND trip_voucher.fuel_qty > 0';
        if($request->vehicle_id!= ''){
            $whereStrTripVoucher .= " AND trip_voucher.vehicle_id =  '".$request->vehicle_id."'";
        }

        if($request->from_date!= ''){
            $whereStrTripVoucher .= " AND DATE(trip_voucher.voucher_date) >=  '".$request->from_date."'";
        }

        if($request->to_date!= ''){
            $whereStrTripVoucher .= " AND DATE(trip_voucher.voucher_date) <=  '".$request->to_date."'";
        }

        $tripVoucherData=TransportTripVouchers::leftJoin('party as fuel_station','fuel_station.id','trip_voucher.fuel_station_id')
                                    ->whereRaw($whereStrTripVoucher)
                                    ->select('trip_voucher.*','fuel_station.name as fuel_station')
                                    ->get();

        $temp=['tripData'=>$tripData,'tripVoucherData'=>$tripVoucherData];    
        echo json_encode($temp);

    }//func close


    function dieselReportPrint(Request $request){
        $whereStrTrip =' transport_trips.id!=0 ';
        if($request->vehicle_id!= ''){
            $whereStrTrip .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
        }

        if($request->from_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
        }

        if($request->to_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
        }
        
        $tripData=TransportTrips::leftJoin('places as from_station','from_station.id','transport_trips.from_station_id')
                                    ->leftJoin('places as to_station','to_station.id','transport_trips.to_station_id')
                                    ->whereRaw($whereStrTrip)
                                    ->select('transport_trips.*','from_station.name as from_station','to_station.name as to_station')
                                    ->get();

        
        $whereStrTripVoucher =' trip_voucher.id!=0 AND trip_voucher.fuel_qty > 0 ';
        if($request->vehicle_id!= ''){
            $whereStrTripVoucher .= " AND trip_voucher.vehicle_id =  '".$request->vehicle_id."'";
        }

        if($request->from_date!= ''){
            $whereStrTripVoucher .= " AND DATE(trip_voucher.voucher_date) >=  '".$request->from_date."'";
        }

        if($request->to_date!= ''){
            $whereStrTripVoucher .= " AND DATE(trip_voucher.voucher_date) <=  '".$request->to_date."'";
        }

        $tripVoucherData=TransportTripVouchers::leftJoin('party as fuel_station','fuel_station.id','trip_voucher.fuel_station_id')
                                    ->whereRaw($whereStrTripVoucher)
                                    ->select('trip_voucher.*','fuel_station.name as fuel_station')
                                    ->get();
        $vehicleData = Vehicles::where('id',$request->vehicle_id)->first();
        $viewData=['tripData'=>$tripData,'tripVoucherData'=>$tripVoucherData];   
        $viewData['vehicleData']=$vehicleData; 
        return view('admin.reports.report_diesel_print',$viewData);

    } //func close

    function driverTripReport(){
        $viewData=[];
        return view('admin.reports.driver_trips_report',$viewData);
    }

    function driverTripReportData(Request $request){
        $whereStrTrip =' transport_trips.id!=0 ';
        if($request->driver_id!= ''){
            $whereStrTrip .= " AND transport_trips.driver_id =  '".$request->driver_id."'";
        }

        if($request->from_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
        }

        if($request->to_date!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
        }
        
        $tripData=$details = TransportTrips::
                with([
                     'getSelectedVehicle' => function ($query) {
                            $query->select('id','registration_no as vehicle_no');
                     },
                     'getSelectedProduct' => function ($query) {
                        $query->select('id','name as product');
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
                    ]
                    )
               ->whereRaw($whereStrTrip)
               ->get();
        
        echo json_encode($tripData);

    }//func close

    function driverTripReportUpdate(Request $request){
        $formData=[];
        parse_str($request->formData,$formData);
        if(isset($formData['tripData'])){
            $res=0;
            foreach($formData['tripData'] as $k => $row){
                if($row['driver_shortage']!=''){
                    $updateTripData=array('driver_shortage'=>$row['driver_shortage'],
                                          'driver_shortage_amt'=>$row['driver_shortage_amt'],
                                         );
                    TransportTrips::where('id',$row['trip_id'])->update($updateTripData);
                    $res=1;
                }
            }//loop close
            echo $res;
        }//if close       
        
    }//func close

    function driverTripReportPrint(Request $request){
        $whereStrTrip =' transport_trips.id!=0 ';
        if($request->driver_id!= ''){
            $whereStrTrip .= " AND transport_trips.driver_id =  '".$request->driver_id."'";
        }

        if($request->fromDate!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) >=  '".$request->fromDate."'";
        }

        if($request->toDate!= ''){
            $whereStrTrip .= " AND DATE(transport_trips.lr_date) <=  '".$request->toDate."'";
        }

        $tripData= TransportTrips::
                with([
                     'getSelectedVehicle' => function ($query) {
                            $query->select('id','registration_no as vehicle_no');
                     },
                     'getSelectedProduct' => function ($query) {
                        $query->select('id','name as product');
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
                    ]
                    )
               ->whereRaw($whereStrTrip)
               ->get();
        
        // $driverData = helperGetDriverDetailByID($request->driver_id);
        $viewData=['tripData'=>$tripData];   
        // $viewData['driverData']=$driverData; 
        
        return view('admin.reports.report_driver_trip_print',$viewData);       
        
    }

    //old

    // function transporterTripStatementPaginate(Request $request){
    //     if ($request->ajax()) {
            
    //         $wherestr = "transport_trips.id !='0' AND transport_trips.is_market_lr = 1 ";

    //         $login_user_role = Auth::user()->role_id;

    //         if($request->party_id!= ''){
    //             $wherestr .= " AND transport_trips.transporter_id =  '".$request->party_id."'";
    //         }

    //         if($request->vehicle_id!= ''){
    //             $wherestr .= " AND transport_trips.vehicle_id =  '".$request->vehicle_id."'";
    //         }

    //         if($request->route_id!= ''){
    //             $wherestr .= " AND transport_trips.route_id =  '".$request->route_id."'";
    //         }

    //         if($request->lr_no!= ''){
    //             $wherestr .= " AND transport_trips.lr_no =  '".$request->lr_no."'";
    //         }


    //         if($request->from_date!= ''){
    //             $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
    //         }

    //         if($request->to_date!= ''){
    //             $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
    //         }
    //         if($request->is_bill_pending!='' && $request->is_bill_pending == 'Yes'){
    //             $wherestr .= " AND transport_trips.transporter_bill_no IS NULL ";
    //         }

    //         if($request->is_bill_pending!='' && $request->is_bill_pending == 'No'){
    //             $wherestr .= " AND transport_trips.transporter_bill_no IS NOT NULL ";
    //         }

            
    //         $data=TransportTrips::whereRaw($wherestr);
    
    //         return Datatables::of($data)
    //                 ->addIndexColumn()
    //                 ->addColumn('action', function($row) {
    //                     $transBillDate=($row->transporter_bill_date!=null)?date('d/m/Y',strtotime($row->transporter_bill_date)):'';
    //                     if($row->transporter_bill_no != ''){
    //                         $btn = 'Bill No : '.$row->transporter_bill_no.' <br>'.
    //                                 'Bill Date : '.$transBillDate;
    //                     }else{
    //                         $btn = '<input type="checkbox" id="lr_'.$row->id.'" class="check_lr" 
    //                                        market_freight="'.$row->market_freight.'" value="'.$row->id.'"
    //                                     onchange="getCheckedMarketFreight('.$row->id.','.$row->market_freight.')"
    //                                 ><input type="hidden" name="checkedMarketFreight[]" id="checked_market_freight_'.$row->id.'" value="">';
    //                     }

    //                     return $btn;
                    
    //                 })->addColumn('lr_date', function($row) {
    //                   return isset($row->lr_date)?date('d/m/Y',strtotime($row->lr_date)):'';
    //                 })
                 
    //                 ->addColumn('transporter', function($row) {
    //                   return (isset($row->getTransporter))?$row->getTransporter->name:'';
    //                 })->addColumn('vehicle_no', function($row) {
    //                   if(isset($row->getVehicle)){
    //                     return $row->getVehicle->registration_no;
    //                   }else{
    //                     return '';
    //                   }
    //               })->addColumn('route_name', function($row) {
    //                   if(isset($row->getRoute)){
    //                     $routeData= $row->getRoute;
    //                     $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
    //                     $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
    //                     $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
    //                     return $RouteName;
    //                   }else{
    //                     return '';
    //                   }
    //               })
    //               ->rawColumns(['action','trip_detail'])
    //               ->make(true);
    //     }
    // }
    
    // function transporterTripBillDetailUpdate(Request $request){
    //     $all_trip_ids =  explode(',', $request->trip_ids);
        
    //     $file = $request->file('transporter_bill_doc');
    //     $uploadedFile=NULL;
    //     if($file){
    //         $fileName = time().'.'.$file->extension();  
    //         $file->move(public_path('uploads/transporter_bill_docs'), $fileName);
    //         $uploadedFile=$fileName;
    //     }

    //     foreach ($all_trip_ids as $key => $trip_id) {
    //         if($trip_id > 0){
    //            $updateData=array(
    //                 'transporter_bill_no'=>$_POST['transporter_bill_no'],
    //                 'transporter_bill_date'=>$_POST['transporter_bill_date'],
    //                 'transporter_bill_doc'=>$uploadedFile,
    //             );
    //             TransportTrips::where('id',$trip_id)->update($updateData);
    //         }
    //     }

    //     echo  1;
    // }
    

    // function transporterTripStatementExport(){

    //     $paramData=json_decode($_GET['data'],true);
    //     extract($paramData);

    //         $wherestr = "transport_trips.id !='0'  AND transport_trips.is_market_lr = 1 ";
            

    //         // if($request->service_request_type_id!= ''){
    //         //     $wherestr .= " AND service_request.service_request_type_id =  '".$request->service_request_type_id."'";
    //         // }

    //         $login_user_role = Auth::user()->role_id;
    //         // if($login_user_role == 12){
    //         //     $login_user_party = Auth::user()->party_id;
    //         //     $all_vehicles = Vehicles::where('party_id' ,'=' ,$login_user_party)->pluck('id')->toArray();
    //         //     $wherestr .= " AND transport_trips.vehicle_id  IN  (".implode(",",$all_vehicles).") ";
    //         // }

    //         if($party_id!= ''){
    //             $wherestr .= " AND transport_trips.transporter_id =  '".$party_id."'";
    //         }

    //         if($vehicle_id!= ''){
    //             $wherestr .= " AND transport_trips.vehicle_id =  '".$vehicle_id."'";
    //         }

    //         if($route_id!= ''){
    //             $wherestr .= " AND transport_trips.route_id =  '".$route_id."'";
    //         }

    //         if($lr_no!= ''){
    //             $wherestr .= " AND transport_trips.lr_no =  '".$lr_no."'";
    //         }

    //         if($from_date!= ''){
    //             $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$from_date."'";
    //         }

    //         if($to_date!= ''){
    //             $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$to_date."'";
    //         }
    //         if($is_bill_pending!='' && $is_bill_pending == 'Yes'){
    //             $wherestr .= " AND transport_trips.transporter_bill_no IS NULL ";
    //         }

    //         if($is_bill_pending!='' && $is_bill_pending == 'No'){
    //             $wherestr .= " AND transport_trips.transporter_bill_no IS NOT NULL ";
    //         }

    //         $data=TransportTrips::whereRaw($wherestr)->select('transport_trips.*')->get();
          
    //       $headers = array(
    //                         "Content-type" => "text/csv",
    //                         "Content-Disposition" => "attachment; filename=transport_trips.csv",
    //                         "Pragma" => "no-cache",
    //                         "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    //                         "Expires" => "0"
    //                     );


    //       $columns = array('sr', 'lr_no','transporter', 'vehicle','route', 'market_freight', 'transporter_bill_no', 'transporter_bill_date');

    //       $callback = function() use ($data, $columns){
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $columns);
    //         $sr=1;
    //         foreach($data as $row) {
    //             $route_name='';
    //             if(isset($row->getRoute)){
    //                 $routeData = $row->getRoute;
    //                 $route_name=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
    //                 $route_name.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
    //                 $route_name.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
    //             }

    //             fputcsv($file,array($sr,
    //                                 $row->lr_no,
    //                                 (isset($row->getTransporter))?$row->getTransporter->name:'',
    //                                 (isset($row->getVehicle))?$row->getVehicle->registration_no:'',
    //                                 $route_name,
    //                                 $row->market_freight,
    //                                 $row->transporter_bill_no,
    //                                 ($row->transporter_bill_date!=null)?date('d/m/Y',strtotime($row->transporter_bill_date)):'',
    //                             )
    //                     );
    //             $sr++;
    //         }
    //         fclose($file);
    //       };
    //     return Response::stream($callback, 200, $headers);
    // }

  
} //class close