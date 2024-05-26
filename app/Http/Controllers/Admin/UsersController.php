<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Party;
use App\Models\ProjectModules;
use App\Models\UserProjectModules;
use DataTables;
use Auth;
use Response;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
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
    

    public function addUser(Request $request) {
        if(!empty($request->all())){
            
            $data = array();
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');
            
            $data['mobile_no'] = $request->input('mobile_no');
            $data['password'] = Hash::make($request->input('password'));
            
            $user_data = User::create($data);

            $module_arr = $request->input('module');

            if(!empty($module_arr)){
                foreach ($module_arr as $project_module_id => $module) {
                    if(isset($module) && !empty($module)){
                        $module_data = array();
                        $module_data['user_id'] = $user_data->id;
                        $module_data['project_module_id'] = $project_module_id;
                        
                        $module_data['is_view'] = (isset($module['view']) && !empty($module['view']))?1:0;
                        $module_data['is_create'] = (isset($module['add']) && !empty($module['add']))?1:0;
                        $module_data['is_edit'] = (isset($module['edit']) && !empty($module['edit']))?1:0;
                        $module_data['is_delete'] = (isset($module['delete']) && !empty($module['delete']))?1:0;
                        $module_data['is_export'] = (isset($module['export']) && !empty($module['export']))?1:0;
                        
                        UserProjectModules::create($module_data); 
                    } //if close
                } //loop close
            } //if close
                    
            if($user_data!= ''){
                return redirect('user-list')->with('success', 'Employee Created successfully!');
            }else{
                return redirect('user-list')->with('error', 'Please try again!');
            }
        }
        //$viewData['Party']=Party::where('party_type','Party')->orWhere('party_type', '=', 'Group')->get()->toArray();
        $viewData['web_modules']=ProjectModules::where('type','Web')->get()->toArray();
        $viewData['app_modules']=ProjectModules::where('type','App')->get()->toArray();
        return view('admin.users.user_add',$viewData);
    }

    public function allUserList(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " users.id != 1 ";
            $viewData = array();
            if($request->role_id > 0){
                $wherestr .= " AND role_id ='".$request->role_id."' ";
            }

            if($request->party_id!=''){
                $wherestr .= " AND party_id ='".$request->party_id."' ";
            }


            $data=User::select('users.*')->whereRaw($wherestr);
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $UserModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==12;})->first();
                        $btn ='';
                        if(isset($UserModuleRights) && $UserModuleRights->is_edit==1){
                           $btn .= '<a href="'.route('edit.user',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                         }
                           return $btn;
                    })->addColumn('btn_toggel', function($row) {
                            if($row->status == '1'){
                                $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
                            }else{
                                $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
                            }
                            return $html;
                    })
                    ->rawColumns(['action','btn_toggel'])
                    ->make(true);
        }

        return view('admin.users.user_list');
    }

    public function editUser($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
          
            $password = $request->input('password');
            $data = User::where('id',$id)->first();
            $data->name = $request->input('name');
            $data->email = $request->input('email');
            $data->mobile_no = $request->input('mobile_no');
            
            $module_arr = $request->input('module');
            
            if(isset($password) && $password!=''){
                $data->password = Hash::make($password);
            }
            
            $data->save();
            $user_id = $data->id;

            UserProjectModules::where('user_id',$user_id)->delete(); //delete old data
            if(!empty($module_arr)){
                foreach ($module_arr as $project_module_id => $module) {
                    if(isset($module) && !empty($module)){
                        $module_data = array();
                        $module_data['user_id'] = $user_id;
                        $module_data['project_module_id'] = $project_module_id;
                        
                        $module_data['is_view'] = (isset($module['view']) && !empty($module['view']))?1:0;
                        $module_data['is_create'] = (isset($module['add']) && !empty($module['add']))?1:0;
                        $module_data['is_edit'] = (isset($module['edit']) && !empty($module['edit']))?1:0;
                        $module_data['is_delete'] = (isset($module['delete']) && !empty($module['delete']))?1:0;
                        $module_data['is_export'] = (isset($module['export']) && !empty($module['export']))?1:0;
                        
                        UserProjectModules::create($module_data); 
                    } //if close
                } //loop close
            } //if close

            if($user_id!= ''){
                return redirect('user-list')->with('success', 'Employee Detail Updated successfully!');
            }else{
                return redirect('user-list')->with('error', 'Please try again!');
            }
        }
        
        $editData = User::find($id);
        
        
        $viewData['user_detail']=$editData;
        //$viewData['modules']=ProjectModules::get()->toArray();
        $viewData['web_modules']=ProjectModules::where('type','Web')->get()->toArray();
        $viewData['app_modules']=ProjectModules::where('type','App')->get()->toArray();
        $viewData['selectedModuleIDs']=UserProjectModules::where('user_id',$id)->pluck('project_module_id')->toArray();
        //$viewData['selectedModuleData']=UserProjectModules::where('user_id',$id)->get();
        
        return view('admin.users.user_add',$viewData);
    }

    function getParty(){
        $Party=Party::where('party_type',$_POST['party_type'])->get();
        return json_encode($Party);
    }

    public function updateUserStatus(Request $request) {
        
        $data = User::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    function exportUsers(){

        $paramData=(isset($_GET['data']))?json_decode($_GET['data'],true):[];
        extract($paramData);
        
        $wherestr = "id != 0 ";
            
        if(isset($party_id) && $party_id!= ''){
            $wherestr .= " AND party_id =  '".$party_id."'";
        }
        if(isset($role_id) && !empty($role_id)){
                $wherestr .= " AND role_id ='".$role_id."' ";
            }

        $data=User::whereRaw($wherestr)->get();
    
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=users.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

        $columns = array('sr', 'Name','User Name','Email','Phone','Type','Status','Authorised','Repair Authorised');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                fputcsv($file,array($sr,
                                    $value->name,
                                    $value->username,
                                    $value->email,
                                    $value->mobile_no,
                                    (isset($value->getRoleDetail->name))? $value->getRoleDetail->name:'',
                                    $value->status,
                                    $value->is_authorised,
                                    $value->is_repair_authorised,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
   
    function viewProfile(Request $request){
        if(!empty($request->all())){
            $updateData=User::find(auth::user()->id);
            $updateData->password=Hash::make($request->input('password'));
            if($updateData->save()){
                return redirect('edit-profile')->with('success', 'Profile updated!');
            }else{
                return redirect('edit-profile')->with('error', 'Profile not updated!');
            }
        }
        $viewData['editData']=User::find(auth::user()->id);
        return view('admin.users.edit_profile',$viewData);
    }
}