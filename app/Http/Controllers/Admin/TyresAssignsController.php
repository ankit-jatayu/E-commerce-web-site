<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\TyreAssigns;
use App\Models\TyreBrands;
use App\Models\Tyres;
use App\Models\PositionVehicle;
use App\Models\Vehicles;





class TyresAssignsController extends Controller
{


    function tyresAssignsPaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "tyre_assigns.id!= '' ";
            

            $data=TyreAssigns::whereRaw($wherestr);   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                  $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TyreAsignsModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==18;})->first();
                        $btn ='';
                        if(isset($TyreAsignsModuleRights) && $TyreAsignsModuleRights->is_edit==1){

                          
                            $btn .= '<a href="'.route('tyres.assigns.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($TyreAsignsModuleRights) && $TyreAsignsModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
             return $btn;
         })
            ->addColumn('vehicle_id', function($row) {
                if(isset($row->getModelsVehicles))  {
                    $tyreassigndata = $row->getModelsVehicles;
                    return $tyreassigndata->registration_no;

                } else{
                 return '';
             }  

         })
            ->addColumn('position_vehicle_id', function($row) {

             if(isset($row->getModelsPositionVehicle))  {
                $tyreassigndata = $row->getModelsPositionVehicle;
                return $tyreassigndata->name;

            } else{
             return '';
         }  

    
     })
            ->addColumn('tyre_id', function($row) {

           return (isset($row->getModelsTyres))?$row->getModelsTyres->mode :'';

     })
            ->rawColumns(['action'])
            ->make(true);
        }
       
        return view('admin.tyres_assigns.tyre_assigns_list');
    }

    public function tyresAssignsAdd(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['tyre_id'] = $request->input('tyre_id');
            $data['vehicle_id'] = $request->input('vehicle_id');
            $data['position_vehicle_id'] = $request->input('position_vehicle_id');
            $data['start_date'] = $request->input('start_date');
            $data['end_date'] = $request->input('end_date');

            $user_id = TyreAssigns::create($data);
            if($user_id!= ''){
                return redirect('tyres-assigns-list')->with('success', ' Tyres Assign  Created successfully!');
            }else{
                return redirect('tyres-assigns-list')->with('error', 'Please try again!');
            }
        }

        $viewData = array();
        $viewData['Tyres']= Tyres::get();
        $viewData['position_vehicle']= PositionVehicle::get();
        $viewData['Vehicles']= Vehicles::where(['status' => 1 ])->get();

        return view('admin.tyres_assigns.tyre_assigns_add',$viewData);
    }
    public function tyresAssignsEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            $data = TyreAssigns::where('id',$id)->first();
            $data->tyre_id = $request->input('tyre_id');
            $data->vehicle_id = $request->input('vehicle_id');
            $data->position_vehicle_id = $request->input('position_vehicle_id');
            $data->start_date = $request->input('start_date');
            $data->end_date = $request->input('end_date');
            $data->save();
            $user_id = $data->id;

            if($user_id!= ''){
                return redirect('tyres-assigns-list')->with('success',' Tyres Assign Detail Updated successfully!');
            }else{
                return redirect('tyres-assigns-list')->with('error', 'Please try again!');
            }
        }
        $editData = TyreAssigns::where('id',$id)->first();
        $viewData=[];
        $viewData['editData']= $editData;
        $viewData['Tyres']= Tyres::where('id',$editData->tyre_id)->get();
        $viewData['position_vehicle']= PositionVehicle::where('id',$editData->position_vehicle_id)->get();
        $viewData['Vehicles']= Vehicles::where('id',$editData->vehicle_id)->get();

        return view('admin.tyres_assigns.tyre_assigns_add',$viewData);
    }
    public function deleteTyresAssigns(Request $request) {

        $data  = TyreAssigns::where('id',$request->input('id'))->delete();

        echo '1';

    }

    
}
