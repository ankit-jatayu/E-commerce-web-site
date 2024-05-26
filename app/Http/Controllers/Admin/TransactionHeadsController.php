<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;
use Storage;

use App\Models\TransactionHead;
use App\Models\Routes;
use App\Models\Parties;
use DB;

class TransactionHeadsController extends Controller{
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
        
        return $viewData;
    }
    public function index(){
        //$viewData = $this->commonViewData();
        $viewData['title']='Transaction Heads';
        return view('admin.transaction_heads.transaction_head_list',$viewData);
    }

    function paginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "transaction_head.id !='0' ";

            if($request->type!= ''){
                $wherestr .= " AND transaction_head.type =  '".$request->type."'";
            }
            
            if($request->transaction_type!= ''){
                $wherestr .= " AND transaction_head.transaction_type =  '".$request->transaction_type."'";
            }

            $data=TransactionHead::whereRaw($wherestr)->orderby('transaction_head.id','desc');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $TransactionHeadModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==11;})->first();
                        $btn ='';
                        if(isset($TransactionHeadModuleRights) && $TransactionHeadModuleRights->is_edit==1){

                            $btn .= '&nbsp;&nbsp;<a href="'.route('transaction.head.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($TransactionHeadModuleRights) && $TransactionHeadModuleRights->is_delete==1){
                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    
    function addData(Request $request){
        if(!empty($request->all())){
            extract($_POST);
            $login_user_id = Auth::user()->id;
           
            $insertData=array(
                            'name'      =>$name,
                            'type'      =>$type,
                            'transaction_type'      =>$transaction_type,
                        );
         
            $response_id = TransactionHead::create($insertData)->id;
            if($response_id!= ''){
                return redirect('/transaction-head-list')->with('success', 'Transaction Head Added Successfully!!');
            }else{
                return redirect('/transaction-head-list')->with('error', 'Please try again!');
            }
        }

        //$viewData=$this->commonViewData();
        $viewData['title']="Add Transaction Head";

        return view('admin.transaction_heads.transaction_head_add',$viewData);
    }

    function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);

           $updateData=array(
                            'name'      =>$name,
                            'type'      =>$type,
                            'transaction_type'      =>$transaction_type,
                        );
            $response = TransactionHead::where('id',$id)->update($updateData);
            if($response){
                return redirect('/transaction-head-list')->with('success', 'Transaction Head Updated Successfully!!');
            }else{
                return redirect('/transaction-head-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = TransactionHead::find($id);
        
        $viewData=$this->commonViewData();
        
        $viewData['editData'] = $editData;
        $viewData['title']="Edit Transaction Head";
        return view('admin.transaction_heads.transaction_head_add',$viewData);
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=TransactionHead::where('id',$id)->delete();
            if($response){
                return redirect('transaction-head-list')->with('success', 'Transaction Head deleted successfully!');
            }else{
                return redirect('transaction-head-list')->with('error', 'Please try again!');
            }
        }
    }
    
    

    
    function exportPrimaryRecord(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "transaction_head.id !='0' ";
            if(isset($type) && $type!= ''){
                $wherestr .= " AND transaction_head.type =  '".$type."'";
            }
            if(isset($transaction_type) && $transaction_type!= ''){
                $wherestr .= " AND transaction_head.transaction_type =  '".$transaction_type."'";
            }
            

            $data=TransactionHead::whereRaw($wherestr)->get();
          
          $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=transaction_head.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


          $columns = array('Sr No.', 'Name','Particulars', 'Transaction Type');

          $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    $row->name,
                                    $row->type,
                                    $row->transaction_type,
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close

} //class close