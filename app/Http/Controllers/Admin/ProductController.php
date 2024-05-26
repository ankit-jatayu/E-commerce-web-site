<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DataTables;
use Auth;
use DB;
use Response;

use App\Models\Products;


class ProductController extends Controller
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
     * @return \Illuminate\Materials\Support\Renderable
     */

    public function index(){
        $viewData['title']='Product';
                                                           
        return view('admin.products.product_list',$viewData);
    }
    
    public function paginate(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " products.id != 0 ";          
            $data=Products::whereRaw($wherestr)->orderBy('products.id','DESC');
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $MaterialModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==23;})->first();
                        $btn ='';
                        if(isset($MaterialModuleRights) && $MaterialModuleRights->is_edit==1){
                            $btn .= '&nbsp;&nbsp;<a href="'.route('product.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                        } 
                        if(isset($MaterialModuleRights)&& $MaterialModuleRights->is_delete==1){
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord(' . $row->id . ')"><i class="feather icon-trash"></i></button>';
                        }     
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.products.product_list');
    }

    public function addData(Request $request) {
        if(!empty($request->all())){
            try{
                DB::beginTransaction();
                    $check=Products::where('name',$request->input('name'))->count();
                    if($check>0){
                        return redirect()->back()->with('error', 'Product already exist !! ');
                    }
                    $data = array();
                    $data['name'] = $request->input('name');
                    
                    $res = Products::create($data);
                    DB::commit();
                    if($res){
                        return redirect('product-list')->with('success', 'Product Created successfully!');
                    }
            }catch(Exception $e) {
                DB::rollback();
                 return redirect('product-list')->with('error', $e->errorInfo[2]);
            }  
        }       
        $viewData['title']='Product';
        return view('admin.products.product_add',$viewData);
    }

    public function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            try{
                DB::beginTransaction();
                    $check=Products::where('id','!=',$id)->where('name',$request->input('name'))->count();
                    if($check>0){
                        return redirect()->back()->with('error', 'Product already exist !! ');
                    }
                    
                    $data = Products::where('id',$id)->first();
                    $data['name'] = $request->input('name');
                    
                    $data->save();
                    $res = $data->id;
                DB::commit();
                if($res!= ''){
                    return redirect('product-list')->with('success', 'Product Detail Updated successfully!');
                }
            }catch(Exception $e) {
                DB::rollback();
                 return redirect('product-list')->with('error', $e->errorInfo[2]);
            }
            
        }
            $viewData=[];
            $viewData['editData'] = Products::find($id);
            $viewData['title']='Product';

        return view('admin.products.product_add',$viewData);
    }

    function deleteRecord(Request $request){
        $id = $request->input('id');
        if($id!=''){
            $response=Products::where('id',$id)->delete();
            if($response){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

}