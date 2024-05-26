<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\TyreBrands;





class TyresBrandController extends Controller
{


    function TyresBrandPaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "tyre_brands.id!= '' ";
            

            $data=TyreBrands::whereRaw($wherestr);   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                 $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TyresBrandModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==20;})->first();
                        $btn ='';
                        if(isset($TyresBrandModuleRights) && $TyresBrandModuleRights->is_edit==1){
                           
                            $btn .= '<a href="'.route('tyre.brand.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';

                            } 
                        if(isset($TyresBrandModuleRights) && $TyresBrandModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
             return $btn;
         })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.tyre_brand.tyre_brand_list');
    }

    public function tyresBrandAdd(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['name'] = $request->input('name');

            $user_id = TyreBrands::create($data);
            if($user_id!= ''){
                return redirect('tyres-brand-list')->with('success', ' Brand  Created successfully!');
            }else{
                return redirect('tyres-brand-list')->with('error', 'Please try again!');
            }
        }

        $viewData=[];
        return view('admin.tyre_brand.tyre_brand_add',$viewData);
    }
    public function tyresBrandEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){

            $data = TyreBrands::where('id',$id)->first();
            $data->name = $request->input('name');
            $data->save();
            $user_id = $data->id;

            if($user_id!= ''){
                return redirect('tyres-brand-list')->with('success', 'Brand Detail Updated successfully!');
            }else{
                return redirect('tyres-brand-list')->with('error', 'Please try again!');
            }
        }

        $viewData=[];
        $viewData['editData']=TyreBrands::where('id',$id)->first();

        return view('admin.tyre_brand.tyre_brand_add',$viewData);
    }
    public function deleteTyresBrand(Request $request) {

        $data  = TyreBrands::where('id',$request->input('id'))->delete();

        echo '1';

    }

    
}
