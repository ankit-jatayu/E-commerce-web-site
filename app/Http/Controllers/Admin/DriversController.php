<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Storage;
use Illuminate\Http\Request;
use App\Models\Drivers;
use App\Models\DriverGuarantors;
use App\Models\DriverRelatives;
use App\Models\DriverDocs;
use App\Models\DriverAllocateVehicles;
use App\Models\DriverBanks;
use App\Models\DriverDues;
use App\Models\Vehicles;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Hash;


class DriversController extends Controller
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
    

    public function allDriverList(Request $request) {
        if ($request->ajax()) {
            $wherestr = " id != '' ";

            if($request->name!=''){
                $wherestr .= " AND name ='$request->name'";
            }

            $data=Drivers::whereRaw($wherestr)->orderBy('id','DESC');

            $user_id = Auth::user()->id;
            $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
            $DriverModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==4;})->first();
                    
            return Datatables::of($data,$DriverModuleRights)
                ->addIndexColumn()
                ->addColumn('action', function($row) use ($DriverModuleRights) {
                    $btn ='';
                    if(isset($DriverModuleRights) && $DriverModuleRights->is_edit==1){
                       $btn .= '<a href="'.route('edit.driver',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                   }
                   return $btn;
                })->addColumn('registration_no', function($row) {
                    $registration_no = '-';
                    $vehicle_detail = $row->getLastestVehicle;
                    if(!empty($vehicle_detail)){
                        $registration_no = $row->getLastestVehicle->getVehicleDetail->registration_no;
                    }

                    return $registration_no;
                })->addColumn('model_code', function($row) {
                    $model_code = '-';
                    $vehicle_detail = $row->getLastestVehicle;
                    if(!empty($vehicle_detail)){
                        $model_code = $row->getLastestVehicle->getVehicleDetail->model_code;
                    }

                    return $model_code;
                })->addColumn('vehicle_type', function($row) {
                    $vehicle_type = '-';
                    $vehicle_detail = $row->getLastestVehicle;
                    if(!empty($vehicle_detail)){
                        $vehicle_type = $row->getLastestVehicle->getVehicleDetail->vehicle_type;
                    }

                    return $vehicle_type;
                })->addColumn('btn_toggel', function($row) {
                    if($row->status == '1'){
                        $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
                    }else{
                        $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
                    }
                    return $html;
                })
                ->rawColumns(['action','btn_toggel','registration_no','model_code','vehicle_type'])
                ->make(true);
            }

        return view('admin.drivers.driver_list');
    }
    
    public function addDriver(Request $request) {
        if(!empty($request->all())){
            
            $data = array();
            $data['name'] = $request->input('name');
            $data['app_date'] = $request->input('app_date');
            $data['contact'] = $request->input('contact');
            $data['home_contact'] = $request->input('home_contact');
            $data['local_address'] = $request->input('local_address');
            $data['permanent_address'] = $request->input('permanent_address');

            if ($request->hasFile('driver_pic') && $request->file('driver_pic')->isValid()) {
                $file = $request->file('driver_pic');
                $fileName = time().'.'.$file->extension();
                $file->move(public_path('uploads/driver_pics'), $fileName);
                    $data['driver_pic'] = $fileName; // Store the file path in the database
                }

                // Save $data to the database
                $driver_id = Drivers::create($data)->id;
                if($driver_id!= ''){
                    $this->updateDriverPersonalDetail($request,$driver_id);
                    $this->updateDriverGuarantor($request,$driver_id);

                    return redirect('driver-list')->with('success', 'Driver Created successfully!');
                }else{
                    return redirect('driver-list')->with('error', 'Please try again!');
                }
            }
            return view('admin.drivers.tab_driver_add');
    }

    public function editDriver($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){

            $data = Drivers::where('id',$id)->first();
            $data->name = $request->input('name');
            $data->app_date = $request->input('app_date');
            $data->contact = $request->input('contact');
            $data->home_contact = $request->input('home_contact');
            $data->local_address = $request->input('local_address');
            $data->permanent_address = $request->input('permanent_address');


            if ($request->hasFile('driver_pic') && $request->file('driver_pic')->isValid()) {
                $file = $request->file('driver_pic');
                $fileName = time().'.'.$file->extension();
                $file->move(public_path('uploads/driver_pics'), $fileName);
                $data['driver_pic'] = $fileName; // Update the image field in $data
            }




            $data->save();
            $driver_id = $data->id;
            if($driver_id!= ''){
                $this->updateDriverPersonalDetail($request,$driver_id);
                $this->updateDriverGuarantor($request,$driver_id);

                return redirect('driver-list')->with('success', 'Driver Detail Updated successfully!');
            }else{
                return redirect('driver-list')->with('error', 'Please try again!');
            }
        }

        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_guarantors']=DriverGuarantors::where('driver_id', $id)->first();
        
        return view('admin.drivers.tab_driver_add',$viewData);
    }

    function updateDriverPersonalDetail($request,$driver_id){
        $driver_data = Drivers::where('id',$driver_id)->first();
        if(!empty($driver_data)){
            $driver_data->experience = $request->input('experience');
            $driver_data->blood_group = $request->input('blood_group');
            $driver_data->driver_dob = $request->input('driver_dob');
            $driver_data->Salary = $request->input('Salary');
            $driver_data->qualification = $request->input('qualification');

            $driver_data->save();
            return 1;
        }else{
            return 0;
        }

    }//func close

    function updateDriverGuarantor($request,$driver_id){
        $driver_guarantors_data = DriverGuarantors::where('driver_id',$driver_id)->first();
        $res=0;
        if(!empty($driver_guarantors_data)){
            $driver_guarantors_data->guarentor1 = $request->input('guarentor1');
            $driver_guarantors_data->guarentor1_phone_no = $request->input('guarentor1_phone_no');
            $driver_guarantors_data->guarentor1_address = $request->input('guarentor1_address');
            $driver_guarantors_data->guarentor2 = $request->input('guarentor2');
            $driver_guarantors_data->guarentor2_phone_no = $request->input('guarentor2_phone_no');
            $driver_guarantors_data->guarentor2_address = $request->input('guarentor2_address');
            $driver_guarantors_data->save();
            $res=1;

        }else{
            $data = array();
            $data['driver_id'] = $driver_id;
            $data['guarentor1'] = $request->input('guarentor1');
            $data['guarentor1_phone_no'] = $request->input('guarentor1_phone_no');
            $data['guarentor1_address'] = $request->input('guarentor1_address');
            $data['guarentor2'] = $request->input('guarentor2');
            $data['guarentor2_phone_no'] = $request->input('guarentor2_phone_no');
            $data['guarentor2_address'] = $request->input('guarentor2_address');

            DriverGuarantors::create($data);
            $res=1;
        }
        return $res;
    }//func close

    
    function paginateDriverDue(Request $request){
        if ($request->ajax()) {
            $wherestr = " id != '' ";

            if($request->driver_id!=''){
                $wherestr .= " AND driver_id =  '".$request->driver_id."'";
            }
            if($request->due_name!=''){
                $wherestr .= " AND due_name =  '".$request->due_name."'";
            }
            if($request->fromDt!=''){
                $wherestr .= " AND date(validity) >=  '".$request->fromDt."'";
            }

            if($request->toDt!=''){
                $wherestr .= " AND date(validity) <=  '".$request->toDt."'";
            }

            $data=DriverDues::whereRaw($wherestr);

            return Datatables::of($data)
            ->addIndexColumn()
                    // ->addColumn('action', function($row) {
                    //        $btn = '<a href="'.route('edit.driver',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                    //        return $btn;
                    // })
            ->addColumn('driver_id', function($row) {
                return (isset($row->getDriverDetail))?$row->getDriverDetail->name:'';
            })->addColumn('validity', function($row) {
                return date('d-m-Y',strtotime($row->validity));
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        $viewData['drivers']=Drivers::where('status',1)->get();
        return view('admin.drivers.driver_doc_due_list',$viewData);
    }

    function exportDriverDue(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

        $wherestr = " id != '' ";
        if(isset($driver_id) && $driver_id!=''){
            $wherestr .= " AND driver_id =  '".$driver_id."'";
        }
        if(isset($due_name) && $due_name!=''){
            $wherestr .= " AND due_name =  '".$due_name."'";
        }

        if(isset($fromDt) && $fromDt!=''){
            $wherestr .= " AND date(validity) >=  '".$fromDt."'";
        }

        if(isset($toDt) && $toDt!=''){
            $wherestr .= " AND date(validity) <=  '".$toDt."'";
        }

        $data=DriverDues::whereRaw($wherestr)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=driver_doc_dues.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('sr', 'Driver','Document Due','validity');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                    (isset($row->getDriverDetail))?$row->getDriverDetail->name:'',
                    $row->due_name,
                    date('d-m-Y',strtotime($row->validity)),
                )
            );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);

    }

    function addDriverPersonalDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        return view('admin.drivers.tab_driver_personal_detail_add',$viewData);
    }

    function addDriverRelativeDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_relative_list']=DriverRelatives::where('driver_id', $id)->get();
        return view('admin.drivers.tab_driver_relative_detail_add',$viewData);
    }
    function addDriverDueDocDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_due']=DriverDues::where('driver_id', $id)->get();
        return view('admin.drivers.tab_driver_duedoc_detail_add',$viewData);
    }

    function addDriverDocDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_doc']=DriverDocs::where('driver_id', $id)->get();
        return view('admin.drivers.tab_driver_doc_detail_add',$viewData);
    }

    function addDriverGuarantorDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_guarantors']=DriverGuarantors::where('driver_id', $id)->first();
        return view('admin.drivers.tab_driver_guarantor_detail_add',$viewData);
    }

    function addDriverBankDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();
        $viewData['driver_bank']=DriverBanks::where('driver_id', $id)->first();
        return view('admin.drivers.tab_driver_bank_detail_add',$viewData);
    }

    function addDriverVehicleDetailTab($id, Request $request){
        $id = base64_decode($id);
        $viewData['driver_detail'] = Drivers::where('id',$id)->first();

        $all_allocate_vehicle = DriverAllocateVehicles::where('to_date',NULL)
        ->where('driver_id','!=',$id)
        ->pluck('vehicle_id')->toArray();
        

        $viewData['vehicle_list']=Vehicles::whereNotIn('id', $all_allocate_vehicle)
        ->where('type','!=','market')->get();

        $viewData['vehicle_allocated_list']=DriverAllocateVehicles::
        leftJoin('vehicles as v', 'v.id', '=', 'driver_allocated_vehicles.vehicle_id')
        ->select('driver_allocated_vehicles.*',
         'driver_allocated_vehicles.id as allocated_id',
         'v.registration_no as registration_no'
     )
        ->where('driver_id', $id)
        ->orderby('driver_allocated_vehicles.id','desc')
        ->get();

        $viewData['vehicle_allocated'] = DriverAllocateVehicles::where('to_date',NULL)->where('driver_id','=',$id)
        ->pluck('vehicle_id')->toArray();

        return view('admin.drivers.tab_driver_vehicle_detail_add',$viewData);
    }

    public function updateDriverstatus(Request $request) {

        $data = Drivers::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';

        $file = public_path('file/test.csv');
        $customerArr = $this->csvToArray(public_path('/uploads/'.$filename));
    }

    public function addDriverRelative(Request $request) {
        if(!empty($request->all())){

            $data = array();
            $data['driver_id'] = $request->input('driver_id');
            $data['relation'] = $request->input('relation');
            $data['name'] = $request->input('name');
            $data['phone_no'] = $request->input('phone_no');
            
            DriverRelatives::create($data);
            $driver_id= base64_encode($request->input('driver_id'));
            if($driver_id!= ''){
                return redirect('driver-add-relative-detail-tab/'.$driver_id)->with('success', 'Driver Relative added successfully!');
            }else{
                return redirect('driver-add-relative-detail-tab/'.$driver_id)->with('error', 'Please try again!');
            }
        }
    }

    public function removeDriverRelative(Request $request) {
        $data = DriverRelatives::where('id',$request->input('id'))->delete();
        echo '1';
    }

    // public function updateDriverPersonalDetail(Request $request) {
    //     if(!empty($request->all())){
    //         $driver_data = Drivers::where('id',$request->input('driver_id'))->first();
            
    //         if(!empty($driver_data)){
    //             $driver_data->experience = $request->input('experience');
    //             $driver_data->blood_group = $request->input('blood_group');
    //             $driver_data->driver_dob = $request->input('driver_dob');
    //             $driver_data->Salary = $request->input('Salary');
    //             $driver_data->qualification = $request->input('qualification');
                
    //             $driver_data->save();
    //         }
            
    //         $driver_id= base64_encode($request->input('driver_id'));
    //         if($driver_id!= ''){
    //             return redirect('driver-add-personal-detail-tab/'.$driver_id)->with('success', 'Driver Personal detail updated successfully!');
    //         }else{
    //             return redirect('driver-add-personal-detail-tab/'.$driver_id)->with('error', 'Please try again!');
    //         }
    //     }
    // }

    public function updateDriverBank(Request $request) {
        if(!empty($request->all())){
            $driver_bank_data = DriverBanks::where('driver_id',$request->input('driver_id'))->first();

            if(!empty($driver_bank_data)){
                $driver_bank_data->bank_name = $request->input('bank_name');
                $driver_bank_data->ac_no = $request->input('ac_no');
                $driver_bank_data->state = $request->input('state');
                $driver_bank_data->place = $request->input('place');
                $driver_bank_data->ifsc = $request->input('ifsc');
                $driver_bank_data->micr = $request->input('micr');
                $driver_bank_data->save();
            }else{
                $data = array();
                $data['driver_id'] = $request->input('driver_id');
                $data['bank_name'] = $request->input('bank_name');
                $data['ac_no'] = $request->input('ac_no');
                $data['state'] = $request->input('state');
                $data['place'] = $request->input('place');
                $data['ifsc'] = $request->input('ifsc');
                $data['micr'] = $request->input('micr');
                
                DriverBanks::create($data);
            }

            $driver_id= base64_encode($request->input('driver_id'));
            if($driver_id!= ''){
                return redirect('driver-add-bank-detail-tab/'.$driver_id)->with('success', 'Driver Relative added successfully!');
            }else{
                return redirect('driver-add-bank-detail-tab/'.$driver_id)->with('error', 'Please try again!');
            }
        }
    }

    // public function updateDriverGuarantor(Request $request) {
    //     if(!empty($request->all())){
    //         $driver_guarantors_data = DriverGuarantors::where('driver_id',$request->input('driver_id'))->first();
            
    //         if(!empty($driver_guarantors_data)){
    //             $driver_guarantors_data->guarentor1 = $request->input('guarentor1');
    //             $driver_guarantors_data->guarentor1_phone_no = $request->input('guarentor1_phone_no');
    //             $driver_guarantors_data->guarentor1_address = $request->input('guarentor1_address');
    //             $driver_guarantors_data->guarentor2 = $request->input('guarentor2');
    //             $driver_guarantors_data->guarentor2_phone_no = $request->input('guarentor2_phone_no');
    //             $driver_guarantors_data->guarentor2_address = $request->input('guarentor2_address');
    //             $driver_guarantors_data->save();
    //         }else{
    //             $data = array();
    //             $data['driver_id'] = $request->input('driver_id');
    //             $data['guarentor1'] = $request->input('guarentor1');
    //             $data['guarentor1_phone_no'] = $request->input('guarentor1_phone_no');
    //             $data['guarentor1_address'] = $request->input('guarentor1_address');
    //             $data['guarentor2'] = $request->input('guarentor2');
    //             $data['guarentor2_phone_no'] = $request->input('guarentor2_phone_no');
    //             $data['guarentor2_address'] = $request->input('guarentor2_address');
                
    //             DriverGuarantors::create($data);
    //         }
            
    //         $driver_id= base64_encode($request->input('driver_id'));
    //         if($driver_id!= ''){
    //             return redirect('driver-add-guarantor-detail-tab/'.$driver_id)->with('success', 'Driver Relative added successfully!');
    //         }else{
    //             return redirect('driver-add-guarantor-detail-tab/'.$driver_id)->with('error', 'Please try again!');
    //         }
    //     }
    // }

    public function addDriverDue(Request $request) {
        if(!empty($request->all())){

            $data = array();
            $data['driver_id'] = $request->input('driver_id');
            $data['due_name'] = $request->input('due_name');
            $data['validity'] = $request->input('validity');
            
            DriverDues::create($data);
            $driver_id= base64_encode($request->input('driver_id'));
            if($driver_id!= ''){
                return redirect('driver-add-duedoc-detail-tab/'.$driver_id)->with('success', 'Driver Doc Expiry created successfully!');
            }else{
                return redirect('driver-add-duedoc-detail-tab/'.$driver_id)->with('error', 'Please try again!');
            }
        }
    }

    public function removeDriverDue(Request $request) {
        $data = DriverDues::where('id',$request->input('id'))->delete();
        echo '1';
    }

    public function addDriverDoc(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['driver_id'] = $request->input('driver_id');
            $data['file_name'] = $request->input('file_name');

            // $file = $request->file;
            // $destinationPath = public_path().'/driver_doc';
            // $extension= $file->getClientOriginalExtension();
            // $originalName= $file->getClientOriginalName();

            // $filename = rand(9999,99999).$originalName;
            // $file->move($destinationPath, $filename); 
            if($request->file){
                $file = $request->file;
                $filename=time().'_'.$file->getClientOriginalName();
                $filePath = 'driver_doc/' . $filename;
                Storage::disk('s3')->put($filePath, file_get_contents($file));    
                $data['file'] = $filename;
            }
            
            DriverDocs::create($data);
            $driver_id= base64_encode($request->input('driver_id'));
            if($driver_id!= ''){
                return redirect('driver-add-doc-detail-tab/'.$driver_id)->with('success', 'Driver Document stored successfully!');
            }else{
                return redirect('driver-add-doc-detail-tab/'.$driver_id)->with('error', 'Please try again!');
            }
        }
    }

    public function removeDriverDoc(Request $request) {
        $data = DriverDocs::where('id',$request->input('id'))->delete();
        echo '1';
    }

    function exportDriver(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = " id != '' ";
        if($name!=''){
            $wherestr .= " AND name =  '$name'";
        }

        $data=Drivers::whereRaw($wherestr)->get();
        
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivers.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('sr', 'Driver','Contact','Vehicle No', 'Model code','Vehicle Type');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                $registration_no = '-';
                $model_code = '-';
                $vehicle_type = '-';
                $vehicle_detail = $value->getLastestVehicle;
                if(!empty($vehicle_detail)){
                    $registration_no = $value->getLastestVehicle->getVehicleDetail->registration_no;
                    $model_code = $value->getLastestVehicle->getVehicleDetail->model_code;
                    $vehicle_type = $value->getLastestVehicle->getVehicleDetail->vehicle_type;
                }
                
                fputcsv($file,array($sr,
                    $value->name,
                    $value->contact,
                    $registration_no,
                    $model_code,
                    $vehicle_type,

                )
            );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}