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
use App\Models\CompanySettings;
use App\Models\TransportTripVouchers;
use DB;

/*use App\Models\Vehicles;
use App\Models\Routes;
use App\Models\User;
use App\Models\VehicleTypes;*/

class BillsController extends Controller{
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
    
    public function index(){
        $viewData['title']='ALL BILLS';
        // $viewData['parties']=TransportTrips::leftJoin('party','party.id','transport_trips.party_id')
        //                                      ->where('party.ledger_type_id',1)
        //                                                ->select('party.id as id','party.name as name')
        //                                                ->groupby('party.id')
        //                                                ->get();
        $viewData['companiesData'] = CompanySettings::get();                                                      
        return view('admin.bills.bill_list',$viewData);
    }

    function paginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "bills.id !='0'  AND bills.remain_amount !=0";

            $login_user_role = Auth::user()->role_id;
           
            if($request->party_id!= ''){
                $wherestr .= " AND bills.party_id =  '".$request->party_id."'";
            }

            if($request->with_gst!= ''){
                $wherestr .= " AND bills.with_gst =  '".$request->with_gst."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(bills.bill_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(bills.bill_date) <=  '".$request->to_date."'";
            }
            
            $data=Bills::whereRaw($wherestr)->orderby('bills.bill_date','desc');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $BillModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==7;})->first();
                        $btn ='';
                        if(isset($BillModuleRights) && $BillModuleRights->is_edit==1){
        
                            $btn .= '&nbsp;&nbsp;<a href="'.route('bill.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                            } 
                        if(isset($BillModuleRights) && $BillModuleRights->is_delete==1){

                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                        
                        $btn .= '&nbsp;&nbsp;<a class="edit btn btn-warning btn-sm ml-1" onclick="printBill('.$row->id.')"><i class="feather icon-printer" style="color: white;"></i></a>';

                        return $btn;
                    })
                    ->addColumn('bill_date', function($row) {
                      return isset($row->bill_date)?date('d/m/Y',strtotime($row->bill_date)):'';
                    })
                    ->addColumn('party_id', function($row) {
                      return (isset($row->getSelectedParty))?$row->getSelectedParty->name:'';
                    })->addColumn('created_by', function($row) {
                      return (isset($row->getCreatedBy))?$row->getCreatedBy->name:'';
                    })->addColumn('company_id', function($row) {
                      return (isset($row->getSelectedCompany))?$row->getSelectedCompany->company_name:'';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    
    function addData(Request $request){
        if(!empty($request->all())){
            extract($request->all());

            $login_user_id = Auth::user()->id;
            $insertData = array(
                'bill_no'           => $bill_no,
                'bill_date'         => $bill_date,
                'party_id'          => $party_id,
                'company_id'        => $company_id,
                'with_gst'          => $with_gst,
                'total_amount'      => $total_amount,
                'remain_amount'     => $total_amount,
                'created_by'        => $login_user_id,
            );
            
            $bill_id = Bills::create($insertData)->id;

            if($bill_id != ''){
                if(isset($request['tp_id']) && !empty($request['tp_id'])){
                    foreach($tp_id as $key => $trip_id){
                        $freight_val = $freight[$key];
                        $detention_val = $detention[$key];
                        $driver_shortage_val = $driver_shortage[$key];
                        $driver_shortage_amt_val = $driver_shortage_amt[$key];

                        TransportTrips::where('id', $trip_id)->update([
                            'bill_id'               => $bill_id,
                            'freight_rate'       => $freight_val,
                            'detention'       => $detention_val,
                            'driver_shortage'       => $driver_shortage_val,
                            'driver_shortage_amt'   => $driver_shortage_amt_val
                        ]);
                    }
                }

                return redirect('/bill-list')->with('success', 'Bill Added Successfully!!');
            }else{
                return redirect('/bill-list')->with('error', 'Please try again!');
            }
        }

        $viewData['title'] = "ADD BILL";
        $tripPayableIDs = TransportTrips::groupBy('payable_party_id')
                                          ->get(['payable_party_id'])
                                          ->pluck('payable_party_id');
        
        $viewData['partiesData'] = [];
        if(isset($tripPayableIDs) && !empty($tripPayableIDs)){
            $viewData['partiesData'] = Parties::whereIn('id', $tripPayableIDs->toArray())->get(['id','name']);
        }

        $viewData['companiesData'] = CompanySettings::get();                                                      
        
        return view('admin.bills.bill_add', $viewData);
    }

    function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($request->all());

           $updateData=array(
                            'bill_no'           =>$bill_no,
                            'bill_date'         =>$bill_date,
                            'party_id'          =>$party_id,
                            'company_id'        =>$company_id,
                            'with_gst'          =>$with_gst,
                            'total_amount'      =>$total_amount,
                            'remain_amount'     =>$total_amount,
                        );
            $response = Bills::where('id',$id)->update($updateData);
            if($response){
                $bill_id=$id;
                if(isset($request['tp_id']) && !empty($request['tp_id'])){
                   TransportTrips::where('bill_id', $id)->update(['bill_id' => NULL]);

                    foreach($request['tp_id'] as $key => $trip_id){
                        $freight_val = $freight[$key];
                        $detention_val = $detention[$key];
                        $driver_shortage_val = $driver_shortage[$key];
                        $driver_shortage_amt_val = $driver_shortage_amt[$key];

                        TransportTrips::where('id', $trip_id)->update([
                            'bill_id'               => $bill_id,
                            'freight_rate'       => $freight_val,
                            'detention'       => $detention_val,
                            'driver_shortage'       => $driver_shortage_val,
                            'driver_shortage_amt'   => $driver_shortage_amt_val
                        ]);
                    }
                }
           
                return redirect('/bill-list')->with('success', 'Bill Updated Successfully!!');
            }else{
                return redirect('/bill-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = Bills::find($id);
        $viewData['editData'] = $editData;
        $viewData['title']='BILL NO : '.$editData->bill_no;
        
        $tripPayableIDs = TransportTrips::groupBy('payable_party_id')
                                          ->get(['payable_party_id'])
                                          ->pluck('payable_party_id');
        
        $viewData['partiesData']=[];
        if(isset($tripPayableIDs) && !empty($tripPayableIDs)){
            $viewData['partiesData']=Parties::whereIn('id',$tripPayableIDs->toArray())->get(['id','name']);
        }
        
        $viewData['companiesData'] = CompanySettings::get();                                                      
        
        return view('admin.bills.bill_add',$viewData);
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            TransportTrips::where('bill_id',$id)->update(['bill_id' => NULL]);
            $response=Bills::where('id',$id)->delete();
            if($response){
                return redirect('bill-list')->with('success', 'Bill deleted successfully!');
            }else{
                return redirect('bill-list')->with('error', 'Please try again!');
            }
        }
    }
    
    

    function getPartywiseTrips(){
        $wherestr="transport_trips.id!='' ";
        
        if($_POST['bill_id']!= ''){
            $wherestr .= " AND (transport_trips.bill_id =  '".$_POST['bill_id']."' OR transport_trips.bill_id = 0)";
        }else{
            $wherestr .= " AND transport_trips.bill_id IS NULL ";
        }

        if($_POST['company_id']!= ''){
            $wherestr .= " AND transport_trips.company_id =  '".$_POST['company_id']."'";
        }

        if($_POST['party_id']!= ''){
            $wherestr .= " AND transport_trips.payable_party_id =  '".$_POST['party_id']."'";
        }

        if($_POST['from_date']!= ''){
            $wherestr .= " AND DATE(transport_trips.lr_date) >=  '".$_POST['from_date']."'";
        }

        if($_POST['to_date']!= ''){
            $wherestr .= " AND DATE(transport_trips.lr_date) <=  '".$_POST['to_date']."'";
        }

       
        // $response=TransportTrips::leftJoin('vehicles','vehicles.id','=','transport_trips.vehicle_id')
        //                           ->leftJoin('routes','routes.id','=','transport_trips.route_id')
        //                           ->whereRaw($wherestr)
        //                           ->select('transport_trips.id as trip_id',
        //                                     'transport_trips.bill_id',
        //                                     'transport_trips.lr_no',
        //                                     'transport_trips.freight',
        //                                     'transport_trips.container_no',
        //                                     'vehicles.registration_no as vehicle_no',
        //                                     DB::raw("CONCAT(routes.from_place,'-',routes.destination_1,IF(routes.destination_2 IS NOT NULL,CONCAT('-',routes.destination_2),''),IF(routes.destination_3 IS NOT NULL,CONCAT('-',routes.destination_3),'')) as route_name,
        //                                         DATE_FORMAT(transport_trips.lr_date,'%d/%m/%Y') AS lr_date
        //                                         ")
        //                                   )
        //                         ->get();

        $response=TransportTrips::with([
                     'getSelectedVehicle' => function ($query) {
                            $query->select('id','registration_no as vehicle_no');
                     },
                     'getSelectedProduct' => function ($query) {
                        $query->select('id','name as product');
                     },
                     'getSelectedFromStation' => function ($query) {
                        $query->select('id','name as from_station','place_type as state');
                     },
                     'getSelectedToStation' => function ($query) {
                        $query->select('id','name as to_station','place_type as state');
                     },
                     'getSelectedBackToStation' => function ($query) {
                        $query->select('id','name as back_to_station','place_type as state');
                     },
                    
                    ]
                    )
                    ->whereRaw($wherestr)
                    ->get();

        foreach ($response as $key => $trip) {
            $party_advance = TransportTrips::getPartyAdvanced($trip['trip_id']);
            $response[$key]['party_advance'] = $party_advance;
            $response[$key]['vehicle_no'] = ($trip->getSelectedVehicle)?$trip->getSelectedVehicle->vehicle_no:'';
            
            $fromStation = ($trip->getSelectedFromStation)?$trip->getSelectedFromStation->from_station:'';
            $toStation = ($trip->getSelectedToStation)?$trip->getSelectedToStation->to_station:'';
            $backToStation = ($trip->getSelectedBackToStation)?$trip->getSelectedBackToStation->back_to_station:'';
            $product = ($trip->getSelectedProduct)?$trip->getSelectedProduct->product:'';
            
            $routeName=$fromStation.'->'.$toStation;
            if($backToStation!=''){
                $routeName.='->'.$backToStation;
            }
            $routeName.='<br>'.$product;

            $response[$key]['route_name'] = $routeName;
            $response[$key]['freight'] = $trip->freight_rate;
        }
        
        echo json_encode($response);

    } //func close

    function exportPrimaryRecord(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "bills.id !='0' ";
            if(isset($party_id) && $party_id!= ''){
                $wherestr .= " AND bills.party_id =  '".$party_id."'";
            }

            if($with_gst!= ''){
                $wherestr .= " AND bills.with_gst =  '".$with_gst."'";
            }

            if(isset($from_date) && $from_date!= ''){
                $wherestr .= " AND DATE(bills.bill_date) >=  '".$from_date."'";
            }

            if(isset($to_date) && $to_date!= ''){
                $wherestr .= " AND DATE(bills.bill_date) <=  '".$to_date."'";
            }
            

            $data=Bills::whereRaw($wherestr)->select('bills.*')->get();
          
          $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=bills.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


          $columns = array('sr', 'bill_no','bill_date', 'party', 'with_gst', 'total amount', 'total remaining amount','created_by');

          $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                
                fputcsv($file,array($sr,
                                    $row->bill_no,
                                    (isset($row->bill_date))?date('d/m/Y',strtotime($row->bill_date)):'',
                                    (isset($row->getSelectedParty))?$row->getSelectedParty->name:'',
                                    $row->with_gst,
                                    $row->total_amount,
                                    $row->remain_amount,
                                    (isset($row->getCreatedBy))?$row->getCreatedBy->name:'',
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close

    function printBill(Request $request){
        $id = $request->id;
        
        $viewData=[];
        $viewData['BillDetail'] = Bills::where('id',$id)->first();
        $viewData['CompanyData'] = CompanySettings::where('id',$viewData['BillDetail']->company_id)->first();
        $viewData['billingPartyDetail'] = Parties::where('id',$viewData['BillDetail']->party_id)->first();
        $viewData['tripData'] = TransportTrips::where('bill_id',$id)->get();

        return view('admin.bills.bill_print',$viewData);
    }

} //class close