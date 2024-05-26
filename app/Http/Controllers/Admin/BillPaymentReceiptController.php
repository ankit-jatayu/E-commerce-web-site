<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;
use Storage;

use App\Models\Bills;
use App\Models\Parties;
use App\Models\TransportTrips;
use App\Models\UserProjectModules;
use App\Models\MNEntries;
use App\Models\Settings;
use App\Models\BillPaymentReceipts;
use App\Models\AccountType;
use App\Models\AccountBook;

use DB;


/*use App\Models\Vehicles;
use App\Models\Routes;
use App\Models\User;
use App\Models\VehicleTypes;*/

class BillPaymentReceiptController extends Controller {

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
        $viewData=array();
        $viewData['parties']=Bills::leftJoin('party','party.id','bills.party_id')
                                                       // ->where('party.ledger_type_id',1) // ledger_type_id = 1 = company
                                                       ->select('party.id as id','party.name as name')
                                                       ->groupby('party.id')
                                                       ->get();
        $viewData['AccountType']=AccountType::get();

        // $viewData['vehicles']=TransportTrips::leftJoin('vehicles','vehicles.id','transport_trips.vehicle_id')
        //                                                ->where('transport_trips.is_delete',0)
        //                                                ->select('vehicles.id as id','vehicles.registration_no as name')
        //                                                ->groupby('vehicles.id')
        //                                                ->get();

