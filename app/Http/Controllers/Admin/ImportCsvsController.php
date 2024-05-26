<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use DataTables;

use App\Models\Routes;
use App\Models\RouteRates;
use App\Models\Parties;
use App\Models\Vehicles;
use App\Models\TransportTrips;
use App\Models\TransportTripVouchers;
use App\Models\AccountBook;
use App\Models\AccountType;
use App\Models\TransactionHead;

class ImportCsvsController extends Controller
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
    
    function csvToArray($filename = '', $delimiter = ','){
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }


    public function importTrips(Request $request) {
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
    
        //$file = public_path('file/test.csv');
        //$filename = 'trip_import_csv.csv';
        $filename = 'hrd_trip_data_19_06_23.csv';
        $tripData = $this->csvToArray(public_path('/import_doc/'.$filename));
        
        foreach ($tripData as $key => $row) {

            // print_r('<pre>');
            // print_r($row);
            // exit;
            
            //$route_name=str_replace(' ', '', $row['ROUTE']);
            $route_name=$row['ROUTE'];
            $route_arr=($route_name!='')?explode(' - ', $row['ROUTE']):[];

            $route_from_place = (isset($route_arr) && isset($route_arr[0])) ? $route_arr[0]:NULL;
            $route_destination_1 = (isset($route_arr) && isset($route_arr[1])) ? $route_arr[1]:NULL;
            $route_destination_2 = (isset($route_arr) && isset($route_arr[2])) ? $route_arr[2]:NULL;
            $route_destination_3 = (isset($route_arr) && isset($route_arr[3])) ? $route_arr[3]:NULL;
            $routeWhareStr = ''; 
            if(count($route_arr)==3){
                $routeWhareStr="(routes.from_place='".$route_from_place."' AND 
                                                              routes.destination_1='".$route_destination_1."' AND 
                                                              routes.destination_2='".$route_destination_2."' AND 
                                                              routes.destination_3 IS NULL
                                                            ) ";
            }elseif(count($route_arr)==2){
                $routeWhareStr="(routes.from_place='".$route_from_place."' AND 
                                                              routes.destination_1='".$route_destination_1."' AND 
                                                              routes.destination_2 IS NULL AND 
                                                              routes.destination_3 IS NULL
                                                            ) ";
            }

            $bill_party_name=$row['BILLING_PARTY'];
            $order_type=($row['ORDER_TYPE']=='FACTORY ORDER')?'Factory Order':'Party Order';
            $transporter_type=($row['TRANSPORTER_TYPE']=='MARKET VEHICLE')?'Transporter':'Group Transporter';
            $transporter=$row['TRANSPORTER'];
            $vehicle_no=$row['VEHICLE_NO'];
            $market_rate=$row['MARKET_RATE'];
            $market_freight=$row['MARKET_FREIGHT'];
            $factory_lr=$row['FACTORY_LR'];
            $gross_weight=$row['GROSS_WEIGHT'];
            $tare_weight=$row['TARE_WEIGHT'];
            $net_weight=$row['NET_WEIGHT'];
            $unload_weight=$row['UNLOAD_WEIGHT'];
            $short_weight=$row['SHORT_WEIGHT'];
            $damage_amount=$row['DAMAGE'];
            $shortage_amount=$row['SHORT'];
            $tds_amount=$row['TDS'];

           
            if ($transporter_type == 'Transporter') {
                $ledger_type_id = 2;
            }else{
                $ledger_type_id = 3;
            }

            // print_r('<pre>');
            // print_r($route_from_place);
            //  print_r('<br>');
            // print_r($route_destination_1);
            //  print_r('<br>');
            // print_r($routeWhareStr);
            // //print_r($routeData);
            // print_r($factory_lr);
            // print_r('<br>');
            // print_r('<br><br>');
            $routeData = Routes::whereRaw($routeWhareStr)->first();
            $route_id = ($routeData)?$routeData->id:'';
                
            
            $billingPartyData = Parties::whereRaw("party.ledger_type_id='1' AND party.name LIKE '%".$bill_party_name."%' ")->first();
            $billing_party_id = ($billingPartyData)?$billingPartyData->id:'';

            $routeRateData = RouteRates::where('route_id',$route_id)->where('party_id',$billing_party_id)->first();
            $billing_rate = ($routeRateData)?$routeRateData->rate:'';
            

            $transporterData = Parties::whereRaw("party.ledger_type_id='".$ledger_type_id."' AND party.name LIKE '%".$transporter."%' ")->first();
            $transporter_id = ($transporterData)?$transporterData->id:'';

            if($transporter_id!=''){
                $vehicleData = Vehicles::whereRaw(" registration_no LIKE '%".$vehicle_no."%' ")->first();
                if($vehicleData){
                    $vehicle_id = ($vehicleData)?$vehicleData->id:'';
                
                }else{

                    $new_vehicle_data = array(
                                              'registration_no' =>$vehicle_no,
                                              'party_id'        =>$transporter_id,
                                              'type'            =>'market',
                                              'vehicle_status'  => 'Available'
                                            );
                    $vehicle_id = Vehicles::create($new_vehicle_data)->id;
                }
            }

            if($billing_party_id!='' && $transporter_id!='' && $vehicle_id!=''){
                $getTripData = TransportTrips::select('lr_no')->orderBy('lr_no','DESC')->first();
                $lr_no=(!empty($getTripData))?($getTripData->lr_no+1):1;
                //date("Y-m-d",strtotime(str_replace('/', '-', $row['LR_DATE'] )))
                $billing_freight = $billing_rate * $net_weight;
                $insertData = array(
                                    "entry_date"    => date('Y-m-d H:i:s'),
                                    "lr_date"       => $row['LR_DATE'],
                                    "lr_no"         => $lr_no,
                                    "order_type"    => $order_type,
                                    "party_id"      => $billing_party_id,
                                    "route_id"      => $route_id,
                                    "transporter_type"  => $transporter_type,
                                    "transporter_id"    => $transporter_id,
                                    "vehicle_id"        => $vehicle_id,
                                    "market_rate"       => $market_rate,
                                    "billing_rate"       => $billing_rate,
                                    "factory_lr"        => $factory_lr,
                                    "gross_weight"      => $gross_weight,
                                    "tare_weight"       => $tare_weight,
                                    "net_weight"        => $net_weight,
                                    "unload_weight"     => $unload_weight,
                                    "short_weight"      => $short_weight,
                                    "damage_amount"     => $damage_amount,
                                    "shortage_amount"   => $shortage_amount,
                                    "tds_amount"        => $tds_amount,
                                    "market_freight"    => $market_freight,
                                    "billing_freight"    => round($billing_freight,2),
                                    "remain_market_freight"     => $market_freight,
                                 );
                
               
                TransportTrips::insert($insertData);    
            }else{
                print_r($factory_lr);
                print_r('<br>');
                // print_r('<br>');
                // print_r($bill_party_name);
                // print_r('<br>');
                // print_r($transporter_id);
                // print_r('<br>');
                // print_r($transporter);
                // print_r('<br>');
                // print_r($vehicle_id);
                // print_r('<br>');
                // print_r($vehicle_no);
                // print_r('<br>');
                // print_r('<br>');
                // print_r('<br>');
            }   
            
        } //loop close
        echo 'trips imported';
    } //func close

    function importTripVoucher(){
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
    
        //$file = public_path('file/test.csv');
        $filename = 'CASH_DEPOSTI.csv';
        $tripVoucherData = $this->csvToArray(public_path('/import_doc/'.$filename));
        
        $message='';
        print_r('<pre>');

        foreach ($tripVoucherData as $key => $row) {
            print_r('<pre>');
            print_r($row);
            exit;

            $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;

            $transporter=$row['TRANSPORTER'];
            $vehicle_no=$row['VEHICLE_NO'];
            $lr_no=$row['FACTORY_LR'];
            $payment_date=$row['PAYMENT_DATE'];
            $payment_type=$row['PAYMENT_TYPE'];
            $payment_mode=$row['PAYMENT_MODE'];
            $amount=$row['AMOUNT'];

            $transporterData = Parties::whereRaw("party.name LIKE '%".$transporter."%' ")->first();
            $transporter_id = ($transporterData)?$transporterData->id:'';
            
            $tripData = TransportTrips::where('factory_lr',$lr_no)->first();
            $trip_id = ($tripData)?$tripData->id:'';
            $vehicle_id = ($tripData)?$tripData->vehicle_id:'';

            if($transporter_id!='' && $trip_id!=''){
                $vehicleData = Vehicles::where('id',$vehicle_id)->first();
                // $vehicleData = Vehicles::whereRaw(" registration_no LIKE '%".$vehicle_no."%' ")->first();
                // if($vehicleData){
                //     $vehicle_id = ($vehicleData)?$vehicleData->id:'';
                // }else{

                //     $new_vehicle_data = array(
                //                               'registration_no' =>$vehicle_no,
                //                               'party_id'        =>$transporter_id,
                //                               'type'            =>'market',
                //                               'vehicle_status'  =>'Available'
                //                             );
                //     $vehicle_id = Vehicles::create($new_vehicle_data)->id;
                // }

                $voucherData=array(
                    'voucher_no'        => $voucher_no,
                    'voucher_entry_date'=> date("Y-m-d",strtotime(str_replace('/', '-', $payment_date ))),
                    'voucher_date'      => date("Y-m-d",strtotime(str_replace('/', '-', $payment_date ))),
                    'transporter_id'    => $transporter_id,
                    'vehicle_id'        => $vehicle_id,
                    'trip_id'           => $trip_id,
                    'payment_mode'      => $payment_mode,
                    'payment_type_id'   => ($payment_type=='ADVANCE ' || $payment_type=='ADVANCE')?1:2,
                    'amount'            => $amount,
                    'voucher_created_by'=> Auth::user()->id,
                );

                print_r('<br>');
                print_r($key);
                print_r($voucherData);
                print_r('<br>');
                print_r('<br>');
            
                $voucher_id=TransportTripVouchers::create($voucherData)->id; 

                //add in account book 
                $transporterData=Parties::find($transporter_id);
                $tripData=TransportTrips::find($trip_id);
                $vehicleData=Vehicles::find($vehicle_id);

                $account_type_id = '1';
                $head_type_id = '117';
                if ($payment_mode=='HDFC') {
                    $account_type_id = '2';
                    $head_type_id = '118';
                }elseif ($payment_mode=='AXIS') {
                    $account_type_id = 'AXIS';
                    $head_type_id = '118';
                }

                $accounEntryData=array(
                    'voucher_id'        =>$voucher_id,
                    'entry_date'        =>date("Y-m-d",strtotime(str_replace('/', '-', $payment_date ))),
                    'party_id'          =>$transporter_id,
                    'transaction_type'  =>($payment_mode=='CASH')?'Cash':'Bank',
                    'account_type_id'   =>$account_type_id,
                    'type'              =>'Expense',
                    'head_type_id'      =>$head_type_id,
                     'amount'           => $amount,
                     'narration'        =>$transporterData->name.' - Trip Date - '.date('d/m/Y',strtotime($tripData->lr_date)).' - Factory LR - '.$tripData->factory_lr.' - Vehicle - '.$vehicleData->registration_no,
                );
                
                AccountBook::create($accounEntryData);    
                //add in account book     

                $message='trip voucher imported';

            } //if close

        }//loop cose

        echo $message;

    } //func close

    function importAccountBookData(){
       date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
    
        //$file = public_path('file/test.csv');
        $filename = 'CASH_DEPOSTI.csv';
        $importData = $this->csvToArray(public_path('/import_doc/'.$filename));
        
        $message='';
        foreach ($importData as $key => $row) {
            // print_r('<pre>');
            // print_r($row);
            // exit;
            
            $party_name=$row['PARTY'];
            $account_type=$row['ACCOUNT_TYPE'];
            $head_type=$row['TRANSACTION_HEAD'];
            $transaction_type=($row['TRANSACTION_TYPE']=='BANK')?'Bank':'Cash';
            $type=($row['PARTICULARS']=='EXPENSE')?'Expense':'Income';

            $payment_date=$row['PAYMENT_DATE'];
            $amount=$row['AMOUNT'];
            $branch=$row['BRANCH'];
            $narration=$row['NARRATION'];
            $remarks=$row['REMARKS'];

            $partiesData = Parties::whereRaw("party.name LIKE '%".trim($party_name)."%' ")->first();
            $party_id = ($partiesData)?$partiesData->id:'';

            $acTypeData = AccountType::where('name',trim($account_type))->first();
            $account_type_id = ($acTypeData)?$acTypeData->id:'';

            $transHeadData = TransactionHead::where('type',trim($type))->where('transaction_type',trim($transaction_type))->where('name',trim($head_type))->first();
        
            $head_type_id = ($transHeadData)?$transHeadData->id:'';
            
            if($party_id!=''){
                $insertData=array(
                            'entry_date'        =>date("Y-m-d",strtotime(str_replace('/', '-', $payment_date ))),
                            'party_id'          =>$party_id,
                            'transaction_type'  =>$transaction_type,
                            'account_type_id'   =>$account_type_id,
                            'type'              =>$type,
                            'head_type_id'      =>$head_type_id,
                            'amount'            =>$amount,
                            'branch'            =>$branch,
                            'narration'         =>$narration,
                            'remarks'           =>$remarks,
                            'created_by'        =>Auth::user()->id,
                        );
                
                
                AccountBook::create($insertData);
               $message='account book data imported'; 
            }else{
               //  print_r('<pre>');
               //  print_r($account_type);
               // print_r('<br>');
               // print_r('<br>');
               //  print_r($type);
               //  print_r('<br>');
               //  print_r($transaction_type);
               //  print_r('<br>');
               //  print_r($head_type);
                print_r('<br>');
                print_r($row);
                print_r('<br><br><br>');
                
            }
        } //loop close

        echo $message;

    } //func close


    function tallyVoucherXml(){

        $filename = 'rec8.csv';
        $data = $this->csvToArray(public_path('/import_doc/'.$filename)); 
        
        $xmlData = '<ENVELOPE>
                    <HEADER>
                    <TALLYREQUEST>Import Data</TALLYREQUEST>
                    </HEADER>
                    <BODY>
                    <IMPORTDATA>
                    <REQUESTDESC>
                    <REPORTNAME>Vouchers</REPORTNAME>
                    <STATICVARIABLES>
                        <SVCURRENTCOMPANY>Guru Logistics</SVCURRENTCOMPANY>
                    </STATICVARIABLES>
                    </REQUESTDESC>
                    <REQUESTDATA>';
                            $voucher = 60;
                            // var_dump($trips);
                            foreach($data as $key => $t){
                                $voucher_no = $voucher+$key;
                                if($t['PARTICULARS']!=''){
                                    $xmlData .= '<TALLYMESSAGE xmlns:UDF="TallyUDF">
                                     <VOUCHER REMOTEID="a922935c-b180-478b-8648-c1d2a8172263-00000136'.rand(2,5000).'" VCHTYPE="Receipt" ACTION="Create">
                                      
                                        <DATE>'.date('Ymd',strtotime($t['Date'])).'</DATE> 
                                      <GUID>a922935c-b180-478b-8648-c1d2a8172263-00000136</GUID>
                                      <NARRATION>Ch. No. :</NARRATION>
                                      <VOUCHERTYPENAME>Receipt</VOUCHERTYPENAME>
                                      <VOUCHERNUMBER>'.$voucher_no.'</VOUCHERNUMBER>
                                      <PARTYLEDGERNAME>'.$t['PARTICULARS'].'</PARTYLEDGERNAME>
                                      <CSTFORMISSUETYPE/>
                                      <CSTFORMRECVTYPE/>
                                      <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
                                      <VCHGSTCLASS/>
                                      <DIFFACTUALQTY>No</DIFFACTUALQTY>
                                      <AUDITED>No</AUDITED>
                                      <FORJOBCOSTING>No</FORJOBCOSTING>
                                      <ISOPTIONAL>No</ISOPTIONAL>
                                      <EFFECTIVEDATE>'.date('Ymd',strtotime($t['Date'])).'</EFFECTIVEDATE>
                                      <USEFORINTEREST>No</USEFORINTEREST>
                                      <USEFORGAINLOSS>No</USEFORGAINLOSS>
                                      <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
                                      <USEFORCOMPOUND>No</USEFORCOMPOUND>
                                      <ALTERID> 351</ALTERID>
                                      <EXCISEOPENING>No</EXCISEOPENING>
                                      <ISCANCELLED>No</ISCANCELLED>
                                      <HASCASHFLOW>Yes</HASCASHFLOW>
                                      <ISPOSTDATED>No</ISPOSTDATED>
                                      <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
                                      <ISINVOICE>No</ISINVOICE>
                                      <MFGJOURNAL>No</MFGJOURNAL>
                                      <HASDISCOUNTS>No</HASDISCOUNTS>
                                      <ASPAYSLIP>No</ASPAYSLIP>
                                      <ISDELETED>No</ISDELETED>
                                      <ASORIGINAL>No</ASORIGINAL>
                                      <ALLLEDGERENTRIES.LIST>
                                       <LEDGERNAME>'.$t['PARTICULARS'].'</LEDGERNAME>
                                       <GSTCLASS/>
                                       <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                       <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                       <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                       <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                       <AMOUNT>'.$t['CR'].'</AMOUNT>
                                      </ALLLEDGERENTRIES.LIST>
                                      <ALLLEDGERENTRIES.LIST>
                                       <LEDGERNAME>Axis Bank C/A No.7504</LEDGERNAME>
                                       <GSTCLASS/>
                                       <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                                       <LEDGERFROMITEM>No</LEDGERFROMITEM>
                                       <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                                       <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                                       <AMOUNT>-'.$t['CR'].'</AMOUNT>
                                      </ALLLEDGERENTRIES.LIST>
                                     </VOUCHER>
                                    </TALLYMESSAGE>';
                                }
                            }
                            $xmlData .= '
                        </REQUESTDATA>
                        </IMPORTDATA>
                        </BODY>
                    </ENVELOPE>';
        
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?> \n <Document xmlns=\"urn:iso:std:iso:20022:tech:xsd:pain.008.001.02\">" .$xmlData;

          return response($xmlData)->header('Content-type', 'text/xml')->header('Content-Disposition', 'attachment; filename="'.date('d-m-y').'_voucher.xml"');
    }

}