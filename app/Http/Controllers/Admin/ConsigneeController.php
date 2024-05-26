<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Models\Consignees; // Changed from Cities to Consignees


class ConsigneeController extends Controller // Changed from CityController to ConsigneeController
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
     * @return \Illuminate\Consignees\Support\Renderable // Changed from Citys to Consignees
     */

    public function index(){
        $viewData['title']='Consignee'; // Changed from City to Consignee
                                                           
        return view('admin.consignees.consignee_list',$viewData); // Changed from city_list to consignee_list
    }
    
    public function paginate(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " consignees.id != 0 "; // Changed from cities to consignees
              
            $data=Consignees::get(); // Changed from Cities to Consignees
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $ConsigneeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==22;})->first(); // Changed from CityModuleRights to ConsigneeModuleRights
                        $btn ='';
                        if(isset($ConsigneeModuleRights) && $ConsigneeModuleRights->is_edit==1){ // Changed from CityModuleRights to ConsigneeModuleRights
                            $btn .= '&nbsp;&nbsp;<a href="'.route('consignee.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                        } 
                        if(isset($ConsigneeModuleRights)&& $ConsigneeModuleRights->is_delete==1){ // Changed from CityModuleRights to ConsigneeModuleRights
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord(' . $row->id . ')"><i class="feather icon-trash"></i></button>';
                        }     
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.consignees.consignee_list'); // Changed from city_list to consignee_list
    }

    public function addData(Request $request) {
        if(!empty($request->all())){
            try{
                DB::beginTransaction();
                    $data = array();
                    $data['company_name'] = $request->input('company_name');
                    $data['gst_no'] = $request->input('gst_no');
                    $data['address'] = $request->input('address');
                    
                    $res = Consignees::create($data); // Changed from Cities to Consignees
                DB::commit();
                if($res){
                    return redirect('consignee-list')->with('success', 'Consignee Created successfully!'); // Changed from city-list to consignee-list
                }
            }catch(Exception $e) {
                DB::rollback();
                 return redirect('consignee-list')->with('error', $e->errorInfo[2]); // Changed from city-list to consignee-list
            }  
        }       
        $viewData['title']='Consignee'; // Changed from City to Consignee
        return view('admin.consignees.consignee_add',$viewData); // Changed from city_add to consignee_add
    }

    public function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            try{
                DB::beginTransaction();
                    $data = Consignees::where('id',$id)->first(); // Changed from Cities to Consignees
                    $data['company_name'] = $request->input('company_name');
                    $data['gst_no'] = $request->input('gst_no');
                    $data['address'] = $request->input('address');
                    $data->save();
                    $res = $data->id;
                DB::commit();
                if($res!= ''){
                    return redirect('consignee-list')->with('success', 'Consignee Detail Updated successfully!'); // Changed from city-list to consignee-list
                }
            }catch(Exception $e) {
                DB::rollback();
                 return redirect('consignee-list')->with('error', $e->errorInfo[2]); // Changed from city-list to consignee-list
            }
            
        }
            $viewData=[];
            $viewData['editData'] = Consignees::find($id); // Changed from Cities to Consignees
            $viewData['title']='Consignee'; // Changed from City to Consignee

        return view('admin.consignees.consignee_add',$viewData); // Changed from city_add to consignee_add
    }

    function deleteRecord(Request $request){
        $id = $request->input('id');
        if($id!=''){
            $response=Consignees::where('id',$id)->delete(); // Changed from Cities to Consignees
            if($response){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

}