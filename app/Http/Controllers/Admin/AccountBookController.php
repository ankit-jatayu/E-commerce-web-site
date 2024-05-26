<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;
use Storage;

use App\Models\AccountBook;
use App\Models\AccountType;
use App\Models\TransactionHead;
use App\Models\Parties;
use DB;

class AccountBookController extends Controller{

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
        $viewData['Parties']=Parties::where('status',1)->get();
        $viewData['AccountType']=AccountType::get();
        $viewData['TransactionHead']=TransactionHead::get();
        return $viewData;
    }

    function getTransportHeads(){
        $data=TransactionHead::where('type',$_POST['type'])
                               ->where('transaction_type',$_POST['transaction_type'])
                               ->get();

        echo json_encode($data);    
    }

    public function index(){
        $viewData = $this->commonViewData();
        $viewData['title']='Account Book';
        return view('admin.account_book.account_book_list',$viewData);
    }

    function paginate(Request $request){
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
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                         $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $AccountBookModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==8;})->first();
                        $btn ='';
                        if(isset($AccountBookModuleRights) && $AccountBookModuleRights->is_edit==1){
                                $btn .= '&nbsp;&nbsp;<a href="'.route('account.book.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($AccountBookModuleRights) && $AccountBookModuleRights->is_delete==1){
                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            };
                        
                        
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
                    })
                    ->rawColumns(['action','credit','debit'])
                    ->make(true);
        }
    }
    
    function addData(Request $request){
        if(!empty($request->all())){
            extract($_POST);
            $login_user_id = Auth::user()->id;
           
            $insertData=array(
                            'entry_date'      =>$entry_date,
                            'party_id'      =>$party_id,
                            'transaction_type'          =>$transaction_type,
                            'account_type_id'      =>$account_type_id,
                            'type'          =>$type,
                            'head_type_id'=>$head_type_id,
                            'amount'=>$amount,
                            'narration'=>$narration,
                            'remarks'=>$remarks,
                            'branch'=>$branch,
                            'created_by'=>Auth::user()->id,
                        );
      
            $response_id = AccountBook::create($insertData)->id;
            if($response_id!= ''){
                return redirect('/account-book-list')->with('success', 'Account Entry Added Successfully!!');
            }else{
                return redirect('/account-book-list')->with('error', 'Please try again!');
            }
        }

        $viewData=$this->commonViewData();
        $viewData['title']="Add Account Book";

        return view('admin.account_book.account_book_add',$viewData);
    }

    function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);

           $updateData=array(
                             'entry_date'      =>$entry_date,
                            'party_id'      =>$party_id,
                            'transaction_type'          =>$transaction_type,
                            'account_type_id'      =>$account_type_id,
                            'type'          =>$type,
                            'head_type_id'=>$head_type_id,
                            'amount'=>$amount,
                            'narration'=>$narration,
                            'remarks'=>$remarks,
                            'branch'=>$branch,
                        );
           
            $response = AccountBook::where('id',$id)->update($updateData);
            if($response){
                return redirect('/account-book-list')->with('success', 'Account Entry Updated Successfully!!');
            }else{
                return redirect('/account-book-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = AccountBook::find($id);
        
        $viewData=$this->commonViewData();
        
        $viewData['editData'] = $editData;
        $viewData['selectedTransHeads']=TransactionHead::where('type',$editData->type)
                               ->where('transaction_type',$editData->transaction_type)
                               ->get();

        $viewData['title']="Edit Account Book";
        return view('admin.account_book.account_book_add',$viewData);
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=AccountBook::where('id',$id)->delete();
            if($response){
                return redirect('account-book-list')->with('success', 'Account Entry deleted successfully!');
            }else{
                return redirect('account-book-list')->with('error', 'Please try again!');
            }
        }
    }
    
    
    function exportPrimaryRecord(){
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
                            "Content-Disposition" => "attachment; filename=account_book.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


          $columns = array('sr', 'payment_date','party', 'transaction_type','account_type','particular','head_type','debit','credit','branch','narration','remarks','created_by');

          $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                fputcsv($file,array($sr,
                                    (isset($row->entry_date))?date('d/m/Y',strtotime($row->entry_date)):'',
                                    (isset($row->getSelectedParty))?$row->getSelectedParty->name:'',
                                    $row->transaction_type,
                                    (isset($row->getSelectedAccountType))?$row->getSelectedAccountType->name:'',
                                    $row->type,
                                    (isset($row->getSelectedTransactionHead))?$row->getSelectedTransactionHead->name:'',
                                    ($row->type=='Expense')?$row->amount:'-',//debit amount
                                    ($row->type=='Income')?$row->amount:'-',//credit amount
                                    $row->branch,
                                    $row->narration,
                                    $row->remarks,
                                    (isset($row->getCreatedBy))?$row->getCreatedBy->name:'',
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close

    function transportTripVoucherTallyXml(){
            $paramData=json_decode($_GET['data'],true);
        extract($paramData);
            $ids = explode(',',$id);
        $login_user_id = Auth::user()->id;

        foreach ($ids as $key => $value) {
          if($value!=''){
            $all_voucher = TransportTripVouchers::where('id',$value)->first();
            $all_voucher->voucher_tally = 1;
            $all_voucher->voucher_tally_by = $login_user_id;
            $all_voucher->save();
          }
        }
            
        $data=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                  ->leftJoin('vehicles as v', 'v.id', '=', 'trip_voucher.vehicle_id')
                                  ->leftJoin('trip_payment_types as tpt', 'tpt.id', '=', 'trip_voucher.payment_type_id')
                                  ->leftJoin('transport_jobs as tj', 'tj.id', '=', 'tt.transport_job_id')
                                  ->leftJoin('service_request as sr', 'sr.id', '=', 'tj.service_request_id')
                                  ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                  ->leftJoin('routes as r', 'r.id', '=', 'srt.route_id')
                                  ->leftJoin('party as p', 'p.id', '=', 'trip_voucher.fuel_party_id')
                                  ->leftJoin('party as bp', 'bp.id', '=', 'sr.billing_party_id')
                                  ->select(
                                    'trip_voucher.id',
                                    'trip_voucher.voucher_no',
                                    'trip_voucher.qty',
                                    'trip_voucher.additional_qty',
                                    'trip_voucher.diesel_rate',
                                    'trip_voucher.diesel_date',
                                    'trip_voucher.total_amount',
                                    'trip_voucher.payment_type_id',
                                    'trip_voucher.cash_amount',
                                    'trip_voucher.additional_cash_amount',
                                    'trip_voucher.card_amount',
                                    'trip_voucher.additional_card_amount',
                                    'trip_voucher.is_party_advance',
                                    'tj.job_no',
                                    \DB::raw("(trip_voucher.cash_amount + trip_voucher.additional_cash_amount) as amount"),
                                    \DB::raw("(trip_voucher.card_amount + trip_voucher.additional_card_amount) as card"),
                                    'tt.lr_no',
                                    'tt.is_market_lr',
                                    'v.registration_no as vehicle_no',
                                    'v.type as vehicle_owner',
                                    'r.from_place',
                                    'r.to_place',
                                    'r.back_place',
                                    'p.tally_name as fuel_party_name',
                                    'bp.name as billing_party_name',
                                    'trip_voucher.voucher_date as voucher_date',
                                    'trip_voucher.card_date as card_date',
                                    \DB::raw("DATE_FORMAT(trip_voucher.voucher_entry_date,'%d/%m/%Y') as voucher_entry_date"),
                                    'tpt.name as payment_type',
                                    'tpt.tally_ledger as payment_tally_ledger',
                                    'trip_voucher.total_amount as total_amount',
                                    )
                                  ->whereIn('trip_voucher.id', array_filter($ids))
                                  ->get();
        
            $xmlData = '<ENVELOPE>
                    <HEADER>
                    <TALLYREQUEST>Import Data</TALLYREQUEST>
                    </HEADER>
                    <BODY>
                    <IMPORTDATA>
                    <REQUESTDESC>
                    <REPORTNAME>All Masters</REPORTNAME>
                    <STATICVARIABLES>
                        <SVCURRENTCOMPANY>ACTIVE  CARGO  MOVERS -  (from 1-Apr-2021)</SVCURRENTCOMPANY>
                    </STATICVARIABLES>
                    </REQUESTDESC>
                    <REQUESTDATA>';
              
                            // var_dump($trips);
                            foreach($data as $key => $t){
                              
                                $dt = (date('m',strtotime($t->voucher_date))<'04') ? date('y',strtotime($t->voucher_date. ' -1 year')) : date('y',strtotime($t->voucher_date));
                                $fyear = $dt.'-'.($dt+1);
                                //$pump = ($t->branch_pump == 84?'Shree Vinayak Petroleum Kapaya (Transport)':($t->branch_pump == 775 ? 'Ram Petroleum':$this->get_party($t->branch_pump->name)));
                                $ledger_name = '';
                                $final_amount = $t->total_amount;

                                if($t->is_party_advance == 1){
                                    $voucher_type ='Journal';

                                      if($t->vehicle_owner == 'market' || $t->vehicle_owner == 'group'){
                                        $ledger_name = 'Truck/Trailor Adv';
                                        $party_ledger = 'Cash';
                                    }else{
                                        $ledger_name = $t->payment_tally_ledger; 
                                        $party_ledger = $t->fuel_party_name;
                                    }
                                }else{
                                    if($t->amount > 0){
                                        $party_ledger = 'Cash';
                                        if($t->is_party_advance == 1){
                                          $voucher_type ='Journal';
                                        }else{
                                          $voucher_type ='Payment';
                                        }
                                    
                                    }else{
                                        $party_ledger = $t->fuel_party_name;
                                        if($t->fuel_party_name == 'Cash'){
                                          $voucher_type ='Payment';
                                        }else{
                                          $voucher_type ='Journal';
                                        }
                                    }

                                  
                                    $pump = $t->fuel_party_name;
                                    if($t->vehicle_owner == 'market' || $t->vehicle_owner == 'group' || $t->is_party_advance == 1){
                                        $ledger_name = 'Truck/Trailor Adv';
                                    }else{
                                        $ledger_name = $t->payment_tally_ledger; 
                                    }
                                }

                                if($ledger_name == 'Truck Driver Salary Advance/Payable'){
                                    $narration = substr($t->vehicle_no,-6).'/'.date('My',strtotime($t->voucher_date));
                                }else{
                                    $narration = substr($t->vehicle_no,-6).'/'.$t->lr_no.'/'.$fyear;
                                }
                                
                                $xmlData .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
                                    <VOUCHER REMOTEID="" VCHKEY="4396b940-a1b7-11db-8ec9-00148597b90a-0000abe5:'.$t->ref_no.'" VCHTYPE="'.($final_amount > 0 ?"Payment":"Journal").'" ACTION="Create" OBJVIEW="Accounting Voucher View">
                                    <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                    </OLDAUDITENTRYIDS.LIST>
                                    <DATE>'.date('Ymd',strtotime($t->voucher_date)).'</DATE>
                                    <GUID></GUID>
                                    <NARRATION>Being     Trip   Adv '.$t->payment_type.'   from '.$t->from_place.' to '.$t->to_place.'   As   Per   V   No  '.$t->voucher_no.'  AND LR NO. '.$t->lr_no.'</NARRATION>
                                    <VOUCHERTYPENAME>'.$voucher_type.'</VOUCHERTYPENAME>
                                    <PARTYLEDGERNAME>'.$party_ledger.'</PARTYLEDGERNAME>
                                    <CSTFORMISSUETYPE/>
                                    <CSTFORMRECVTYPE/>
                                    <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                                    <PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>
                                    <VCHGSTCLASS/>
                                    <ENTEREDBY>vipul</ENTEREDBY>
                                    <DIFFACTUALQTY>No</DIFFACTUALQTY>
                                    <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
                                    <ASORIGINAL>No</ASORIGINAL>
                                    <AUDITED>No</AUDITED>
                                    <FORJOBCOSTING>No</FORJOBCOSTING>
                                    <ISOPTIONAL>No</ISOPTIONAL>
                                    <EFFECTIVEDATE>'.date('Ymd',strtotime($t->voucher_date)).'</EFFECTIVEDATE>
                                    <USEFOREXCISE>No</USEFOREXCISE>
                                    <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
                                    <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
                                    <USEFORINTEREST>No</USEFORINTEREST>
                                    <USEFORGAINLOSS>No</USEFORGAINLOSS>
                                    <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
                                    <USEFORCOMPOUND>No</USEFORCOMPOUND>
                                    <USEFORSERVICETAX>No</USEFORSERVICETAX>
                                    <ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>
                                    <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
                                    <USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>
                                    <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
                                    <EXCISEOPENING>No</EXCISEOPENING>
                                    <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
                                    <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
                                    <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
                                    <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
                                    <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
                                    <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
                                    <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
                                    <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
                                    <ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>
                                    <ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>
                                    <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
                                    <ISISDVOUCHER>No</ISISDVOUCHER>
                                    <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
                                    <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
                                    <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
                                    <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
                                    <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
                                    <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
                                    <ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>
                                    <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
                                    <ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>
                                    <ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>
                                    <ISCANCELLED>No</ISCANCELLED>
                                    <HASCASHFLOW>No</HASCASHFLOW>
                                    <ISPOSTDATED>No</ISPOSTDATED>
                                    <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                                    <ISINVOICE>No</ISINVOICE>
                                    <MFGJOURNAL>No</MFGJOURNAL>
                                    <HASDISCOUNTS>No</HASDISCOUNTS>
                                    <ASPAYSLIP>No</ASPAYSLIP>
                                    <ISCOSTCENTRE>No</ISCOSTCENTRE>
                                    <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
                                    <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
                                    <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
                                    <ISVOID>No</ISVOID>
                                    <ISONHOLD>No</ISONHOLD>
                                    <ORDERLINESTATUS>No</ORDERLINESTATUS>
                                    <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
                                    <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
                                    <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
                                    <VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>
                                    <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
                                    <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
                                    <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
                                    <ISDELETED>No</ISDELETED>
                                    <CHANGEVCHMODE>No</CHANGEVCHMODE>
                                    <ALTERID> 1197537</ALTERID>
                                    <MASTERID> 883198</MASTERID>
                                    <VOUCHERKEY>189000035861136</VOUCHERKEY>
                                    <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
                                    <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
                                    <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
                                    <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
                                    <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
                                    <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
                                    <EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>
                                    <INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
                                    <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
                                    <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
                                    <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
                                    <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
                                    <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
                                    <ALLLEDGERENTRIES.LIST>
                                        <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                        </OLDAUDITENTRYIDS.LIST>
                                        <LEDGERNAME>'.$ledger_name.'</LEDGERNAME>
                                        <GSTCLASS/>
                                        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                                        <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                        <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                        <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
                                        <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                        <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                        <AMOUNT>-'.($final_amount).'</AMOUNT>
                                        <VATEXPAMOUNT>-'.($final_amount).'</VATEXPAMOUNT>
                                        <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                                        <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                                        <BILLALLOCATIONS.LIST>
                                        <NAME>'.$narration.'</NAME>
                                        <BILLTYPE>New Ref</BILLTYPE>
                                        <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                        <AMOUNT>-'.($final_amount).'</AMOUNT>
                                        <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                                        <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                                        </BILLALLOCATIONS.LIST>
                                        <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                                        <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                                        <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                                        <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                                        <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                                        <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                                        <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                                        <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                                        <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                                        <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                                        <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                                        <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                                        <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                                        <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                                        <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                                        <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                                        <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                                        <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                                        <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                                        <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                                    </ALLLEDGERENTRIES.LIST>
                                    ';
                                    if($t->card > 0 && $t->is_party_advance != 1){
                                        $xmlData .= '<ALLLEDGERENTRIES.LIST>
                                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                            </OLDAUDITENTRYIDS.LIST>
                                            <LEDGERNAME>Va Tech Venture Pvt Ltd</LEDGERNAME>
                                            <GSTCLASS/>
                                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                            <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                            <AMOUNT>'.$t->card.'</AMOUNT>
                                            <VATEXPAMOUNT>'.$t->card.'</VATEXPAMOUNT>
                                            <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                                            <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                                            <BILLALLOCATIONS.LIST>
                                            <NAME>'.date('d-m-y',strtotime($t->card_date)).'</NAME>
                                            <BILLCREDITPERIOD JD="44004" P="50 Days">50 Days</BILLCREDITPERIOD>
                                            <BILLTYPE>Agst Ref</BILLTYPE>
                                            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                            <AMOUNT>'.$t->card.'</AMOUNT>
                                            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                                            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                                            </BILLALLOCATIONS.LIST>
                                            <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                                            <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                                            <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                                            <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                                            <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                                            <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                                            <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                                            <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                                            <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                                            <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                                            <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                                            <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                                            <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                                            <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                                            <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                                            <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                                            <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                                            <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                                            <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                                            <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                                        </ALLLEDGERENTRIES.LIST>';
                                    }
                                    if($t->amount > 0 && $t->payment_type != 'Fastag' && $t->is_party_advance != 1){
                                        $xmlData .= '<ALLLEDGERENTRIES.LIST>
                                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                            </OLDAUDITENTRYIDS.LIST>
                                            <LEDGERNAME>Cash</LEDGERNAME>
                                            <GSTCLASS/>
                                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                            <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                            <AMOUNT>'.$t->amount.'</AMOUNT>
                                            <VATEXPAMOUNT>'.$t->amount.'</VATEXPAMOUNT>
                                            <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                                            <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                                            <BILLALLOCATIONS.LIST>
                                            <NAME>'.date('d-m-y',strtotime($t->voucher_date)).'</NAME>
                                            <BILLCREDITPERIOD JD="44004" P="50 Days">50 Days</BILLCREDITPERIOD>
                                            <BILLTYPE>Agst Ref</BILLTYPE>
                                            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                            <AMOUNT>'.$t->amount.'.00</AMOUNT>
                                            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                                            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                                            </BILLALLOCATIONS.LIST>
                                            <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                                            <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                                            <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                                            <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                                            <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                                            <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                                            <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                                            <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                                            <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                                            <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                                            <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                                            <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                                            <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                                            <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                                            <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                                            <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                                            <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                                            <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                                            <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                                            <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                                        </ALLLEDGERENTRIES.LIST>';
                                    }
                                    if($t->payment_type == 'Fastag' && $t->is_party_advance != 1){
                                        $xmlData .= '<ALLLEDGERENTRIES.LIST>
                                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                            </OLDAUDITENTRYIDS.LIST>
                                            <LEDGERNAME>E Logistic (Fast Tag)</LEDGERNAME>
                                            <GSTCLASS/>
                                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                            <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                            <AMOUNT>'.$t->amount.'</AMOUNT>
                                            <VATEXPAMOUNT>'.$t->amount.'</VATEXPAMOUNT>
                                            <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                                            <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                                            <BILLALLOCATIONS.LIST>
                                            <NAME>'.date('d-m-y',strtotime($t->voucher_date)).'</NAME>
                                            <BILLCREDITPERIOD JD="44004" P="50 Days">50 Days</BILLCREDITPERIOD>
                                            <BILLTYPE>Agst Ref</BILLTYPE>
                                            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                            <AMOUNT>'.$t->amount.'.00</AMOUNT>
                                            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                                            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                                            </BILLALLOCATIONS.LIST>
                                            <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                                            <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                                            <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                                            <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                                            <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                                            <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                                            <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                                            <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                                            <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                                            <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                                            <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                                            <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                                            <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                                            <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                                            <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                                            <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                                            <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                                            <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                                            <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                                            <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                                        </ALLLEDGERENTRIES.LIST>';
                                    }
                                    if($t->payment_type == 'Diesel' && $t->is_party_advance != 1){
                                        $xmlData .= '<ALLLEDGERENTRIES.LIST>
                                            <OLDAUDITENTRYIDS.LIST TYPE="Number">
                                            <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                                            </OLDAUDITENTRYIDS.LIST>
                                            <LEDGERNAME>'.$pump.'</LEDGERNAME>
                                            <GSTCLASS/>
                                            <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                            <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                            <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                            <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                            <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                                            <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                                            <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                                            <AMOUNT>'.$final_amount.'</AMOUNT>
                                            <VATEXPAMOUNT>'.$final_amount.'</VATEXPAMOUNT>
                                            <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                                            <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                                            <BILLALLOCATIONS.LIST>
                                            <NAME>'.date('d-m-y',strtotime($t->diesel_date)).'</NAME>
                                            <BILLCREDITPERIOD JD="44004" P="50 Days">50 Days</BILLCREDITPERIOD>
                                            <BILLTYPE>Agst Ref</BILLTYPE>
                                            <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                                            <AMOUNT>'.$final_amount.'</AMOUNT>
                                            <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                                            <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                                            </BILLALLOCATIONS.LIST>
                                            <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                                            <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                                            <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                                            <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                                            <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                                            <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                                            <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                                            <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                                            <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                                            <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                                            <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                                            <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                                            <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                                            <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                                            <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                                            <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                                            <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                                            <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                                            <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                                            <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                                        </ALLLEDGERENTRIES.LIST>';

                                    }
                  if($t->is_party_advance == 1){
                    $xmlData .= '<ALLLEDGERENTRIES.LIST>
                      <OLDAUDITENTRYIDS.LIST TYPE="Number">
                      <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
                      </OLDAUDITENTRYIDS.LIST>
                      <LEDGERNAME>Truck/Trailor Adv</LEDGERNAME>
                      <GSTCLASS/>
                      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                      <LEDGERFROMITEM>No</LEDGERFROMITEM>
                      <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                      <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                      <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
                      <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
                      <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
                      <AMOUNT>'.$final_amount.'</AMOUNT>
                      <VATEXPAMOUNT>'.$final_amount.'</VATEXPAMOUNT>
                      <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
                      <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
                      <BILLALLOCATIONS.LIST>
                      <NAME>'.str_replace("&","&amp;",$t->billing_party_name).'/'.$t->lr_no.'/22-23</NAME>
                      <BILLCREDITPERIOD JD="44004" P="50 Days">50 Days</BILLCREDITPERIOD>
                      <BILLTYPE>Agst Ref</BILLTYPE>
                      <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
                      <AMOUNT>'.$final_amount.'</AMOUNT>
                      <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
                      <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
                      </BILLALLOCATIONS.LIST>
                      <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
                      <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
                      <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
                      <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
                      <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
                      <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
                      <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
                      <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
                      <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
                      <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
                      <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
                      <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
                      <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
                      <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
                      <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
                      <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
                      <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
                      <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
                      <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
                      <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
                    </ALLLEDGERENTRIES.LIST>';

                  }
                                    $xmlData .= '<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
                                    <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
                                    <GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
                                    <GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
                                    <TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
                                    </VOUCHER>
                                </TALLYMESSAGE>
                                ';
                            }
                            $xmlData .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
                            <COMPANY>
                             <REMOTECMPINFO.LIST MERGE="Yes">
                              <NAME>4396b940-a1b7-11db-8ec9-00148597b90a</NAME>
                              <REMOTECMPNAME>ACTIVE  CARGO  MOVERS -  (from 1-Apr-2021)</REMOTECMPNAME>
                              <REMOTECMPSTATE>Gujarat</REMOTECMPSTATE>
                             </REMOTECMPINFO.LIST>
                            </COMPANY>
                           </TALLYMESSAGE>
                        </REQUESTDATA>
                        </IMPORTDATA>
                        </BODY>
                    </ENVELOPE>';
        
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?> \n <Document xmlns=\"urn:iso:std:iso:20022:tech:xsd:pain.008.001.02\">" .$xmlData;

          return response($xmlData)->header('Content-type', 'text/xml')->header('Content-Disposition', 'attachment; filename="'.date('d-m-y').'_voucher.xml"');
    }

} //class close