        return $viewData;
    }

    public function index(){
        $viewData = $this->commonViewData();
        $viewData['title']='ALL BILL PAYMENT RECEIPTS ';
        
        return view('admin.bill_payment_receipts.bill_payment_receipt_list',$viewData);
    }

    function paginate(Request $request){
        if ($request->ajax()) {
            $wherestr = "bill_payment_receipts.id !='0' ";

            $login_user_role = Auth::user()->role_id;
           
            if($request->party_id!= ''){
                $wherestr .= " AND bill_payment_receipts.party_id =  '".$request->party_id."'";
            }

            if($request->receipt_no!= ''){
                $wherestr .= " AND bill_payment_receipts.receipt_no =  '".$request->receipt_no."'";
            }
            
            if($request->from_date!= ''){
                $wherestr .= " AND DATE(bill_payment_receipts.receipt_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(bill_payment_receipts.receipt_date) <=  '".$request->to_date."'";
            }
            
            $data=BillPaymentReceipts::whereRaw($wherestr)->orderby('bill_payment_receipts.id','desc');

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $user_id = Auth::user()->id;

                        $all_access_rights = UserProjectModules::where(['user_id' => $user_id])->get();
                        $BillsPaymentReceiptModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==13;})->first(); //bill payment receipt module

                        $btn = '';
                         //    $settings=Settings::where('id',1)->first();
                            
                        // if($row->lr_date >= $settings->data_lock_date){ //dont confuse with crnt date
                            if(isset($BillsPaymentReceiptModuleRights) && $BillsPaymentReceiptModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<a href="'.route('bill.payment.receipt.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                        <i class="feather icon-edit"></i></a>';
                            }
                        //}

                        if(isset($BillsPaymentReceiptModuleRights) && $BillsPaymentReceiptModuleRights->is_delete==1){
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                        }    
                            return $btn;  
                    })
                    ->addColumn('receipt_date', function($row) {
                      return isset($row->receipt_date)?date('d/m/Y',strtotime($row->receipt_date)):'';
                    })->addColumn('party_id', function($row) {
                      return (isset($row->getSelectedParty))?$row->getSelectedParty->name:'';
                    })->addColumn('bill_id', function($row) {
                      return (isset($row->getSelectedBill))?$row->getSelectedBill->bill_no:'';
                    })->addColumn('created_by', function($row) {
                      return (isset($row->getCreatedBy))?$row->getCreatedBy->name:'';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    
    function addUpdateAcountBook($bill_rcpt_id,$postData){
        $received_amt=$postData['received_amt'];
        $accountBookEntryData=array('trip_id'=>$bill_rcpt_id,
                                    'entry_date'=>$postData['receipt_date'],
                                    'party_id'=>$postData['party_id'],
                                    'transaction_type'=>$_POST['transaction_type'],
                                    'account_type_id'=>$_POST['account_type_id'],
                                    'type'=>'Income',
                                    'head_type_id'=>($_POST['transaction_type']=='Cash')?62:63,
                                    'amount'=>$received_amt,
                                    'created_by'=>Auth::user()->id,
                                    'narration'=>'account entry againt bill payment receipt no '.$postData['receipt_no'],
                                   );
        
        $check=AccountBook::where('trip_id',$bill_rcpt_id)
                            ->where('type','Income')
                            ->where('head_type_id',($_POST['transaction_type']=='Cash')?62:63)
                            ->count();
        if($check==0){
            AccountBook::create($accountBookEntryData);
        }else{
            AccountBook::where('trip_id',$bill_rcpt_id)->update($accountBookEntryData);
        }

    }//func close

    function addData(Request $request){
        if(!empty($request->all())){
            extract($_POST);
            
            $login_user_id = Auth::user()->id;
            
            /*$getTripData = TransportTrips::where('is_delete',0)->select('lr_no')->orderBy('lr_no','DESC')->first();
            $lr_no=(!empty($getTripData))?($getTripData->lr_no+1):1;
            */
           
            $insertData=array(
                            'receipt_no'           =>$receipt_no,
                            'receipt_no_suffix'    =>$receipt_no_suffix,
                            'receipt_date'         =>$receipt_date,
                            'party_id'          =>$party_id,
                            'bill_id'          =>$bill_id,
                            'received_amt'      =>$received_amt,
                            'created_by'        =>$login_user_id,
                        );
            $bill_payment_rcpt_id = BillPaymentReceipts::create($insertData)->id;
            if($bill_payment_rcpt_id!= ''){
                $this->addUpdateAcountBook($bill_payment_rcpt_id,$_POST); //rcpt entry in account book

                return redirect('/bill-payment-receipt-list')->with('success', 'Bill Payment Receipt Added!! ');
            }else{
                return redirect('/bill-payment-receipt-list')->with('error', 'Please try again!');
            }
        }

        $viewData=$this->commonViewData();
        $viewData['title']="ADD BILL PAYMENT RECEIPT";
        $lastEntry=BillPaymentReceipts::orderby('id','DESC')->limit(1)->first();
        $receipt_no='RCPT_1';
        $new_suffix=1;
        if(isset($lastEntry) && !empty($lastEntry)){
            $new_suffix=$lastEntry->receipt_no_suffix+1;
            $receipt_no='RCPT_'.$new_suffix;
        }

        $viewData['new_receipt_no']=$receipt_no;
        $viewData['new_suffix']=$new_suffix;

        return view('admin.bill_payment_receipts.bill_payment_receipt_add',$viewData);
    }

    function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            // print_r('<pre>');
            // print_r($_POST);
            // die();
            
           
           $updateData=array(
                            'receipt_date'         =>$receipt_date,
                            'party_id'          =>$party_id,
                            'bill_id'          =>$bill_id,
                            'received_amt'      =>$received_amt,
                        );
            
            $response = BillPaymentReceipts::where('id',$id)->update($updateData);
            if($response){
                $this->addUpdateAcountBook($id,$_POST); //rcpt entry in account book

                return redirect('/bill-payment-receipt-list')->with('success', 'Bill Payment Receipt Updated !! ');
            }else{
                return redirect('/bill-payment-receipt-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = BillPaymentReceipts::where('id',$id)->first();

        $viewData=$this->commonViewData();
        $viewData['editData'] = $editData;
        $viewData['editSelectedPartyBills'] = Bills::where('party_id',$editData->party_id)
                                      ->select( 'bills.id',
                                            'bills.bill_no',
                                            'bills.remain_amount',
                                            DB::raw("DATE_FORMAT(bills.bill_date,'%d/%m/%Y') AS bill_date")
                                          )
                                     ->get();
       
         $viewData['editrcptAccBookEntry']=AccountBook::where('trip_id',$id)
                          ->where('type','Income')
                          ->whereRaw('(head_type_id=62 OR head_type_id=63)')
                          ->first();
      



        $viewData['title']='BILL PAYMENT RECEIPT NO : '.$editData->receipt_no;
        
        return view('admin.bill_payment_receipts.bill_payment_receipt_add',$viewData);
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){

             AccountBook::where('trip_id',$id)
                          ->where('type','Income')
                          ->whereRaw('(head_type_id=62 OR head_type_id=63)')
                          ->delete();
                          
            $response=BillPaymentReceipts::where('id',$id)->delete();
            if($response){
                return redirect('bill-payment-receipt-list')->with('success', 'Bill Payment Receipt Deleted !! ');
            }else{
                return redirect('bill-payment-receipt-list')->with('error', 'Please try again!');
            }
        }
    }

    function getPartywiseBills(){
        $wherestr="bills.id!='' AND bills.id !='0' ";
        
        // if($_POST['bill_id']!= ''){
        //     $wherestr .= " AND (mn_entries.bill_id =  '".$_POST['bill_id']."' OR mn_entries.bill_id = 0)";
        // }else{
        //     $wherestr .= " AND mn_entries.bill_id=0 ";
        // }

        if($_POST['party_id']!= ''){
            $wherestr .= " AND bills.party_id =  '".$_POST['party_id']."'";
        }

        // if($_POST['from_date']!= ''){
        //     $wherestr .= " AND DATE(mn_entries.mn_date) >=  '".$_POST['from_date']."'";
        // }

        // if($_POST['to_date']!= ''){
        //     $wherestr .= " AND DATE(mn_entries.mn_date) <=  '".$_POST['to_date']."'";
        // }
        
        $response=Bills::whereRaw($wherestr)
                                  ->select( 'bills.id',
                                            'bills.bill_no',
                                            'bills.remain_amount',
                                            DB::raw("DATE_FORMAT(bills.bill_date,'%d/%m/%Y') AS bill_date")
                                          )
                                  ->get();

        echo json_encode($response);

    } //func close

    function exportPrimaryRecord(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "bill_payment_receipts.id !='0' ";
            if(isset($party_id) && $party_id!= ''){
                $wherestr .= " AND bill_payment_receipts.party_id =  '".$party_id."'";
            }

             if(isset($receipt_no) && $receipt_no!= ''){
                $wherestr .= " AND bill_payment_receipts.receipt_no =  '".$receipt_no."'";
            }

            if(isset($from_date) && $from_date!= ''){
                $wherestr .= " AND DATE(bill_payment_receipts.receipt_date) >=  '".$from_date."'";
            }

            if(isset($to_date) && $to_date!= ''){
                $wherestr .= " AND DATE(bill_payment_receipts.receipt_date) <=  '".$to_date."'";
            }
            

            $data=BillPaymentReceipts::whereRaw($wherestr)->select('bill_payment_receipts.*')->get();
          
          $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=bill_payment_receipts.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


          $columns = array('sr', 'receipt_no','receipt_date', 'party', 'received_amt','created_by');

          $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                
                fputcsv($file,array($sr,
                                    $row->receipt_no,
                                    (isset($row->receipt_date))?date('d/m/Y',strtotime($row->receipt_date)):'',
                                    (isset($row->getSelectedParty))?$row->getSelectedParty->name:'',
                                    $row->received_amt,
                                    (isset($row->getCreatedBy))?$row->getCreatedBy->name:'',
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close

} //class close