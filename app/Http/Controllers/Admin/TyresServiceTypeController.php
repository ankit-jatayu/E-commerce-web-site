<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\TyresServiceType;





class TyresServiceTypeController extends Controller
{


    function TyresServicePaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "tyre_service_type.id!= '' ";
            

            $data=TyresServiceType::whereRaw($wherestr);   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                  $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TyresServiceTypeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==21;})->first();
                        $btn ='';
                        if(isset($TyresServiceTypeModuleRights) && $TyresServiceTypeModuleRights->is_edit==1){

                           
                            $btn .= '<a href="'.route('tyre.service.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($TyresServiceTypeModuleRights) && $TyresServiceTypeModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
             return $btn;
         })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.tyres_service_type.tyre_service_list');
    }

    public function tyresServiceAdd(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['name'] = $request->input('name');

            $user_id = TyresServiceType::create($data);
            if($user_id!= ''){
                return redirect('tyres-service-list')->with('success', ' Brand  Created successfully!');
            }else{
                return redirect('tyres-service-list')->with('error', 'Please try again!');
            }
        }

        $viewData=[];
        return view('admin.tyres_service_type.tyre_service_add',$viewData);
    }
    public function tyresServiceEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){

            $data = TyresServiceType::where('id',$id)->first();
            $data->name = $request->input('name');
            $data->save();
            $user_id = $data->id;

            if($user_id!= ''){
                return redirect('tyres-service-list')->with('success', 'Brand Detail Updated successfully!');
            }else{
                return redirect('tyres-service-list')->with('error', 'Please try again!');
            }
        }

        $viewData=[];
        $viewData['editData']=TyresServiceType::where('id',$id)->first();

        return view('admin.tyres_service_type.tyre_service_add',$viewData);
    }
    public function deleteTyresService(Request $request) {

        $data  = TyresServiceType::where('id',$request->input('id'))->delete();

        echo '1';

    }

    
}
