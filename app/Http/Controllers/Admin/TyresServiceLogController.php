<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\TyreBrands;
use App\Models\Tyres;
use App\Models\TyreAssigns;
use App\Models\PositionVehicle;
use App\Models\Vehicles;
use App\Models\TyresServiceLog;
use App\Models\TyresServiceType;





class TyresServiceLogController extends Controller
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
        $viewData['TyresServiceLog']=TyresServiceLog::whereRaw('id')->get();

        return $viewData;
    }

    public function index(){
        $viewData=$this->commonViewData();
        $viewData['title']='Tyre Service Log';

        return view('admin.tyres_service_log.tyre_service_log_list',$viewData);
    }

    function tyresServiceLogPaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "tyre_service_log.id!= '' ";
            

            $data=TyresServiceLog::whereRaw($wherestr);   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                    $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TyreServiceLogModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==19;})->first();
                        $btn ='';
                        if(isset($TyreServiceLogModuleRights) && $TyreServiceLogModuleRights->is_edit==1){
                            $btn .= '<a href="'.route('tyre.service.log.edit',base64_encode($row['id'])).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($TyreServiceLogModuleRights) && $TyreServiceLogModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
               
            // $btn .= '&nbsp;&nbsp;<a href="'.route('report.party.transaction.detail',base64_encode($row['id'])).'" class="btn btn-secondary btn-sm"><i class="feather icon-grid"></i></a>';
            return $btn;
        })
            ->addColumn('tyre_id', function($row) {

                return (isset($row->getModelsTyres))?$row->getModelsTyres->serial_number :'';

            })
             ->addColumn('vehicle_id', function($row) {

                return (isset($row->getModelsVehicles))?$row->getModelsVehicles->registration_no:'';

            })
             ->addColumn('tyre_service_type_id', function($row) {

                return (isset($row->getModelsTyresServiceType))?$row->getModelsTyresServiceType->name :'';

            })
           
            
        //    ->addColumn('status', function($row) {
        //     if($row->status == '1'){
        //         $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
        //     }else{
        //         $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
        //     }
        //     return $html;
        // })->addColumn('created_by', function($row) {
        //     return (isset($row->getCreatedByDetail))?$row->getCreatedByDetail->name:'';
        // })->addColumn('ledger_type', function($row) {
        //     return (isset($row->getLedgerType))?$row->getLedgerType->name:'';
        // })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    function tyresServiceLogAdd(Request $request){
        if(!empty($request->all())){
            extract($_POST);

                $insertData = array();
                $insertData['tyre_id'] = $tyre_id;
                $insertData['vehicle_id'] = $vehicle_id;
                $insertData['tyre_service_type_id'] = $tyre_service_type_id;
                $insertData['service_amount'] = $service_amount;
                $insertData['service_date'] = $service_date;
                $insertData['remarks'] = $remarks;
                
              $user_id = TyresServiceLog::create($insertData);
            if($user_id!= ''){
                return redirect('/tyres-service-log-list')->with('success', 'Tyres Added successfully!');

            }else{
                return redirect('/tyres-service-log-list')->with('error', 'Please try again!');
            }
        }



        $viewData=$this->commonViewData();

        $viewData['Tyres']= Tyres::get();
        $viewData['TyresServiceType']= TyresServiceType::get();
        $viewData['Vehicles']= Vehicles::get();

        return view('admin.tyres_service_log.tyre_service_log_add', $viewData);
    }


    public function tyresServiceLogEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
                
                $updateData = array();
                $updateData['tyre_id'] = $tyre_id;
                $updateData['vehicle_id'] = $vehicle_id;
                $updateData['tyre_service_type_id'] = $tyre_service_type_id;
                $updateData['service_amount'] = $service_amount;
                $updateData['service_date'] = $service_date;
                $updateData['remarks'] = $remarks;

            $res=TyresServiceLog::where('id',$id)->update($updateData);

            if($res!= ''){
                return redirect('/tyres-service-log-list')->with('success', 'Tyres Updated successfully!');
            }else{
                return redirect('/tyres-service-log-list')->with('error', 'Please try again!');
            }

        }  //if close

        $editData = TyresServiceLog::leftJoin('vehicles','vehicles.id','=','tyre_service_log.vehicle_id')
                                    ->where('tyre_service_log.id',$id)
                                    ->select('tyre_service_log.*','vehicles.registration_no as vehicle_no')
                                    ->first();
        
        
        $viewData = array();
        $viewData['title']='EDIT Tyres';
        $viewData['editData']= $editData;
        $viewData['Tyres']= Tyres::get();
        $viewData['TyresServiceType']= TyresServiceType::get();
        $viewData['Vehicles']= Vehicles::get();


        return view('admin.tyres_service_log.tyre_service_log_add',$viewData);
    }
    
    public function deleteTyresServiceLog(Request $request) {
          
    $data  = TyresServiceLog::where('id',$request->input('id'))->delete();
    
    echo '1';
   
      }
      
      function getVehicleData(Request $request){
            
          
        $TyreAssigns = TyreAssigns::leftJoin('vehicles','vehicles.id','=','tyre_assigns.vehicle_id')->where('tyre_id',$request->tyre_id)->select('tyre_assigns.*','vehicles.registration_no as vehicle_no')->first();
            
        echo json_encode($TyreAssigns);
      }
    
}
