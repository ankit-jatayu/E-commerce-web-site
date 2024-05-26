<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\SalaryVouchers;
use App\Models\User;
use App\Models\Vehicles;
use App\Models\TransportTrips;
use App\Models\DriverAllocateVehicles;
use App\Models\Drivers;



class SalaryVouchersController extends Controller{
    
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
        $viewData['title']='SALARY VOUCHERS';
        $viewData['Vehicles']=Vehicles::get();
        return view('admin.salary_vouchers.salary_voucher_list',$viewData);
    }

    function paginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "salary_voucher.id !='0' AND salary_voucher.voucher_type = 'SalaryVoucher' ";
            
            if($request->from_date!= ''){
                $wherestr .= " AND DATE(salary_voucher.salary_voucher_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(salary_voucher.salary_voucher_date) <=  '".$request->to_date."'";
            }
            if($request->vehicle_id!= ''){
                $wherestr .= " AND salary_voucher.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->branch!= ''){
                $wherestr .= " AND salary_voucher.branch =  '".$request->branch."'";
            }
            
            $data=SalaryVouchers::whereRaw($wherestr);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                          $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $SalaryVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==15;})->first();
                        $btn ='';
                        if(isset($SalaryVchModuleRights) && $SalaryVchModuleRights->is_edit==1){   
                            $btn .= '<a href="'.route('salary.voucher.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($SalaryVchModuleRights) && $SalaryVchModuleRights->is_delete==1){
                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                        $btn .= '<a class="btn btn-warning btn-sm ml-1" onclick="printVoucher('.$row->id.')"><i class="feather icon-printer" style="color: white;"></i></a>';

                           return $btn;
                    })
                    ->addColumn('vehicle_id', function($row) {
                           if(isset($row->getVehicleDetail->registration_no)){
                            return $row->getVehicleDetail->registration_no;
                           }else{
                            return '';
                           }
                    })->addColumn('driver_id', function($row) {
                           if(isset($row->getDriverDetail->name)){
                            return $row->getDriverDetail->name;
                           }else{
                            return '';
                           }
                    })->addColumn('created_by', function($row) {
                           if(isset($row->getCreatedBy->name)){
                            return $row->getCreatedBy->name;
                           }else{
                            return '';
                           }
                    })->addColumn('salary_voucher_date', function($row) {
                          return date('d/m/Y',strtotime($row->salary_voucher_date)); 
                    })->addColumn('salary_voucher_from_date', function($row) {
                        return ($row->salary_voucher_from_date!=null)?date('d/m/Y',strtotime($row->salary_voucher_from_date)):'';
                    })->addColumn('salary_voucher_to_date', function($row) {
                        return ($row->salary_voucher_to_date!=null)?date('d/m/Y',strtotime($row->salary_voucher_to_date)):'';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    function addData(Request $request){
        if(!empty($request->all())){
            extract($_POST);
            $voucherNo=1;
            $lastData=SalaryVouchers::latest()->first();
            if(!empty($lastData)){
                $voucherNo=($lastData->salary_voucher_no+1);
            }

            $deduct_amount=($_POST['deduct_amount']!='')?$_POST['deduct_amount']:0;
            $payable_amount=($salary_amount-$deduct_amount);

            $voucherData=array(
                              'salary_voucher_no'=>$voucherNo,
                              'salary_voucher_from_date'=>$salary_voucher_from_date,
                              'salary_voucher_to_date'=>$salary_voucher_to_date,
                              'branch'=>$branch,
                              'vehicle_id'=>$vehicle_id,
                              'driver_id'=>$driver_id,
                              'salary_amount'=>$salary_amount,
                              'deduct_amount'=>$deduct_amount,
                              'payable_amount'=>$payable_amount,
                              'payment_type'=>$payment_type,
                              'salary_voucher_date'=>$salary_voucher_date,
                              'remarks'=>(isset($remarks))?$remarks:'',
                              'voucher_type'=>'SalaryVoucher',
                              'created_by'=>Auth::user()->id,
                            );
           
            $res = SalaryVouchers::insert($voucherData);
            if($res!= ''){
                return redirect('salary-voucher-list')->with('success', 'Salary voucher added successfully!');
            }else{
                return redirect('salary-voucher-list')->with('error', 'Please try again!');
            }
        }

        $viewData['title']="ADD SALARY VOUCHER";
        
        $vehicleWhereStr = " to_date IS NULL";
        $viewData['Vehicles']= DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($vehicleWhereStr)
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->get();
      
        $viewData['drivers']=Drivers::where('status',1)->get();
       
        return view('admin.salary_vouchers.salary_voucher_add',$viewData);
    }


    public function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            $deduct_amount=($_POST['deduct_amount']!='')?$_POST['deduct_amount']:0;
            $payable_amount=($salary_amount-$deduct_amount);
            
            $voucherData=array(
                              'salary_voucher_from_date'=>$salary_voucher_from_date,
                              'salary_voucher_to_date'=>$salary_voucher_to_date,
                              'branch'=>$branch,
                              'vehicle_id'=>$vehicle_id,
                              'driver_id'=>$driver_id,
                              'salary_amount'=>$salary_amount,
                              'deduct_amount'=>$deduct_amount,
                              'payable_amount'=>$payable_amount,
                              'payment_type'=>$payment_type,
                              'salary_voucher_date'=>$salary_voucher_date,
                              'remarks'=>(isset($remarks))?$remarks:'',
                              'voucher_type'=>'SalaryVoucher',
                            );
          
            
            $res=SalaryVouchers::where('id',$id)->update($voucherData);
            
            if($res!= ''){
                return redirect('salary-voucher-list')->with('success', 'Salary voucher Updated successfully!');
            }else{
                return redirect('salary-voucher-list')->with('error', 'Please try again!');
            }

        }  //if close

        $viewData['title']='EDIT SALARY VOUCHER';
        
        $viewData['editData'] = SalaryVouchers::find($id);

      
        $vehicleWhereStr = " (vehicle_status = 'Available' OR v.id = '".$viewData['editData']->vehicle_id. "' AND to_date IS NULL) ";
       
        $viewData['Vehicles']= DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($vehicleWhereStr)
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->get();
        $viewData['drivers']=Drivers::where('status',1)->get();
        
        return view('admin.salary_vouchers.salary_voucher_add',$viewData);
    }

    function deleteRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=SalaryVouchers::where('id',$id)->delete();
            if($response){
                return redirect('salary-voucher-list')->with('success', 'Salary voucher deleted successfully!');
            }else{
                return redirect('salary-voucher-list')->with('error', 'Please try again!');
            }
        }
    }
    

    function exportSalaryVoucher(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = "salary_voucher.id !='0' AND salary_voucher.voucher_type = 'SalaryVoucher' ";
            
        if($from_date!= ''){
            $wherestr .= " AND salary_voucher.salary_voucher_date >=  '".$from_date."'";
        }

        if($to_date!= ''){
            $wherestr .= " AND salary_voucher.salary_voucher_date <=  '".$to_date."'";
        }

        if($vehicle_id!= ''){
            $wherestr .= " AND salary_voucher.vehicle_id =  '".$vehicle_id."'";
        }

        if($branch!= ''){
            $wherestr .= " AND salary_voucher.branch =  '".$branch."'";
        }
        
        $data=SalaryVouchers::whereRaw($wherestr)->get();
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=salary_vouchers.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

          $columns = array('Sr', 'Voucher No.','Voucher From Dt','Voucher To. Dt','Branch','Vehicle','Driver', 'Salary Amt','Deduct Amt','Payable Amt','Payment Type', 'Date', 'Remarks','Created By','Voucher type');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    $row->salary_voucher_no,
                                    ($row->salary_voucher_from_date!=null)?date('d-m-Y',strtotime($row->salary_voucher_from_date)):'',
                                    ($row->salary_voucher_to_date!=null)?date('d-m-Y',strtotime($row->salary_voucher_to_date)):'',
                                    $row->branch,
                                    (isset($row->getVehicleDetail->registration_no))?$row->getVehicleDetail->registration_no:'',
                                    (isset($row->getDriverDetail->name))?$row->getDriverDetail->name:'',
                                    $row->salary_amount,
                                    $row->deduct_amount,
                                    $row->payable_amount,
                                    $row->payment_type,
                                    date('d/m/Y',strtotime($row->salary_voucher_date)),
                                    $row->remarks,
                                    (isset($row->getCreatedBy->name))?$row->getCreatedBy->name:'',
                                    $row->voucher_type,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    function printSalaryVoucher(){
        $id = $_GET['id'];
        $viewData['saleryVoucherData']=SalaryVouchers::find($id);

        return view('admin.salary_vouchers.salary_voucher_print',$viewData);
    }

     function transportTripPaginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "transport_trips.id != '' ";
            if($request->driver_id!= ''){
                $wherestr .= " AND transport_trips.driver_id =  '".$request->driver_id."'";
            }else{
                $wherestr .= " AND transport_trips.id =  '0'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$request->to_date."'";
            }
         
           $data=TransportTrips::whereRaw($wherestr)
                                  ->select(
                                    'transport_trips.*',
                                    // \DB::raw("
                                    //     DATE_FORMAT(transport_trips.pickup_date_time,'%d/%m/%Y %H:%i') as pickup_date,
                                    //     DATE_FORMAT(transport_trips.drop_date_time,'%d/%m/%Y %H:%i') as drop_date
                                    // ")
                                    )
                                  ->orderby('id','desc');
            // print_r('<pre>');
            // print_r($data->get()->toArray());
            // die();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('lr_date', function($row) {
                      return isset($row->lr_date)?date('d/m/Y',strtotime($row->lr_date)):'';
                    })->addColumn('party_name', function($row) {
                      return (isset($row->getBillingParty))?$row->getBillingParty->name:'';
                    })->addColumn('transporter_name', function($row) {
                      return (isset($row->getTransporter))?$row->getTransporter->name:'';
                    })->addColumn('driver_name', function($row) {
                      if(isset($row->getDriver->name)){
                        $driver_name=$row->getDriver->name;
                        $driver_name .= (isset($row->getDriver->contact))?' / '.$row->getDriver->contact:'';
                        return $driver_name;
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
                      if(isset($row->getRoute)){
                        $routeData= $row->getRoute;
                        $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                        $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                        $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                        return $RouteName;
                      }else{
                        return '';
                      }
                  })->rawColumns(['action'])
                    ->make(true);
        }
    }

    function transportTripExport(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

        $wherestr = "transport_trips.id != '' ";


        if($driver_id!= ''){
            $wherestr .= " AND transport_trips.driver_id =  '".$driver_id."'";
        }else{
                $wherestr .= " AND transport_trips.id =  '0'";
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
            "Content-Disposition" => "attachment; filename=driver_transport_trips.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );


        $columns = array('Sr','Date','Job No','Party','LR No','Truck No','Container No/Weight','Route','Driver Name','Driver Contact',);

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                $job_no=isset($row->getTransportJobs->job_no)?$row->getTransportJobs->job_no:'';

                if(isset($row->getTransportJobs->getServiceRequestDetail->getPartyDetail)){
                    $party_name= $row->getTransportJobs->getServiceRequestDetail->getPartyDetail->name;
                }else{
                    $party_name= '';
                }

                $driver_name = (isset($row->getDriver->name))?$row->getDriver->name:'';
                $driver_contact = (isset($row->getDriver->contact))?$row->getDriver->contact:'';

                $vehicle_no=(isset($row->getVehicle->registration_no))?$row->getVehicle->registration_no:'';
                $container_no='';
                if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                    $job_type=$row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->job_type;
                        if($job_type=='Container'){
                           $str='';
                           if($row->getContainer1){
                             $str=$row->getContainer1->container_no;
                           }
                           if($row->getContainer2){
                             $str.=','.$row->getContainer2->container_no;
                           } 
                           $container_no = $str;
                        }else{
                           $container_no = $row->weight;
                        }
                }

                $route_name='';
                if(isset($row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail)){
                    $routeData= $row->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail;
                    if($routeData->back_place!=''){
                      $route_name = $routeData->from_place.'-'.$routeData->to_place.'-'.$routeData->back_place;
                    }else{
                      $route_name = $routeData->from_place.'-'.$routeData->to_place;
                    }
                }  
                
                fputcsv($file,array($sr,
                    date('d-m-Y',strtotime($row->lr_date)),
                    $job_no,
                    $party_name,
                    $row->lr_no,
                    $vehicle_no,
                    $container_no,
                    $route_name,
                    $driver_name,
                    $driver_contact,
                )
            );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    function driverAllocatedVehiclePaginate(Request $request){
        if ($request->ajax()) {
            
        if($request->driver_id!= '' && $request->from_date!= '' && $request->to_date!= ''){
                $sqlQuery="SELECT *  FROM (
                                SELECT driver_allocated_vehicles.*,vehicles.registration_no as vehicle_no
                                 FROM `driver_allocated_vehicles` 
                                 LEFT JOIN vehicles ON vehicles.id=driver_allocated_vehicles.vehicle_id
                                WHERE `driver_id` = '".$request->driver_id."' AND ((`to_date` IS NULL AND from_date < '".$request->to_date."'))
                           ) t1
                           UNION
                           SELECT * FROM (
                                SELECT driver_allocated_vehicles.*,vehicles.registration_no as vehicle_no
                                 FROM `driver_allocated_vehicles` 
                                 LEFT JOIN vehicles ON vehicles.id=driver_allocated_vehicles.vehicle_id
                                WHERE `driver_id` = '".$request->driver_id."' AND ((`to_date` IS NOT NULL AND to_date > '".$request->from_date."'))
                           ) t2 ";
        }else{
            $sqlQuery ="SELECT *  FROM `driver_allocated_vehicles` WHERE id=0 ";
        }    

        $response = \DB::select($sqlQuery);
        $data=array();
        $filterFromDt = $request->from_date;
        $filterToDt = $request->to_date;
        if(!empty($response)){
            foreach($response as $k =>$row){
               $working_duration='';
               $working_days=0;
               //calc working_days
               $diff_from_date= '';
               
               if(strtotime($row->from_date) > strtotime($filterFromDt)){
                $diff_from_date=$row->from_date;
               }else{
                $diff_from_date=$filterFromDt;
               }

               $diff_to_date='';
               if((isset($row->to_date) && $row->to_date!=null)){
                 if(strtotime($row->to_date) < strtotime($filterToDt)){
                    $diff_to_date = $row->to_date;
                 }else{
                    $diff_to_date = $filterToDt;
                 }
               }else{
                    $diff_to_date = $filterToDt;
               }

               $working_duration = date('d/m/Y',strtotime($diff_from_date)).' - '.date('d/m/Y',strtotime($diff_to_date));
                
               $date1 = strtotime($diff_from_date);
               $date2 = strtotime($diff_to_date);
               $diff = ($date2 - $date1);
               $working_days = round($diff / 86400);

               //calc working_days
               
               $data[$k]=$row;
               $data[$k]->working_duration=$working_duration;
               $data[$k]->working_days=$working_days;
                
            } //loop close
        }
        
    
        echo json_encode($data);
        }  //ajax close   
    } //func close


    public function indexAdvanceSalaryVoucher(){
        $viewData['title']='ADVANCE SALARY VOUCHERS';
        $viewData['Vehicles']=Vehicles::get();
        return view('admin.advance_salary_vouchers.advance_salary_voucher_list',$viewData);
    }

    function paginateAdvanceSalaryVoucher(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "salary_voucher.id !='0' AND salary_voucher.voucher_type = 'AdvanceVoucher' ";
            
            if($request->from_date!= ''){
                $wherestr .= " AND DATE(salary_voucher.salary_voucher_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(salary_voucher.salary_voucher_date) <=  '".$request->to_date."'";
            }
            if($request->vehicle_id!= ''){
                $wherestr .= " AND salary_voucher.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->branch!= ''){
                $wherestr .= " AND salary_voucher.branch =  '".$request->branch."'";
            }
            
            $data=SalaryVouchers::whereRaw($wherestr);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                               $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $AdvanceSalaryVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==16;})->first();
                        $btn ='';
                        if(isset($AdvanceSalaryVchModuleRights) && $AdvanceSalaryVchModuleRights->is_edit==1){   
                        $btn = '<a href="'.route('advance.salary.voucher.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($AdvanceSalaryVchModuleRights) && $AdvanceSalaryVchModuleRights->is_delete==1){
     
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                            $btn .= '<a class="btn btn-warning btn-sm ml-1" onclick="printVoucher('.$row->id.')"><i class="feather icon-printer" style="color: white;"></i></a>';

                           return $btn;
                    })
                    ->addColumn('vehicle_id', function($row) {
                           if(isset($row->getVehicleDetail->registration_no)){
                            return $row->getVehicleDetail->registration_no;
                           }else{
                            return '';
                           }
                    })->addColumn('driver_id', function($row) {
                           if(isset($row->getDriverDetail->name)){
                            return $row->getDriverDetail->name;
                           }else{
                            return '';
                           }
                    })->addColumn('created_by', function($row) {
                           if(isset($row->getCreatedBy->name)){
                            return $row->getCreatedBy->name;
                           }else{
                            return '';
                           }
                    })->addColumn('salary_voucher_date', function($row) {
                          return date('d/m/Y',strtotime($row->salary_voucher_date)); 
                    })->addColumn('salary_voucher_from_date', function($row) {
                        return ($row->salary_voucher_from_date!=null)?date('d/m/Y',strtotime($row->salary_voucher_from_date)):'';
                    })->addColumn('salary_voucher_to_date', function($row) {
                        return ($row->salary_voucher_to_date!=null)?date('d/m/Y',strtotime($row->salary_voucher_to_date)):'';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    function addDataAdvanceSalaryVoucher(Request $request){
        if(!empty($request->all())){
            extract($_POST);
          
            $voucherNo=1;
            $lastData=SalaryVouchers::latest()->first();
            if(!empty($lastData)){
                $voucherNo=($lastData->salary_voucher_no+1);
            }
            $voucherData=array(
                              'salary_voucher_no'=>$voucherNo,
                              //'salary_voucher_from_date'=>$salary_voucher_from_date,
                              //'salary_voucher_to_date'=>$salary_voucher_to_date,
                              'branch'=>$branch,
                              'vehicle_id'=>$vehicle_id,
                              'driver_id'=>$driver_id,
                              'salary_amount'=>$salary_amount,
                              'deduct_amount'=>0,
                              'payable_amount'=>$salary_amount,
                              'payment_type'=>$payment_type,
                              'salary_voucher_date'=>$salary_voucher_date,
                              'remarks'=>(isset($remarks))?$remarks:'',
                              'voucher_type'=>'AdvanceVoucher',
                              'created_by'=>Auth::user()->id,
                            );
            
            $res = SalaryVouchers::insert($voucherData);
            if($res!= ''){
                return redirect('advance-salary-voucher-list')->with('success', 'Advance salary voucher added successfully!');
            }else{
                return redirect('advance-salary-voucher-list')->with('error', 'Please try again!');
            }
        }

        $viewData['title']="ADD ADVANCE SALARY VOUCHER";
        
        $vehicleWhereStr = " to_date IS NULL";
        $viewData['Vehicles']= DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($vehicleWhereStr)
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->get();
      
        $viewData['drivers']=Drivers::where('status',1)->get();
       
        return view('admin.advance_salary_vouchers.advance_salary_voucher_add',$viewData);
    }


    public function editDataAdvanceSalaryVoucher($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);

            $voucherData=array(
                              //'salary_voucher_from_date'=>$salary_voucher_from_date,
                              //'salary_voucher_to_date'=>$salary_voucher_to_date,
                              'branch'=>$branch,
                              'vehicle_id'=>$vehicle_id,
                              'driver_id'=>$driver_id,
                              'salary_amount'=>$salary_amount,
                              'deduct_amount'=>0,
                              'payable_amount'=>$salary_amount,
                              'payment_type'=>$payment_type,
                              'salary_voucher_date'=>$salary_voucher_date,
                              'remarks'=>(isset($remarks))?$remarks:'',
                              'voucher_type'=>'AdvanceVoucher',
                            );
          

            $res=SalaryVouchers::where('id',$id)->update($voucherData);
            
            if($res!= ''){
                return redirect('advance-salary-voucher-list')->with('success', 'Advance salary voucher Updated successfully!');
            }else{
                return redirect('advance-salary-voucher-list')->with('error', 'Please try again!');
            }

        }  //if close

        $viewData['title']='EDIT ADVANCE SALARY VOUCHER';
        
        $viewData['editData'] = SalaryVouchers::find($id);

      
        $vehicleWhereStr = " (vehicle_status = 'Available' OR v.id = '".$viewData['editData']->vehicle_id. "' AND to_date IS NULL) ";
       
        $viewData['Vehicles']= DriverAllocateVehicles::leftjoin('vehicles as v','driver_allocated_vehicles.vehicle_id','v.id')
                              ->leftjoin('party','party.id','v.party_id')
                              ->leftjoin('drivers as d','d.id','driver_allocated_vehicles.driver_id')
                              ->whereRaw($vehicleWhereStr)
                              ->select(
                                    'v.id',
                                    'v.registration_no',
                                    'v.vehicle_type',
                                    'party.id as transporter_party_id',
                                    'party.name as transporter_party_name',
                                    'd.id as driver_id',
                                    'd.name as driver_name',
                                )
                              ->get();
        $viewData['drivers']=Drivers::where('status',1)->get();
        
        return view('admin.advance_salary_vouchers.advance_salary_voucher_add',$viewData);
    }

    function deleteRecordAdvanceSalaryVoucher(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=SalaryVouchers::where('id',$id)->delete();
            if($response){
                return redirect('advance-salary-voucher-list')->with('success', 'Advance salary voucher deleted successfully!');
            }else{
                return redirect('advance-salary-voucher-list')->with('error', 'Please try again!');
            }
        }
    }
    

    function exportAdvanceSalaryVoucher(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = "salary_voucher.id !='0' AND salary_voucher.voucher_type = 'AdvanceVoucher' ";
            
        if($from_date!= ''){
            $wherestr .= " AND salary_voucher.salary_voucher_date >=  '".$from_date."'";
        }

        if($to_date!= ''){
            $wherestr .= " AND salary_voucher.salary_voucher_date <=  '".$to_date."'";
        }

        if($vehicle_id!= ''){
            $wherestr .= " AND salary_voucher.vehicle_id =  '".$vehicle_id."'";
        }

        if($branch!= ''){
            $wherestr .= " AND salary_voucher.branch =  '".$branch."'";
        }
        
        $data=SalaryVouchers::whereRaw($wherestr)->get();
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=advance_salary_vouchers.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

        $columns = array('Sr', 'Voucher No.','Branch','Vehicle','Driver', 'Salary Amt','Deduct Amt','Payable Amt','Payment Type', 'Date', 'Remarks','Created By','Voucher type');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    $row->salary_voucher_no,
                                    $row->branch,
                                    (isset($row->getVehicleDetail->registration_no))?$row->getVehicleDetail->registration_no:'',
                                    (isset($row->getDriverDetail->name))?$row->getDriverDetail->name:'',
                                    $row->salary_amount,
                                    $row->deduct_amount,
                                    $row->payable_amount,
                                    $row->payment_type,
                                    date('d/m/Y',strtotime($row->salary_voucher_date)),
                                    $row->remarks,
                                    (isset($row->getCreatedBy->name))?$row->getCreatedBy->name:'',
                                    $row->voucher_type,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    function printAdvanceSalaryVoucher(){
        $id = $_GET['id'];
        $viewData['saleryVoucherData']=SalaryVouchers::find($id);

        return view('admin.advance_salary_vouchers.advance_salary_voucher_print',$viewData);
    }

    
} //class close