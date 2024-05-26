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





class TyresController extends Controller
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
        $viewData['Tyres']=Tyres::whereRaw('id')->get();

        return $viewData;
    }

    public function index(){
        $viewData=$this->commonViewData();
        $viewData['title']='Tyres';

        return view('admin.tyres.tyre_inventory_list',$viewData);
    }

    function tyresPaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "tyres.id!= '' ";
            

            $data=Tyres::whereRaw($wherestr);   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                  $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TyreModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==17;})->first();
                        $btn ='';
                        if(isset($TyreModuleRights) && $TyreModuleRights->is_edit==1){
                            $btn .= '<a href="'.route('tyres.edit',base64_encode($row['id'])).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($TyreModuleRights) && $TyreModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
            // $btn .= '&nbsp;&nbsp;<a href="'.route('report.party.transaction.detail',base64_encode($row['id'])).'" class="btn btn-secondary btn-sm"><i class="feather icon-grid"></i></a>';
            return $btn;
        })
            ->addColumn('tyre_brand_id', function($row) {

                return (isset($row->getSelectedTyreBrands))?$row->getSelectedTyreBrands->name:'';

            })
            ->addColumn('tyre_id', function($row) {

                return (isset($row->getSelectedTyreAssigns))?$row->getSelectedTyreAssigns->id :'';

            })
            ->addColumn('vehicle_id', function($row) {
                if(isset($row->getSelectedTyreAssigns))  {
                    $tyreassigndata = $row->getSelectedTyreAssigns;
                    $vehicle_detail = Vehicles::where('id',$tyreassigndata->vehicle_id)->first();
                    return $vehicle_detail->registration_no;

                } else{
                 return '';
             }  

         })
            ->addColumn('position_vehicle_id', function($row) {

             if(isset($row->getSelectedTyreAssigns))  {
                $tyreassigndata = $row->getSelectedTyreAssigns;
                $vehicle_detail = PositionVehicle::where('id',$tyreassigndata->position_vehicle_id)->first();
                return $vehicle_detail->name;

            } else{
             return '';
         }  

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

    function tyresAdd(Request $request){
        if(!empty($request->all())){
            extract($_POST);
            $insertData=array(
              'tyre_brand_id'=>$tyre_brand_id,
              'mode'=>$mode,
              'serial_number'=>$serial_number,
              'size'=>$size,
              'tread_pattern'=>$tread_pattern,
              'tread_depth'=>$tread_depth,
              'pressure'=>$pressure,
              'max_running_limit'=>$max_running_limit,
              'tyre_condition'=>$tyre_condition,
              'odo'=>$odo,
              'manufacturer_dt'=>$manufacturer_dt,
              'remarks'=>$remarks,
          );
            $res = Tyres::create($insertData);

            if($res){
                $tyre_id=$res->id;

                $start_date = date("Y-m-d"); 


                $insertData = array();
                $insertData['tyre_id'] = $tyre_id;
                $insertData['vehicle_id'] = $vehicle_id;
                $insertData['position_vehicle_id'] = $position_vehicle_id;
                $insertData['start_date'] = $start_date;
                if($mode == 'Vehicle'){
                TyreAssigns::create($insertData); 
                }
                return redirect('/tyres-list')->with('success', 'Tyres Added successfully!');

            }else{
                return redirect('/tyres-list')->with('error', 'Please try again!');
            }

          
        }


        $viewData=$this->commonViewData();
        $viewData['tyres_brand']= TyreBrands::get();
        $viewData['position_vehicle']= PositionVehicle::get();
        $viewData['Vehicles']= Vehicles::where(['status' => 1])->get();


        return view('admin.tyres.tyre_inventory_add', $viewData);
    }


    public function tyresEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);

            $updateData=array(
                'tyre_brand_id'=>$tyre_brand_id,
                'mode'=>$mode,
                'serial_number'=>$serial_number,
                'size'=>$size,
                'tread_pattern'=>$tread_pattern,
                'tread_depth'=>$tread_depth,
                'pressure'=>$pressure,
                'max_running_limit'=>$max_running_limit,
                'tyre_condition'=>$tyre_condition,
                'odo'=>$odo,
                'manufacturer_dt'=>$manufacturer_dt,
                'remarks'=>$remarks,
            );

            $res=Tyres::where('id',$id)->update($updateData);

            if($res!= ''){
                // $tyre_id=$id;

                // $start_date = date("Y-m-d"); 


                //         $updateData = array();
                //         $updateData['tyre_id'] = $tyre_id;
                //         $updateData['vehicle_id'] = $vehicle_id;
                //         $updateData['position_vehicle_id'] = $position_vehicle_id;
                //         $updateData['start_date'] = $start_date;

                //         TyreAssigns::create($updateData); 


                return redirect('/tyres-list')->with('success', 'Tyres Updated successfully!');
            }else{
                return redirect('/tyres-list')->with('error', 'Please try again!');
            }

        }  //if close
        $editData = Tyres::where('id',$id)->first();
        
        
        $viewData = array();
        $viewData['title']='EDIT Tyres';
        $viewData['editData']= $editData;

        $viewData['tyres_brand']= TyreBrands::get();
        $viewData['position_vehicle']= PositionVehicle::get();
        // $viewData['Vehicles']= Vehicles::get();


        return view('admin.tyres.tyre_inventory_edit',$viewData);
    }

    
    public function updateStatus(Request $request) {

        $data = Parties::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    
    public function deletetyres(Request $request) {
          
    $data  = Tyres::where('id',$request->input('id'))->delete();
    
    echo '1';
   
      }

    public function addPop(request $request){
        $data = array();
        $data['name'] = $request->input('name');

        TyreBrands::create($data);

        $TyreBrands = TyreBrands::get();

        echo json_encode(['TyreBrands' => $TyreBrands ],true);
    }
    
}
