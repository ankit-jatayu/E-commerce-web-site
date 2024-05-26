<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Auth;
use DataTables;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Dues;

use App\Models\Vehicles;
use App\Models\VehicleModelCodes;
use App\Models\VehicleTypes;
use App\Models\VehicleDues;
use App\Models\VehicleDocuments;
use App\Models\VehicleRepairs;

use App\Models\Drivers;
use App\Models\DriverAllocateVehicles;

// use App\Models\CfsAllocateVehicles;
// use App\Models\CfsLists;
use App\Models\TransportTrips;
// use App\Models\TransportJobs;
// use App\Models\ServiceRequest;

use App\Models\Parties;
use App\Models\PartySelectedPartyTypes;
use App\Models\User;



class VehiclesController extends Controller
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
    
    function addVehicleDueTrackTab(){
        $tempID=(isset($_GET['id']) && $_GET['id']!='')?$_GET['id']:'';
        $id = base64_decode($tempID);

        $viewData=[];
        if($id!=''){
            $viewData['vehicle_detail'] = Vehicles::find($id);
            $viewData['dues'] = Dues::where('status',1)->get();
            $viewData['vehicle_dues'] = VehicleDues::leftJoin('due_types as dt', 'dt.id', '=', 'vehicle_dues.due_id')
                                                     ->where('vehicle_id',$id)
                                                     ->select('vehicle_dues.*','dt.name as due_type')
                                                     ->get();
        }
        
        return view('admin.vehicles.tab_vehicle_add_due_track',$viewData);

    }
    
    function addVehicleDocumentsTab(){
        $tempID=(isset($_GET['id']) && $_GET['id']!='')?$_GET['id']:'';
        $id = base64_decode($tempID);
        
        $viewData=[];
        if($id!=''){
            $viewData['vehicle_detail'] = Vehicles::find($id);
            $viewData['vehicle_doc'] = VehicleDocuments::where('vehicle_id',$id)->get();
        }
        return view('admin.vehicles.tab_vehicle_add_document',$viewData);
    }

    function addVehicleDriverDetailTab(){
        $tempID=(isset($_GET['id']) && $_GET['id']!='')?$_GET['id']:'';
        $id = base64_decode($tempID);
        
        $viewData=[];
        if($id!=''){
            $viewData['vehicle_detail'] = Vehicles::find($id);
            $viewData['driver_allocated'] = DriverAllocateVehicles::where('to_date',NULL)->where('vehicle_id','=',$id)->pluck('driver_id')->toArray();
           
            $all_allocate_driver = DriverAllocateVehicles::where('to_date',NULL)->where('vehicle_id','!=',$id)->pluck('driver_id')->toArray();
            $viewData['driver_list']=Drivers::whereNotIn('id', $all_allocate_driver)->get();
            $viewData['driver_allocated_list']=DriverAllocateVehicles::leftJoin('drivers as d', 'd.id', '=', 'driver_allocated_vehicles.driver_id')
                                                ->select('driver_allocated_vehicles.*','driver_allocated_vehicles.id as allocated_id','d.name as driver_name')
                                                ->where('vehicle_id', $id)
                                                ->orderby('driver_allocated_vehicles.id','desc')
                                                ->get();
        }
        return view('admin.vehicles.tab_vehicle_add_driver_detail',$viewData);

    } 

    public function allVehicleList(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = "id !='0' AND type != 'market' ";

            $login_user_role = Auth::user()->role_id;
            $login_user_party = Auth::user()->party_id;
            
            if($login_user_role == 12){
                $wherestr .= " AND party_id =  '".$login_user_party."'";
            }

            if($request->registration_no!=''){
                $wherestr .= " AND registration_no LIKE '%".$request->registration_no."%'";
            }

            if($request->equipment_vehicle!=''){
                $wherestr .= " AND equipment_vehicle =  '".$request->equipment_vehicle."'";
            }

            if($request->model_code!=''){
                $wherestr .= " AND model_code =  '".$request->model_code."'";
            }

            if($request->vehicle_type!=''){
                $wherestr .= " AND vehicle_type =  '".$request->vehicle_type."'";
            }

            $data=Vehicles::whereRaw($wherestr)->orderBy('id','DESC');
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                              $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $VehicleModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==14;})->first();
                        $btn ='';
                        if(isset($VehicleModuleRights) && $VehicleModuleRights->is_edit==1){
                           $btn .= '<a href="'.route('edit.vehicle',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                           return $btn;
                    })->addColumn('driver_detail', function($row) {
                            $driver_name = '-';
                            $driver_detail = $row->getLastestDriver;
                            if(!empty($driver_detail)){
                                $driver_name = $row->getLastestDriver->getDriverDetail->name . ' / '.$row->getLastestDriver->getDriverDetail->contact;
                            }
                            
                            return $driver_name;
                    })->addColumn('btn_toggel', function($row) {
                            if($row->status == '1'){
                                $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
                            }else{
                                $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
                            }
                            return $html;
                    })->rawColumns(['action','model_name','btn_toggel','driver_detail'])
                    ->make(true);
        }

        $viewData['VehicleModelCodes']=VehicleModelCodes::get();
        $viewData['VehicleTypes']=VehicleTypes::get();

        return view('admin.vehicles.vehicle_list',$viewData);
    }

    public function addVehicle(Request $request) {
        if(!empty($request->all())){
            
            $data = array();
            $data['registration_no'] = strtoupper($request->input('registration_no'));
            $data['party_id'] = $request->input('party_id');
            $data['vehicle_alias'] = $request->input('vehicle_alias');
            $data['registration_date'] = $request->input('registration_date');
            $data['model_code'] = $request->input('model_code');
            $data['rto_auth'] = $request->input('rto_auth');
            $data['chassis_no'] = $request->input('chassis_no');
            $data['engine_no'] = $request->input('engine_no');
            $data['manufacture_year'] = $request->input('manufacture_year');
            $data['manufacture_month'] = $request->input('manufacture_month');
            $data['purchase_date'] = $request->input('purchase_date');
            $data['purchase_amount'] = $request->input('purchase_amount');
            $data['sale_date'] = $request->input('sale_date');
            $data['sale_amount'] = $request->input('sale_amount');
            $data['gvw_in_kg'] = $request->input('gvw_in_kg');
            $data['ulw_in_kg'] = $request->input('ulw_in_kg');
            $data['vehicle_type'] = $request->input('vehicle_type');
            $data['stephanie'] = $request->input('stephanie');
            $data['type'] = $request->input('type');
            $data['fuel'] = $request->input('fuel');
            $data['remarks'] = $request->input('remarks');
            $data['f_t_type'] = $request->input('f_t_type');
            $data['f_total_tyre'] = $request->input('f_total_tyre');
            $data['b_t_type'] = $request->input('b_t_type');
            $data['b_total_tyre'] = $request->input('b_total_tyre');
            $data['f_size'] = $request->input('f_size');
            $data['b_size'] = $request->input('b_size');
            $data['equipment_vehicle'] = $request->input('equipment_vehicle');
            
            $vehicle_id = Vehicles::create($data);
            if($vehicle_id!= ''){
                return redirect('vehicle-list')->with('success', 'Vehicle Created successfully!');
            }else{
                return redirect('vehicle-list')->with('error', 'Please try again!');
            }
        }

       
        //party_type=3==Transporter,party_type=4==Vehicle Owner
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

        return view('admin.vehicles.tab_vehicle_add',$viewData);
    }

    public function editVehicle($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            
            $data = Vehicles::where('id',$id)->first();
            //$data->model_id = $request->input('model_id');
            $data->registration_no = strtoupper($request->input('registration_no'));
            $data->party_id = $request->input('party_id');
            $data->type = $request->input('type');
            $data->model_code = $request->input('model_code');
            $data->vehicle_alias = $request->input('vehicle_alias');
            $data->registration_date = $request->input('registration_date');
            $data->rto_auth = $request->input('rto_auth');
            $data->chassis_no = $request->input('chassis_no');
            $data->engine_no = $request->input('engine_no');
            $data->manufacture_year = $request->input('manufacture_year');
            $data->manufacture_month = $request->input('manufacture_month');
            $data->purchase_date = $request->input('purchase_date');
            $data->purchase_amount = $request->input('purchase_amount');
            $data->sale_date = $request->input('sale_date');
            $data->sale_amount = $request->input('sale_amount');
            $data->gvw_in_kg = $request->input('gvw_in_kg');
            $data->ulw_in_kg = $request->input('ulw_in_kg');
            $data->equipment_vehicle = $request->input('equipment_vehicle');
            $data->vehicle_type = $request->input('vehicle_type');
            $data->stephanie = $request->input('stephanie');
            $data->fuel = $request->input('fuel');
            $data->f_t_type = $request->input('f_t_type');
            $data->f_size = $request->input('f_size');
            $data->f_total_tyre = $request->input('f_total_tyre');
            $data->b_t_type = $request->input('b_t_type');
            $data->b_size = $request->input('b_size');
            $data->b_total_tyre = $request->input('b_total_tyre');
            $data->remarks = $request->input('remarks');
            
            $data->save();
            $vehicle_id = $data->id;
            if($vehicle_id!= ''){
                return redirect('vehicle-list')->with('success', 'Vehicle Detail Updated successfully!');
            }else{
                return redirect('vehicle-list')->with('error', 'Please try again!');
            }
        }
        //$viewData['model_list'] = Models::get();
        $viewData['vehicle_detail'] = Vehicles::where('id',$id)->first();
       
        //party_type=3==Transporter,party_type=4==Vehicle Owner
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
    
        return view('admin.vehicles.tab_vehicle_add',$viewData);
    }

    public function updateVehicleStatus(Request $request) {
        
        $data = Vehicles::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    public function addModel(Request $request) {
        $data = array();
        $data['name'] = $request->input('name');
        Models::create($data);

        $model_list = Models::get();
        echo json_encode($model_list,true);
    }


    function paginateVehicleDue(Request $request){
        if ($request->ajax()) {
            $wherestr = " id != '' ";

            if($request->vehicle_id!=''){
                $wherestr .= " AND vehicle_id =  '".$request->vehicle_id."'";
            }
            if($request->due_id!=''){
                $wherestr .= " AND due_id =  '".$request->due_id."'";
            }
            
            if($request->fromDt!=''){
                $wherestr .= " AND date(validity) >=  '".$request->fromDt."'";
            }

            if($request->toDt!=''){
                $wherestr .= " AND date(validity) <=  '".$request->toDt."'";
            }

            $data=VehicleDues::whereRaw($wherestr);
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('action', function($row) {
                    //        $btn = '<a href="'.route('edit.driver',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                    //        return $btn;
                    // })
                    ->addColumn('vehicle_id', function($row) {
                        return (isset($row->getVehicleDetail))?$row->getVehicleDetail->registration_no:'';
                    })->addColumn('due_id', function($row) {
                        return (isset($row->getDueDetail))?$row->getDueDetail->name:'';
                    })->addColumn('validity', function($row) {
                        return date('d-m-Y',strtotime($row->validity));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        $viewData['vehicles'] = Vehicles::where('status',1)->get();
        $viewData['dues'] = Dues::where('status',1)->get();

        return view('admin.vehicles.vehicle_doc_due_list',$viewData);
    }

    function exportVehicleDue(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = " id != '' ";
        if(isset($vehicle_id) && $vehicle_id!=''){
            $wherestr .= " AND vehicle_id =  '".$vehicle_id."'";
        }

        if(isset($due_id) && $due_id!=''){
            $wherestr .= " AND due_id =  '".$due_id."'";
        }

        if(isset($fromDt) && $fromDt!=''){
            $wherestr .= " AND date(validity) >=  '".$fromDt."'";
        }

        if(isset($toDt) && $toDt!=''){
            $wherestr .= " AND date(validity) <=  '".$toDt."'";
        }

        $data=VehicleDues::whereRaw($wherestr)->get();
      
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=vehicle_doc_dues.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

        $columns = array('sr', 'Vehicle No.','Document Due','validity');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    (isset($row->getVehicleDetail))?$row->getVehicleDetail->registration_no:'',
                                    (isset($row->getDueDetail))?$row->getDueDetail->name:'',
                                    date('d-m-Y',strtotime($row->validity)),
                                    )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
 
    }


    public function addVehicleDue(Request $request) {
        if(!empty($request->all())){
            
            $data = array();
            $data['vehicle_id'] = $request->input('vehicle_id');
            $data['due_id'] = $request->input('due_id');
            $data['validity'] = $request->input('validity');
            
            VehicleDues::create($data);
            $vehicle_id= base64_encode($request->input('vehicle_id'));
            if($vehicle_id!= ''){
                return redirect('vehicle-add-due-track-tab?id='.$vehicle_id)->with('success', 'Vehicle Doc Expiry created successfully!');
            }else{
                return redirect('vehicle-add-due-track-tab?id='.$vehicle_id)->with('error', 'Please try again!');
            }
        }
    }

    public function removeVehicleDue(Request $request) {
        $data = VehicleDues::where('id',$request->input('id'))->delete();
        echo '1';
    }

    public function addVehicleDoc(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['vehicle_id'] = $request->input('vehicle_id');
            $data['document_name'] = $request->input('document_name');
           
            // $document_file = $request->document_file;

            // $destinationPath = public_path().'/vehicle_doc';
            // $extension= $document_file->getClientOriginalExtension();
            // $originalName= $document_file->getClientOriginalName();
                
            // $filename = rand(9999,99999).$originalName;
            // $document_file->move($destinationPath, $filename); 
            
            $file = $request->document_file;
            $filename=time().'_'.$file->getClientOriginalName();
            $filePath = 'vehicle_doc/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            
            $data['document_file'] = $filename;
            VehicleDocuments::create($data);
            $vehicle_id= base64_encode($request->input('vehicle_id'));
            if($vehicle_id!= ''){
                return redirect('vehicle-add-documents-tab?id='.$vehicle_id)->with('success', 'Vehicle Document stored successfully!');
            }else{
                return redirect('vehicle-add-documents-tab?id='.$vehicle_id)->with('error', 'Please try again!');
            }
        }
    }

    public function removeVehicleDoc(Request $request) {
        $data = VehicleDocuments::where('id',$request->input('id'))->delete();
        echo '1';
    }

    public function addVehicleDriver(Request $request) {
        if(!empty($request->all())){
            
            $data = array();
            $data['vehicle_id'] = $request->input('vehicle_id');
            $data['driver_id'] = $request->input('driver_id');
            $data['from_date'] = $request->input('from_date');
            $data['to_date'] = $request->input('to_date');
            
            DriverAllocateVehicles::create($data);
            $vehicle_id= base64_encode($request->input('vehicle_id'));
            if($vehicle_id!= ''){
                return redirect()->back(); 

                // return redirect('vehicle-add-driver-tab?id='.$vehicle_id)->with('success', 'Driver added to vehicle successfully!');
            }else{
                return redirect()->back(); 
                // return redirect('vehicle-add-driver-tab?id='.$vehicle_id)->with('error', 'Please try again!');
            }
        }
    }

    public function updateVehicleDriver(Request $request) {
        if(!empty($request->all())){
            $data = DriverAllocateVehicles::where('id',$request->input('id'))->first();
            $data->driver_id = $request->input('driver_id');
            $data->vehicle_id = $request->input('vehicle_id');
            $data->from_date = $request->input('from_date');
            $data->to_date = $request->input('to_date');
            $data->save();
            echo 1;
        }
    }

    public function allVehicleListByStatus($status, Request $request) {
        if ($request->ajax()) {
            $status = $request->input('status');
            $vehicle_type = $request->input('vehicle_type');
            $vehicle_status = $request->input('vehicle_status');
            $registration_no = $request->input('registration_no');
            $wherestr = "id !='0' AND vehicle_type = '".$vehicle_type."' ";

            if($registration_no!=''){
                $wherestr .= " AND registration_no  LIKE '%".$registration_no."%' ";
            }

            $loginUserRole = Auth::user()->role_id;
            $loginUserPartyID = Auth::user()->party_id;
            if($loginUserRole == 12){
                $wherestr .= " AND  vehicles.party_id = ".$loginUserPartyID;
            }
            
            if($status == 'ALL'){
                if($vehicle_status!=''){
                    $wherestr .= " AND vehicle_status = '".$vehicle_status."' ";
                }

                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            }elseif ($status == 'CFS') {
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            }elseif ($status == 'JOB') {
                $wherestr .= " AND vehicle_status = 'On Job' ";
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            }elseif ($status == 'UNLOADING') {
                $wherestr .= " AND vehicle_status = 'Unloading' ";
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            }elseif ($status == 'HOLD') {
                $wherestr .= " AND vehicle_status = 'Hold' ";
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            }elseif ($status == 'NO DRIVER') {
                
                $alloted_driver_vehicle = DriverAllocateVehicles::where('to_date',NULL)->pluck('vehicle_id')->toArray();
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr)->where('vehicle_status','!=','Repair')->whereNotIn('id', $alloted_driver_vehicle);
            }elseif ($status == 'FREE') {
                $wherestr .= " AND vehicle_status = 'Available' ";
                $alloted_driver_vehicle = DriverAllocateVehicles::where('to_date',NULL)->pluck('vehicle_id')->toArray();
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr)->whereIn('id', $alloted_driver_vehicle);
            }elseif ($status == 'REPAIR') {
                $wherestr .= " AND vehicle_status = 'Repair' ";
                $data=Vehicles::select('vehicles.*',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = vehicles.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr)->orderby('registration_no','asc');
            }
            
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('gps', function($row) {
                           $btn = '<a href="'.route('vehicle.location',base64_encode($row->registration_no)).'" target="_blank">'.$row->registration_no.'</a>';

                           return $btn;
                    })->addColumn('change_status', function($row) use ($status) {
                            if($status == 'ALL'){
                                return '-';
                            }elseif ($status =='CFS') {
                                return '-';
                            }elseif ($status =='JOB') {
                                $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Unloading">Unloading</option><option value="Hold">Hold</option></select>';
                                return $html;
                            }elseif ($status =='UNLOADING') {
                                $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Available">Free</option><option value="Hold">Hold</option></select>';
                                return $html;
                            }elseif ($status =='HOLD') {
                                $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Available">Free</option><option value="On Job">On Job</option><option value="Unloading">Unloading</option><option value="Hold">Hold</option></select>';
                                return $html;
                            }elseif ($status =='FREE') {
                                $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Repair">Repair</option></select>';
                                return $html;
                            }elseif ($status =='REPAIR') {
                                $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Available">Free</option></select>';
                                return $html;
                            }
                            
                    })->addColumn('cfs_list', function($row) {
                            $all_cfs = CfsLists::get()->toArray();
                            $html = '<select class="form-control" id="cfs_'.$row->id.'" onchange = changeCfsStatus('.$row->id.')>'.
                                        '<option value="">Select CFS</option>';
                                        foreach ($all_cfs as $key => $value) {
                                            $select = '';
                                            if($value['id'] == $row['cfs_id']){
                                                $select = 'selected';
                                            }
                                            $html .= '<option value="'.$value['id'].'" '.$select.'>'.$value['cfs_name'].'</option>';
                                        }
                                        
                                    $html .='</select>';
                            
                        return $html;
                    })->addColumn('estimeted_time', function($row) use ($status) {
                        if($status == 'REPAIR'){
                            $vehicle_repair = VehicleRepairs::where('vehicle_id',$row->id)->where('end_date',NULL)->first();
                            return $vehicle_repair->estimeted_time;
                        }else {
                            return '';
                        }
                        
                    })->addColumn('time_diff', function($row) use ($status) {
                        if($status == 'REPAIR'){
                            $vehicle_repair = VehicleRepairs::where('vehicle_id',$row->id)->where('end_date',NULL)->first();

                            $dateDiff = intval((time()-strtotime($vehicle_repair->start_date))/60);
                            $hours = intval($dateDiff/60);
                            return $hours;
                            //return date('d-m-Y H:i', strtotime($vehicle_repair->start_date));
                        }else {
                            return '';
                        }
                    })->addColumn('estimeted_time_add', function($row) use ($status) {
                        if($status == 'FREE'){
                            //$btn = "<label>Estimeted time</label><input type='text' id='estimeted_time_".$row['id']."' class='form-control input-sm estimeted_time' data-id=".$row['id'].">";
                            $btn = "<button class='btn btn-primary' id='estimeted_time_".$row['id']."' onclick='openEstimatedTimeModal(".$row['id'].")'><i class='feather icon-plus-circle' ></i>Estimeted time</button>";
                            return $btn;
                        }else {
                            return '';
                        }
                    })->addColumn('job_detail', function($row) use ($status) {
                        if($status == 'JOB' || $status == 'UNLOADING'){
                            
                            $trip_data = TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                  ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                  ->select('transport_jobs.job_no')
                                  ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                  ->where('vehicle_id',$row['id'])
                                  ->where('end_trip_time','=',NULL)
                                  ->where('lr_status','=',1)
                                  ->first();
                            if(!empty($trip_data)){
                                $btn = "<label>Job No : </label><span>".$trip_data->job_no."</span>";
                            }else{
                                $btn = '-';
                            }
                            
                            return $btn;
                        }else {
                            return '';
                        }
                    })->addColumn('vehicle_status', function($row) use ($status) {
                        $str=$row->vehicle_status;
                        if($row->vehicle_status == 'On Job' || $row->vehicle_status == 'Unloading'){
                            
                            $trip_data = TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                  ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                  ->select('transport_jobs.job_no')
                                  ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                  ->where('transport_trips.vehicle_id',$row->id)
                                  ->whereNULL('transport_trips.end_trip_time')
                                  ->where('lr_status','=',1)
                                  ->first();
                            if(!empty($trip_data)){
                                $str .= " : ".$trip_data->job_no;
                            }
                        }elseif($row->vehicle_status == 'CFS'){
                            $responseData=CfsAllocateVehicles::leftJoin('cfs_lists', 'cfs_lists.id', '=', 'cfs_allocated_vehicles.cfs_id')
                                          ->where('cfs_allocated_vehicles.vehicle_id',$row->id)
                                          ->whereNULL('end_date')
                                          ->select('cfs_lists.cfs_name as cfs_name')
                                          ->first();
                            if(!empty($responseData)){
                                $str .= " : ".$responseData->cfs_name;
                            }              
                        }
                        return $str;
                    })->addColumn('followup_by', function($row) use ($status) {
                        if($status == 'REPAIR'){
                            $vehicle_repair = VehicleRepairs::where('vehicle_id',$row->id)->where('end_date',NULL)->first();
                            return (isset($vehicle_repair->getFollowupByDetail))?$vehicle_repair->getFollowupByDetail->name:'';
                        }else {
                            return '';
                        }
                        
                    })->addColumn('location', function($row) use ($status) {
                        if($status == 'REPAIR'){
                            $vehicle_repair = VehicleRepairs::where('vehicle_id',$row->id)->where('end_date',NULL)->first();
                            return $vehicle_repair->location;
                        }else {
                            return '';
                        }
                        
                    })->addColumn('complain', function($row) use ($status) {
                        if($status == 'REPAIR'){
                            $vehicle_repair = VehicleRepairs::where('vehicle_id',$row->id)->where('end_date',NULL)->first();
                            return $vehicle_repair->complain;
                        }else {
                            return '';
                        }
                        
                    })
                    ->rawColumns(['gps','change_status','cfs_list','estimeted_time_add','time_diff','estimeted_time','job_detail'])
                    ->make(true);
        }
        $viewData['status'] = $status;
        $viewData['users']=User::where('status',1)->where('is_repair_authorised','=',1)->get();
        return view('admin.vehicles.vehicle_list_by_status',$viewData);
    }

    public function updateVehicleTypeStatus(Request $request) {
        date_default_timezone_set('Asia/Kolkata');
        
        $vehicle_status = $request->input('vehicle_status');
        $vehicle_id = $request->input('id');
        $login_user_id = Auth::user()->id;
        //$singleTripData = TransportTrips::where('id',$transport_trip_id)->first();
        
        //$vehicle_id = $singleTripData->vehicle_id;
        
        $oldData = Vehicles::where('id',$vehicle_id)->first();
                                  
        if($oldData->vehicle_status != 'Repair' AND $vehicle_status == 'Repair'){
            $details = array();
            $details['vehicle_id'] = $vehicle_id;
            $details['complain'] = $request->input('complain');
            $details['estimeted_time'] = $request->input('estimeted_time');
            $details['followup_by'] = $request->input('followup_by');
            $details['location'] = $request->input('location');
            VehicleRepairs::insert($details);
        }

        if($oldData->vehicle_status == 'Repair' AND $vehicle_status != 'Repair'){
            $details = array();

            $details['end_date'] = date('Y-m-d H:i:s');

            $vehicle_repair = VehicleRepairs::where('vehicle_id',$vehicle_id)->where('end_date',NULL)->first();
            $vehicle_repair->end_date = date('Y-m-d H:i:s');
            $vehicle_repair->save();
        }

        if($oldData->vehicle_status != 'CFS' AND $vehicle_status == 'CFS'){
            $details = array();
            $details['vehicle_id'] = $vehicle_id;
            CfsAllocateVehicles::insert($details);
        }

        if($oldData->vehicle_status == 'CFS' AND $vehicle_status != 'CFS'){
            $details = array();
            $details['end_date'] = date('Y-m-d H:i:s');

            $vehicle_cfs = CfsAllocateVehicles::where('vehicle_id',$vehicle_id)->where('end_date',NULL)->first();
            $vehicle_cfs->end_date = date('Y-m-d H:i:s');
            $vehicle_cfs->save();
        }
        $singleTripData = TransportTrips::where('vehicle_id',$vehicle_id)->orderby('id','desc')->first();

        if($vehicle_status == 'Unloading'){
            $singleTripData->trip_unloading_by = $login_user_id;
            $singleTripData->save();
        }
        
        if($vehicle_status == 'Available'){
            
            if(!empty($singleTripData)){
                $allTripDataByJob=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                    ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                    ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                    ->leftJoin('vehicles as v', 'v.id', '=', 'transport_trips.vehicle_id')
                                    ->select('transport_job_id','sr.id as service_request_id','transport_trips.id as trip_id','srt.remaining_qty')
                                    ->where('transport_job_id',$singleTripData->transport_job_id)
                                    ->where('sr.request_status','In Progress')
                                    ->where('end_trip_time',NULL)
                                    ->get()
                                    ->toArray();
                
                if(count($allTripDataByJob)  == 1 && $allTripDataByJob[0]['remaining_qty'] == 0){
                    $service_request = ServiceRequest::where('id',$allTripDataByJob[0]['service_request_id'])->first();
                    $service_request->request_status = 'Completed';
                    $service_request->save();
                }

                $singleTripData->end_trip_time = date('Y-m-d H:i:s');
                $singleTripData->trip_end_by = $login_user_id;
                $singleTripData->save();
            }
        }
        
        $oldData->vehicle_status = $request->input('vehicle_status');
        $oldData->save();
        
        echo '1';
    }

    public function updateMarketVehicleTypeStatus(Request $request) {

        $vehicle_status = $request->input('vehicle_status');
        $vehicle_id = $request->input('id');
        $transport_trip_id = $request->input('trip_id');
        $login_user_id = Auth::user()->id;
        $singleTripData = TransportTrips::where('id',$transport_trip_id)->first();
        
        $vehicleData = Vehicles::where('id',$vehicle_id)->first();
                                  
        if($vehicle_status == 'Available'){
            
            if(!empty($singleTripData)){
                $allTripDataByJob=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                    ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                    ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                    ->leftJoin('vehicles as v', 'v.id', '=', 'transport_trips.vehicle_id')
                                    ->select('transport_job_id','sr.id as service_request_id','transport_trips.id as trip_id','srt.remaining_qty')
                                    ->where('transport_job_id',$singleTripData->transport_job_id)
                                    ->where('sr.request_status','In Progress')
                                    ->where('end_trip_time',NULL)
                                    ->get()
                                    ->toArray();
                
                if(count($allTripDataByJob)  == 1 && $allTripDataByJob[0]['remaining_qty'] == 0){
                    $service_request = ServiceRequest::where('id',$allTripDataByJob[0]['service_request_id'])->first();
                    $service_request->request_status = 'Completed';
                    $service_request->save();
                }

                $singleTripData->end_trip_time = date('Y-m-d H:i:s');
                $singleTripData->trip_end_by = $login_user_id;
                $singleTripData->save();
            }
        }
        
        $vehicleData->vehicle_status = $request->input('vehicle_status');
        $vehicleData->save();
        
        echo '1';
    }

    public function updateVehicleCFSStatus(Request $request) {
        $cfs_id = $request->input('cfs_id');
        $id = $request->input('id');

        $oldData = CfsAllocateVehicles::where('vehicle_id',$id)->where('end_date',NULL)->first();
        $vehicle_detail = Vehicles::where('id',$id)->first();

        if(!empty($oldData)){
            $details = array();
            $oldData->end_date = date('Y-m-d H:i:s');
            $oldData->save();
        }

        $details = array();
        $details['vehicle_id'] = $id;
        $details['cfs_id'] = $cfs_id;

        CfsAllocateVehicles::insert($details);
        $vehicle_detail->vehicle_status = 'CFS';
        $vehicle_detail->save();

        echo '1';
    }

    public function allVehicleListByCFS($status, Request $request) {
        if ($request->ajax()) {
            $cfs_id = $request->input('cfs_id');
            $vehicle_type = $request->input('vehicle_type');
            $registration_no = $request->input('registration_no');

            $wherestr = "id !='0' AND vehicle_type = '".$vehicle_type."' AND cfs_id = '".$cfs_id."' AND end_date is NULL";
            
            if($registration_no!=''){
                $wherestr .= " AND registration_no  LIKE '%".$registration_no."%' ";
            }

            $loginUserRole = Auth::user()->role_id;
            $loginUserPartyID = Auth::user()->party_id;
            if($loginUserRole == 12){
                $wherestr .= " AND v.party_id = ".$loginUserPartyID." ";
            }
            $data=CfsAllocateVehicles::leftJoin('vehicles as v', 'v.id', '=', 'cfs_allocated_vehicles.vehicle_id')
                                ->select('v.*','cfs_id',
                                        \DB::raw("(SELECT CONCAT(name,' / ',COALESCE(`contact`,'')) as driver_name FROM driver_allocated_vehicles dav LEFT JOIN drivers d ON d.id = dav.driver_id WHERE vehicle_id = v.id AND dav.to_date IS NULL ) as driver_name")
                                )->whereRaw($wherestr);
            
            
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                           $btn = '';
                           return $btn;
                    })->addColumn('change_status', function($row) {

                            $html = '<select class="form-control" id="status_'.$row->id.'" onchange = changeVehicleStatus('.$row->id.')><option value="">Select Vehicle Status</option><option value="Available">Free</option><option value="Repair">Repair</option></select>';
                            return $html;
                    })->addColumn('cfs_list', function($row) {
                            $all_cfs = CfsLists::get()->toArray();
                            $html = '<select class="form-control" id="cfs_'.$row->id.'" onchange = changeCfsStatus('.$row->id.')>'.
                                        '<option value="">Select CFS</option>';
                                        foreach ($all_cfs as $key => $value) {
                                            $select = '';
                                            if($value['id'] == $row['cfs_id']){
                                                $select = 'selected';
                                            }
                                            $html .= '<option value="'.$value['id'].'" '.$select.'>'.$value['cfs_name'].'</option>';
                                        }
                                        
                                    $html .='</select>';
                            
                        return $html;
                    })->rawColumns(['action','change_status','cfs_list'])
                    ->make(true);
        }
        $viewData['status'] = $status;
        return view('admin.vehicles.vehicle_list_by_cfs',$viewData);
    }

    public function allVehicleListByMarket(Request $request) {
        if ($request->ajax()) {
            $registration_no = $request->input('registration_no');

            $wherestr = " is_market_lr = 1 AND end_trip_time IS NULL ";
            if($registration_no!=''){
                $wherestr .= " AND registration_no  LIKE '%".$registration_no."%' ";
            }

            $data=TransportTrips::leftJoin('transport_jobs', 'transport_jobs.id', '=', 'transport_trips.transport_job_id')
                                          ->leftJoin('service_request as sr', 'sr.id', '=', 'transport_jobs.service_request_id')
                                          ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                          ->leftJoin('vehicles as v', 'v.id', '=', 'transport_trips.vehicle_id')
                                          ->leftjoin('party','party.id','v.party_id')
                                          ->select('v.registration_no','v.id as vehicle_id','party.name as transporter_party_name','transport_jobs.job_no','transport_trips.id as transport_trip_id')
                                          ->whereRaw($wherestr);
            
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                           $btn = '';
                           return $btn;
                    })->addColumn('change_status', function($row) {

                            $html = '<select class="form-control" id="status_'.$row->vehicle_id.'" onchange = changeVehicleStatus('.$row->vehicle_id.','.$row->transport_trip_id.')><option value="">Select Vehicle Status</option><option value="Available">Free</option></select>';
                            return $html;
                    })->rawColumns(['action','change_status'])
                    ->make(true);
        }
        return view('admin.vehicles.vehicle_list_by_market');
    }


    function exportVehicle(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = "id !='0' AND type != 'market'";

        if($registration_no!=''){
            $wherestr .= " AND registration_no =  '$registration_no'";
        }

        if($equipment_vehicle!=''){
            $wherestr .= " AND equipment_vehicle =  '".$equipment_vehicle."'";
        }

        if($model_code!=''){
            $wherestr .= " AND model_code =  '".$model_code."'";
        }

        if($vehicle_type!=''){
            $wherestr .= " AND vehicle_type =  '".$vehicle_type."'";
        }

        $data=Vehicles::whereRaw($wherestr)->get();
        
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=vehicles.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

        $columns = array('sr', 'Vehicle No','Driver', 'Model code','Vehicle Type');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                $driver_name = '-';
                $driver_detail = $value->getLastestDriver;
                if(!empty($driver_detail)){
                    $driver_name = $value->getLastestDriver->getDriverDetail->name . ' / '.$value->getLastestDriver->getDriverDetail->contact;
                }
                
                fputcsv($file,array($sr,
                                    $value->registration_no,
                                    $driver_name,
                                    $value->model_code,
                                    $value->vehicle_type,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
   
}