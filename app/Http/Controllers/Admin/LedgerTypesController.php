<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\LedgerTypes;


class LedgerTypesController extends Controller
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
    

    public function addledgerType(Request $request) {
        if(!empty($request->all())){
           

            $data = array();
            $data['name'] = $request->input('name');
            $ledger_type_id = LedgerTypes::create($data);
            if($ledger_type_id!= ''){
                return redirect('ledger-type-list')->with('success', 'Ledger Type Created successfully!');
            }else{
                return redirect('ledger-type-list')->with('error', 'Please try again!');
            }
        }
        return view('admin.ledger_types.ledger_type_add');
    }

    public function allLedgerTypeList(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " ledger_types.id !='0' ";

            if($request->name!= ''){
                $wherestr .= " AND name =  '".$request->name."'";
            }

            $data=LedgerTypes::whereRaw($wherestr)->select('*');
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                         $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $LedgerTypesModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==10;})->first();
                        $btn ='';
                        if(isset($LedgerTypesModuleRights) && $LedgerTypesModuleRights->is_edit==1){
                           $btn .= '<a href="'.route('edit.ledger.type',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($LedgerTypesModuleRights) && $LedgerTypesModuleRights->is_delete==1){
                           $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
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

        return view('admin.ledger_types.ledger_type_list');
    }

    public function editledgerType($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){

            $data = LedgerTypes::where('id',$id)->first();
            $data->name = $request->input('name');
           
            $data->save();
            $ledger_type_id = $data->id;
            if($ledger_type_id!= ''){
                return redirect('ledger-type-list')->with('success', 'LedgerType Detail Updated successfully!');
            }else{
                return redirect('ledger-type-list')->with('error', 'Please try again!');
            }
        }
        $viewData['ledger_type_detail'] = LedgerTypes::where('id',$id)->first();
        
        return view('admin.ledger_types.ledger_type_add',$viewData);
    }

    public function updateLedgerTypeStatus(Request $request) {
        
        $data = LedgerTypes::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=LedgerTypes::where('id',$id)->delete();
            if($response){
                return redirect('ledger-type-list')->with('success', 'Ledger Type deleted successfully!');
            }else{
                return redirect('ledger-type-list')->with('error', 'Please try again!');
            }
        }
    }

    
   
}