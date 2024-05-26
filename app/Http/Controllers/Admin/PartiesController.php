<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\Parties;
use App\Models\PartyDocuments;
use App\Models\PartyAdditionalDetails;
use App\Models\User;
use App\Models\PartyTypes;
use App\Models\PartySelectedPartyTypes;

use App\Models\AccountBook;
use App\Models\AccountType;
use App\Models\TransactionHead;


class PartiesController extends Controller
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
        $viewData['partyTypes']=PartyTypes::get(['id','name']);
        return $viewData;
    }

    public function index(){
        $viewData=$this->commonViewData();
        $viewData['title']='PARTIES';

        return view('admin.parties.party_list',$viewData);
    }

    function partyPaginate(Request $request){
        if ($request->ajax()) {

            $wherestr = "id !='0' ";
            
            if($request->party_type_id!= ''){
                echo $request->party_type_id;
                die();
                $wherestr .= " AND ledger_type_id = '".$request->ledger_type_id."' ";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(created_at) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(created_at) <=  '".$request->to_date."'";
            }

            $data=Parties::whereRaw($wherestr);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                          $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $PartyModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==1;})->first();
                        $btn ='';
                        if(isset($PartyModuleRights) && $PartyModuleRights->is_edit==1){

                           $btn .= '<a href="'.route('party.edit',base64_encode($row['id'])).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($PartyModuleRights) && $PartyModuleRights->is_delete==1){

                                $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                            $btn .= '&nbsp;&nbsp;<a href="'.route('report.party.transaction.detail',base64_encode($row['id'])).'" class="btn btn-secondary btn-sm"><i class="feather icon-grid"></i></a>';
                            return $btn;
                    })->addColumn('status', function($row) {
                            if($row->status == '1'){
                                $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
                            }else{
                                $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
                            }
                            return $html;
                    })->addColumn('created_by', function($row) {
                        return (isset($row->getCreatedByDetail))?$row->getCreatedByDetail->name:'';
                    })->addColumn('party_type', function($row) {
                        // $temp = ($row->getSelectedPartyTypes)?$row->getSelectedPartyTypes:[];
                        return '';
                    })
                    ->rawColumns(['action','status','created_by'])
                    ->make(true);
        }
    }

    function partyAdd(Request $request){
        if(!empty($request->all())){
            extract($request->all());

            $insertData=array(
                              'name'=>$name,
                              'phone_no'=>$phone_no,
                              'gst_no'=>$gst_no,
                              'pan_no'=>$pan_no,
                              'state_code'=>$state_code,
                              'tds_per'=>$tds_per,
                              'state_name'=>$state_name,
                              'city'=>$city,
                              'pincode'=>$pincode,
                              'address_line_1'=>$address_line_1,
                              'address_line_2'=>$address_line_2,
                              'bank_name'=>$bank_name,
                              'ifsc_code'=>$ifsc_code,
                              'beneficiary_name'=>$beneficiary_name,
                              'branch_name'=>$branch_name,
                              'account_no'=>$account_no,
                              'account_type'=>$account_type,
                              'created_by'=>Auth::user()->id,
                          );
            $res = Parties::create($insertData);
            if($res){
                $party_id=$res->id;
                //add selected party types
                if(isset($request->party_type_id) && !empty($request->party_type_id)){
                    foreach($request->party_type_id as $party_type_id){
                        $insertPartyTypeData=['party_id'=>$party_id,
                                              'party_type_id'=>$party_type_id
                                             ];
                        PartySelectedPartyTypes::create($insertPartyTypeData);                     
                    }//loop close
                }//if close
                
                //add selected party types
                
                $DetailArr=(isset($_POST['childDetail']))?array_values($_POST['childDetail']):[];
                if(!empty($DetailArr)){
                    foreach($DetailArr as $k => $row){
                        $insertData = array();
                        $insertData['party_id'] = $party_id;
                        $insertData['name'] = $row['name'];
                        $insertData['designation'] = $row['designation'];
                        $insertData['phone_no'] = $row['phone_no'];
                        $insertData['email'] = (isset($row['email']))?$row['email']:'';
                        PartyAdditionalDetails::create($insertData);
                    }
                }
                
                $document_name=$_POST['document_name'];
                $files = $request->file('document_file');
                if($files && !empty($document_name)){
                    foreach($files as $k => $file){

                          $fileName = rand(99999,999999).'_'.time().'.'.$file->extension();  
                          $file->move(public_path('uploads/party_docs'), $fileName);
                          $doc_file=$fileName;

                          $docInsertData=array('party_id'=> $party_id,
                             'name'    => $document_name[$k],
                             'doc_file'=> $doc_file,
                         );        

                          PartyDocuments::create($docInsertData);
                    } //loop close
                } //if close    
                return redirect('/party-list')->with('success', 'Party Added successfully!');
            }else{
                return redirect('/party-list')->with('error', 'Please try again!');
            }
        }

        $viewData=$this->commonViewData();
        $viewData['title']="ADD PARTY";
        
        return view('admin.parties.party_add',$viewData);
    }


    public function partyEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
           
            $updateData=array(
                              'name'=>$name,
                              'phone_no'=>$phone_no,
                              'gst_no'=>$gst_no,
                              'pan_no'=>$pan_no,
                              'state_code'=>$state_code,
                              'tds_per'=>$tds_per,
                              'state_name'=>$state_name,
                              'city'=>$city,
                              'pincode'=>$pincode,
                              'address_line_1'=>$address_line_1,
                              'address_line_2'=>$address_line_2,
                              'bank_name'=>$bank_name,
                              'ifsc_code'=>$ifsc_code,
                              'beneficiary_name'=>$beneficiary_name,
                              'branch_name'=>$branch_name,
                              'account_no'=>$account_no,
                              'account_type'=>$account_type,
                            );

            $res=Parties::where('id',$id)->update($updateData);
            
            if($res!= ''){
                $party_id=$id;
                //add selected party types
                if(isset($request->party_type_id) && !empty($request->party_type_id)){
                    PartySelectedPartyTypes::where('party_id',$party_id)->forceDelete(); //delete previous
                    foreach($request->party_type_id as $party_type_id){
                        $insertPartyTypeData=['party_id'=>$party_id,
                                              'party_type_id'=>$party_type_id
                                             ];
                        PartySelectedPartyTypes::create($insertPartyTypeData);                     
                    }//loop close
                }//if close
                
                //add selected party types

                $DetailArr=(isset($_POST['childDetail']))?array_values($_POST['childDetail']):[];
                if(!empty($DetailArr)){
                    PartyAdditionalDetails::where('party_id',$party_id)->delete(); //delete old child records as per inward 

                    foreach($DetailArr as $k => $row){
                        $insertData = array();
                        $insertData['party_id'] = $party_id;
                        $insertData['name'] = $row['name'];
                        $insertData['designation'] = $row['designation'];
                        $insertData['phone_no'] = $row['phone_no'];
                        $insertData['email'] = (isset($row['email']))?$row['email']:'';
                        PartyAdditionalDetails::create($insertData);
                    }
                }

                $document_name=$_POST['document_name'];
                $files = $request->file('document_file');
                      
                if($files && !empty($document_name)){
                    foreach($files as $k => $file){
                          $fileName = rand(99999,999999).'_'.time().'.'.$file->extension();  
                          $file->move(public_path('uploads/party_docs'), $fileName);
                          $doc_file=$fileName;

                          $docInsertData=array('party_id'=> $party_id,
                             'name'    => $document_name[$k],
                             'doc_file'=> $doc_file,
                         );        
                        PartyDocuments::create($docInsertData);
                    } //loop close
                } //if close    
                
                return redirect('/party-list')->with('success', 'Party Updated successfully!');
            }else{
                return redirect('/party-list')->with('error', 'Please try again!');
            }

        }  //if close

        $viewData=$this->commonViewData();
        $viewData['title']='EDIT PARTY';
        $edit_detail = Parties::where('id',$id)->first();
        $viewData['selectedPartyTypeIds'] = ($edit_detail->getSelectedPartyTypes)?$edit_detail->getSelectedPartyTypes->pluck('party_type_id')->toArray():[];
        $viewData['editData'] = $edit_detail->toArray();
        
        $viewData['childDetail']=(isset($edit_detail->getPartyAdditionalDetails))?$edit_detail->getPartyAdditionalDetails:[];
        $viewData['selectedPartyDocs']=(isset($edit_detail->getPartyDocuments))?$edit_detail->getPartyDocuments:[];
       
        return view('admin.parties.party_add',$viewData);
    }

    function exportParty(){

        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
        
        $wherestr = "id !='0' ";
        if(isset($ledger_type_id) && $ledger_type_id!= ''){
                $wherestr .= " AND ledger_type_id = '".$ledger_type_id."' ";
        }
        if(isset($from_date) && $from_date!= ''){
            $wherestr .= " AND DATE(created_at) >=  '".$from_date."'";
        }

        if(isset($to_date) && $to_date!= ''){
            $wherestr .= " AND DATE(created_at) <=  '".$to_date."'";
        }

      
        $data=Parties::whereRaw($wherestr)->get();
        $headers = array(
                        "Content-type" => "text/csv",
                        "Content-Disposition" => "attachment; filename=parties.csv",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                    );

        $columns = array('sr', 'Company Name', 'Primary No','GST No', 'Company Type','Address','Created By');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                fputcsv($file,array($sr,
                                    $value->name,
                                    $value->phone_no,
                                    $value->gst_no,
                                    $value->ledger_type_id,
                                    $value->address_line_1.$value->address_line_2,
                                    (isset($value->getCreatedByDetail))?$value->getCreatedByDetail->name:'',
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request) {
        
        $data = Parties::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    function deleteParty(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            PartySelectedPartyTypes::where('party_id',$id)->delete();
            $response=Parties::where('id',$id)->delete();
            if($response){
                return redirect('party-list')->with('success', 'Party deleted successfully!');
            }else{
                return redirect('party-list')->with('error', 'Please try again!');
            }
        }
    }

    function deletePartyAdditionDetailSingle(){
        $id=(isset($_POST['id']))?$_POST['id']:'';
        $response=PartyAdditionalDetails::where('id',$id)->delete();
        if($response){
            echo 1;
        }else{
            echo 0;
        }
    }
    function deletePartyDocSingle(){
        $id=(isset($_POST['id']))?$_POST['id']:'';
        $oldData = PartyDocuments::find($id);
        if($oldData->doc_file!='' && $oldData->doc_file!=null){
            unlink(public_path('uploads/party_docs/'.$oldData->doc_file));
        }

        $response = PartyDocuments::where('id',$id)->delete();
        if($response){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function partyTransactionList($id,){
        $id = base64_decode($id);
        $accountType=Parties::where('id',$id)->first();
        $viewData['title']=$accountType->name;
        $viewData['party_id']=$accountType->id;
        
        return view('admin.parties.party_transaction_report',$viewData);
    }

    function partyTransactionPaginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "account_book.id !='0' ";

            $login_user_role = Auth::user()->role_id;
           
            if($request->party_id!= ''){
                $wherestr .= " AND account_book.party_id =  '".$request->party_id."'";
            }
            
            if($request->head_type_id!= ''){
                $wherestr .= " AND account_book.head_type_id =  '".$request->head_type_id."'";
            }

            if($request->type!= ''){
                $wherestr .= " AND account_book.type =  '".$request->type."'";
            }

            if($request->account_type_id!= ''){
                $wherestr .= " AND account_book.account_type_id =  '".$request->account_type_id."'";
            }

            if($request->transaction_type!= ''){
                $wherestr .= " AND account_book.transaction_type =  '".$request->transaction_type."'";
            }


            if($request->from_date!= ''){
                $wherestr .= " AND DATE(account_book.entry_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(account_book.entry_date) <=  '".$request->to_date."'";
            }
            
            $data=AccountBook::whereRaw($wherestr)->orderby('account_book.id','desc');
            $balance = 0;
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '';
                        if($row->voucher_id==0){
                            $btn .= '&nbsp;&nbsp;<a href="'.route('account.book.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                        }
                        
                        
                        return $btn;
                    })->addColumn('entry_date', function($row) {
                      return isset($row->entry_date)?date('d/m/Y',strtotime($row->entry_date)):'';
                    })->addColumn('party_id', function($row) {
                      return (isset($row->getSelectedParty))?$row->getSelectedParty->name:'';
                    })->addColumn('account_type_id', function($row) {
                      return (isset($row->getSelectedAccountType))?$row->getSelectedAccountType->name:'';
                    })->addColumn('head_type_id', function($row) {
                      return (isset($row->getSelectedTransactionHead))?$row->getSelectedTransactionHead->name:'';
                    })->addColumn('created_by', function($row) {
                      return (isset($row->getCreatedBy))?$row->getCreatedBy->name:'';
                    })->addColumn('debit', function($row) {
                      return ($row->type=='Expense')?$row->amount:'-';
                    })->addColumn('credit', function($row) {
                      return ($row->type=='Income')?$row->amount:'-';
                    })->addColumn('balance', function($row) use (&$balance)  {
                        
                        if($row->type=='Income'){
                            $balance = $balance + $row->amount;
                        }else{
                            $balance = $balance - $row->amount;
                        }
                        return $balance;
                    })
                    ->rawColumns(['action','credit','debit','balance'])
                    ->make(true);
        }
    }

    function partyTransactionExport(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "account_book.id !='0' ";
            if(isset($party_id) && $party_id!= ''){
                $wherestr .= " AND account_book.party_id =  '".$party_id."'";
            }

            if(isset($head_type_id) && $head_type_id!= ''){
                $wherestr .= " AND account_book.head_type_id =  '".$head_type_id."'";
            }

            if(isset($type) && $type!= ''){
                $wherestr .= " AND account_book.type =  '".$type."'";
            }

            if(isset($account_type_id) && $account_type_id!= ''){
                $wherestr .= " AND account_book.account_type_id =  '".$account_type_id."'";
            }

            if(isset($transaction_type) && $transaction_type!= ''){
                $wherestr .= " AND account_book.transaction_type =  '".$transaction_type."'";
            }


            if(isset($from_date) && $from_date!= ''){
                $wherestr .= " AND DATE(account_book.entry_date) >=  '".$from_date."'";
            }

            if(isset($to_date) && $to_date!= ''){
                $wherestr .= " AND DATE(account_book.entry_date) <=  '".$to_date."'";
            }
            
            $data=AccountBook::whereRaw($wherestr)->get();
          
            $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=party_ledger.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


            $columns = array('Sr No.', 'Date','Account Type', 'Particular','Head type','Debit','Credit','Balance','branch','Narration');

            $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            $balance = 0;
            foreach($data as $row) {
                $balance = ($row->type=='Income')?($balance + $row->amount):($balance - $row->amount);
                fputcsv($file,array($sr,
                                    (isset($row->entry_date))?date('d/m/Y',strtotime($row->entry_date)):'',
                                    (isset($row->getSelectedAccountType))?$row->getSelectedAccountType->name:'',
                                    $row->type,
                                    (isset($row->getSelectedTransactionHead))?$row->getSelectedAccountType->name:'',
                                    ($row->type=='Expense')?$row->amount:'-',//debit amount
                                    ($row->type=='Income')?$row->amount:'-',//credit amount
                                    $balance,//balance amount
                                    $row->branch,
                                    $row->narration,
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close
} //class close