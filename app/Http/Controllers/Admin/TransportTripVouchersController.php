<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\Locations;
use App\Models\Products;
use App\Models\User;
use App\Models\Vehicles;
use App\Models\Units;
use App\Models\Parties;
use App\Models\PartyTypes;
use App\Models\PartySelectedPartyTypes;




use App\Models\TransportTrips;
use App\Models\TransportTripVouchers;
use App\Models\TransportTripPaymentTypes;
use App\Models\AccountBook;


class TransportTripVouchersController extends Controller
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
    
    public function index(){
        $viewData['title']='TRANSPORT TRIP VOUCHER';
        $viewData['parties']=Parties::get()->toArray();

        $viewData['vehicles']=Vehicles::get()->toArray();
        $viewData['users']=User::where('is_authorised',1)->get()->toArray();
        $viewData['paymentTypes']=TransportTripPaymentTypes::get()->toArray();
        $viewData['fuels']=Parties::get()->toArray();
        return view('admin.transport_trip_vouchers.transport_trip_voucher_list',$viewData);
    }

    function TransportTripVoucherPaginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "trip_voucher.id !='0' AND trip_voucher.payment_type_id != '15' ";
            if($request->vehicle_id!= ''){
                $wherestr .= " AND trip_voucher.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->voucher_no!= ''){
                $wherestr .= " AND trip_voucher.voucher_no =  '".$request->voucher_no."'";
            }

            if($request->lr_no!= ''){
                $wherestr .= " AND tt.lr_no =  '".$request->lr_no."'";
            }

            // if($request->job_no!= ''){
            //     $wherestr .= " AND tj.job_no =  '".$request->job_no."'";
            // }

            // if($request->authorised_by!= ''){
            //     $wherestr .= " AND trip_voucher.authorised_by =  '".$request->authorised_by."'";
            // }

            if($request->payment_type_id!= ''){
                $wherestr .= " AND trip_voucher.payment_type_id =  '".$request->payment_type_id."'";
            }

            // if($request->fuel_party_id!= ''){
            //     $wherestr .= " AND trip_voucher.fuel_party_id =  '".$request->fuel_party_id."'";
            // }

            // if($request->payment_by!= ''){
            //     if($request->payment_by == 'Cash'){
            //         $wherestr .= " AND (trip_voucher.cash_amount >  0 OR additional_cash_amount > 0)";
            //     }elseif($request->payment_by == 'Card'){
            //         $wherestr .= " AND (trip_voucher.card_amount >  0 OR additional_card_amount > 0)";
            //     }
            // }

            if($request->is_party_advance!= ''){
                $wherestr .= " AND trip_voucher.is_party_advance =  '".$request->is_party_advance."'";
            }

            if($request->branch!= ''){
                $wherestr .= " AND trip_voucher.branch =  '".$request->branch."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) <=  '".$request->to_date."'";
            }
            
            $data=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                  ->whereRaw($wherestr)
                                  ->select('trip_voucher.*')
                                  ->orderby('id','desc');

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                            $user_id = Auth::user()->id;
                            $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                            $TripVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==5;})->first();
                            $btn ='';
                           // if($row['payment_type_id'] == 2){
                           //      $btn .= '<a class="btn btn-warning btn-sm ml-1" onclick="printDiesel('.$row['id'].')"><i class="feather icon-printer" style="color: white;"></i></a>';
                           // }else{
                           //      $btn .= '<a class="btn btn-warning btn-sm ml-1" onclick="printTrip('.$row['id'].')"><i class="feather icon-printer" style="color: white;"></i></a>';
                           // }
                            if(isset($TripVchModuleRights) && $TripVchModuleRights->is_edit==1){

                                $btn .= '<a href="'.route('transport.trip.voucher.edit',base64_encode($row['id'])).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                            } 
                            if(isset($TripVchModuleRights) && $TripVchModuleRights->is_delete==1){

                            $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                            }
                           
                           return $btn;
                    })
                    ->addColumn('voucher_entry_date', function($row) {
                        return ($row->voucher_entry_date!='')?date('d/m/Y',strtotime($row->voucher_entry_date)):'';
                    })->addColumn('voucher_date', function($row) {
                        return ($row->voucher_date!='')?date('d/m/Y',strtotime($row->voucher_date)):'';
                    })->addColumn('branch_name', function($row) {
                        return $row->branch;
                    })->addColumn('lr_no', function($row) {
                        return (isset($row->getSelectedTransportTrip->lr_no))?$row->getSelectedTransportTrip->lr_no:'';
                    })->addColumn('vehicle_no', function($row) {
                        return (isset($row->getSelectedVehicle->registration_no))?$row->getSelectedVehicle->registration_no:'';
                    })->addColumn('payment_type', function($row) {
                        return (isset($row->getSelectedPaymentType->name))?$row->getSelectedPaymentType->name:'';
                    })->addColumn('fuel_station_id', function($row) {
                        return (isset($row->getSelectedFuelStation->name))?$row->getSelectedFuelStation->name:'';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    
    function deleteTransportTripVoucher(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            // AccountBook::where('voucher_id',$id)->update(['is_delete' => 1]);
            $response=TransportTripVouchers::where('id',$id)->delete();
            if($response){
                return redirect('transport-trip-voucher-list')->with('success', 'Transport Trip Voucher deleted successfully!');
            }else{
                return redirect('transport-trip-voucher-list')->with('error', 'Please try again!');
            }
        }
    }
    
    function TransportTripVoucherAdd(Request $request){
        if(!empty($request->all())){
            
            $getVoucherData = TransportTripVouchers::select('voucher_no')->orderBy('voucher_no','DESC')->first();
            $voucher_no=(!empty($getVoucherData))?($getVoucherData->voucher_no+1):1;

            $login_user_id = Auth::user()->id;
            
            $voucherData=array(
                'trip_id'           => $request->input('trip_id'),
                'is_party_advance'  => isset($_POST['is_party_advance'])?$_POST['is_party_advance']:0,
                'voucher_no'        => $voucher_no,
                'branch'            => $request->input('branch'),
                'voucher_entry_date'=> $request->input('voucher_entry_date'),
                'voucher_date'      => $request->input('voucher_date'),
                'vehicle_id'        => $request->input('vehicle_id'),
                'payment_type_id'   => $request->input('payment_type_id'),
                'payment_mode'      => $request->input('payment_mode'),
                'fuel_station_id'   => $request->input('fuel_station_id'),
                'fuel_qty'          => ($request->fuel_qty != '')?$request->fuel_qty:0,
                'fuel_rate'         =>($request->fuel_rate != '')?$request->fuel_rate:0,
                'amount'            => $request->input('amount'),
                'remarks'            => $request->input('remarks'),
                'voucher_created_by'=> $login_user_id
            );

            $response = TransportTripVouchers::create($voucherData);
            $voucher_id=$response->id;
            if($voucher_id){
                
                return redirect('/transport-trip-voucher-edit/'.base64_encode($voucher_id))->with('success', 'Transport Trip Voucher Added successfully!');
            }else{
                return redirect('/transport-trip-voucher-edit/'.base64_encode($voucher_id))->with('error', 'Please try again!');
            }
        }

        $viewData['title']="ADD TRANSPORTER TRIP VOUCHER";
        
        $viewData['vehicles'] =  TransportTrips::leftjoin('vehicles','vehicles.id','transport_trips.vehicle_id')
                                  ->leftjoin('party','party.id','transport_trips.transporter_id')
                                  ->select('vehicles.id','vehicles.registration_no','transport_trips.is_market_lr','party.name as transporter')
                                  ->groupby('vehicles.id')
                                  ->get();  
        $viewData['paymentTypes']=TransportTripPaymentTypes::get();
        $viewData['fuelStations']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',5)  //5==Fuel Station   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']);   

        //$viewData['users']=User::where('is_authorised',1)->get();
        $viewData['partyTypes']=PartyTypes::get(['id','name']); 
        return view('admin.transport_trip_vouchers.transport_trip_voucher_add',$viewData);
    }

    public function getTransportTrip(Request $request) {
        extract($_POST);
        $login_user_id = Auth::user()->id;
        
        if($trip_id!= ''){
            $wherestr = " (transport_trips.id = ".$trip_id." AND transport_trips.vehicle_id = ".$vehicle_id." )";
        }else{
            $wherestr = "transport_trips.vehicle_id = ".$vehicle_id;
        }
            
        // $trip_list=TransportTrips::leftJoin('routes', 'routes.id', '=', 'transport_trips.route_id')->leftJoin('drivers', 'drivers.id', '=', 'transport_trips.driver_id')
        //                     ->select('transport_trips.*','drivers.name as driver_name',
        //                         \DB::raw("CONCAT(routes.from_place,'-',routes.destination_1,IF(routes.destination_2 IS NOT NULL,CONCAT('-',routes.destination_2),''),IF(routes.destination_3 IS NOT NULL,CONCAT('-',routes.destination_3),'')) as route_name")
        //                     )
        //                     ->whereRaw($wherestr)
        //                     ->where('transport_trips.lr_status',1)
        //                     ->limit(3)
        //                     ->orderby('transport_trips.id','desc')
        //                     ->get()
        //                     ->toArray();

        $trip_list=TransportTrips::leftJoin('drivers', 'drivers.id', '=', 'transport_trips.driver_id')
                                   ->leftJoin('places as from_station', 'from_station.id', '=', 'transport_trips.from_station_id')
                                   ->leftJoin('places as to_station', 'to_station.id', '=', 'transport_trips.to_station_id') 
                                   ->leftJoin('places as back_to_station', 'back_to_station.id', '=', 'transport_trips.back_to_station_id') 
                                   ->whereRaw($wherestr)
                                   ->select('transport_trips.id',
                                            'transport_trips.lr_no',
                                            'transport_trips.lr_date',
                                            'drivers.id as driver_id',
                                            'drivers.name as driver_name',
                                            'from_station.name as from_station',
                                            'to_station.name as to_station',
                                            'back_to_station.name as back_to_station'
                                            )
                                   ->orderby('transport_trips.id','desc')->get();
        echo json_encode($trip_list);

    }

  /*  public function getBudgetedByTrip(Request $request) {
        extract($_POST);
        $tripData = TransportTrips::where('id',$trip_id)->first();
        $data = array();

        if($voucher_id != ''){
            $old_trip_list=TransportTripVouchers::select(
                                    \DB::raw(" SUM(qty) as total_qty"),
                                    \DB::raw(" SUM(cash_amount + card_amount) as total_amount"),
                                )
                                ->where('id',$voucher_id)
                                ->first();

            $data['remain_budgeted_advance'] = (isset($tripData->remain_budgeted_advance)?$tripData->remain_budgeted_advance:0) + $old_trip_list->total_amount;
            
            $data['remain_budgeted_diesel'] = (isset($tripData->remain_budgeted_diesel)?$tripData->remain_budgeted_diesel:0) + $old_trip_list->total_qty;
            $data['remain_budgeted_sez'] = (isset($tripData->remain_budgeted_sez)?$tripData->remain_budgeted_sez:0) + $old_trip_list->total_amount;

        }else{
            $data['remain_budgeted_advance'] = isset($tripData->remain_budgeted_advance)?$tripData->remain_budgeted_advance:0;
            $data['remain_budgeted_diesel'] = isset($tripData->remain_budgeted_diesel)?$tripData->remain_budgeted_diesel:0;
            $data['remain_budgeted_sez'] = isset($tripData->remain_budgeted_sez)?$tripData->remain_budgeted_sez:0;
            
        }


        echo json_encode($data);
       
    }
*/
    public function TransportTripVoucherEdit($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            
            $voucherData=array(
                'trip_id'           => $trip_id,
                'is_party_advance'  => isset($is_party_advance)?$is_party_advance:0,
                'branch'            => $branch,
                'voucher_entry_date'=> $voucher_entry_date,
                'voucher_date'      => $voucher_date,
                'vehicle_id'        => $vehicle_id,
                'payment_type_id'   => $payment_type_id,
                'payment_mode'      => $request->input('payment_mode'),
                'fuel_station_id'   => $request->input('fuel_station_id'),
                'fuel_qty'          => $request->input('fuel_qty'),
                'fuel_rate'         => $request->input('fuel_rate'),
                'amount'            => $request->input('amount'),
                'remarks'            => $request->input('remarks'),
            );
            
            $res=TransportTripVouchers::where('id',$id)->update($voucherData);
            
            if($res!= ''){
                return redirect('/transport-trip-voucher-list')->with('success', 'Transport Trip Voucher Updated successfully!');
            }else{
                return redirect('/transport-trip-voucher-list')->with('error', 'Please try again!');
            }

        }  //if close

        $viewData['editData'] = TransportTripVouchers::select('trip_voucher.*')->where('trip_voucher.id',$id)->first();
        
        $viewData['title']='VOUCHER NO : '.$viewData['editData']->voucher_no;

        
        $viewData['vehicles'] =  TransportTrips::leftjoin('vehicles','vehicles.id','transport_trips.vehicle_id')
                                  ->leftjoin('party','party.id','transport_trips.transporter_id')
                                  ->select('vehicles.id','vehicles.registration_no','transport_trips.is_market_lr','party.name as transporter')
                                  ->groupby('vehicles.id')
                                  ->get();
     
        $viewData['paymentTypes']=TransportTripPaymentTypes::get();

        $viewData['fuelStations']=Parties::
                                    leftJoin('party_selected_party_types as pspt','pspt.party_id','party.id')
                                    ->where('pspt.party_type_id',5)  //5==Fuel Station   
                                    ->groupBy('party.id')
                                    ->get(['party.id','party.name']); 
        
        $viewData['partyTypes']=PartyTypes::get(['id','name']); 
        
        return view('admin.transport_trip_vouchers.transport_trip_voucher_add',$viewData);

    }

    function exportTransportTripVoucher(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);
            
            $wherestr = "trip_voucher.id !='0' ";
            if($vehicle_id!= ''){
                $wherestr .= " AND trip_voucher.vehicle_id =  '".$vehicle_id."'";
            }

            if($voucher_no!= ''){
                $wherestr .= " AND trip_voucher.voucher_no =  '".$voucher_no."'";
            }

            if($lr_no!= ''){
                $wherestr .= " AND tt.lr_no =  '".$lr_no."'";
            }

            // if($authorised_by!= ''){
            //     $wherestr .= " AND trip_voucher.authorised_by =  '".$authorised_by."'";
            // }

            if($payment_type_id!= ''){
                $wherestr .= " AND trip_voucher.payment_type_id =  '".$payment_type_id."'";
            }

            // if($fuel_party_id!= ''){
            //     $wherestr .= " AND trip_voucher.fuel_party_id =  '".$fuel_party_id."'";
            // }

            if($is_party_advance!= ''){
                $wherestr .= " AND trip_voucher.is_party_advance =  '".$is_party_advance."'";
            }

            if($branch!= ''){
                $wherestr .= " AND trip_voucher.branch =  '".$branch."'";
            }

            if($from_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) >=  '".$from_date."'";
            }

            if($to_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) <=  '".$to_date."'";
            }

            $data=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                  ->whereRaw($wherestr)
                                  ->select(
                                    'trip_voucher.id',
                                    'trip_voucher.trip_id',
                                    'trip_voucher.vehicle_id',
                                    'trip_voucher.payment_type_id',
                                    'trip_voucher.voucher_no',
                                    'trip_voucher.payment_mode',
                                    \DB::raw("trip_voucher.branch as branch_name"),
                                    \DB::raw("DATE_FORMAT(trip_voucher.voucher_entry_date,'%d/%m/%Y') as voucher_entry_date"),
                                    \DB::raw("DATE_FORMAT(trip_voucher.voucher_date,'%d/%m/%Y') as voucher_date"),
                                    'trip_voucher.amount',
                                    'trip_voucher.fuel_station_id',
                                    'trip_voucher.fuel_qty',
                                    'trip_voucher.fuel_rate'
                                    )
                                  ->get();
            $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=transport_trip_vouchers.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );

            $columns = array('sr', 'voucher_no','lr_no', 'vehicle','voucher_entry_date', 'voucher_date','payment_mode', 'payment_type','fuel_station','fuel qty','fuel rate', 'amount');

            $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                fputcsv($file,array($sr,
                                    $value->voucher_no,
                                    (isset($value->getSelectedTransportTrip->lr_no))?$value->getSelectedTransportTrip->lr_no:'',
                                    (isset($value->getSelectedVehicle->registration_no))?$value->getSelectedVehicle->registration_no:'',
                                    $value->voucher_entry_date,
                                    $value->voucher_date,
                                    $value->payment_mode,
                                    (isset($value->getSelectedPaymentType->name))?$value->getSelectedPaymentType->name:'',
                                    (isset($value->getSelectedFuelStation->name))?$value->getSelectedFuelStation->name:'',
                                    $value->fuel_qty,
                                    $value->fuel_rate,
                                    $value->amount,
                                    '',
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function printTripVoucher(){
        
        $id = $_GET['id'];
        $viewData['trip_data']=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                                       ->leftjoin('transport_jobs as tj','tj.id','tt.transport_job_id')
                                                       ->leftJoin('service_request as sr', 'sr.id', '=', 'tj.service_request_id')
                                                       ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                                       ->leftJoin('vehicles as v', 'v.id', '=', 'trip_voucher.vehicle_id')
                                                       ->leftJoin('driver_allocated_vehicles as dac', 'dac.vehicle_id', '=', 'trip_voucher.vehicle_id')
                                                       ->leftJoin('drivers as d', 'd.id', '=', 'dac.driver_id')
                                                       ->leftJoin('routes as r', 'r.id', '=', 'srt.route_id')
                                                       ->leftJoin('materials as m', 'm.id', '=', 'srt.material_id')
                                                       ->leftJoin('transport_job_containers as tjc', 'tjc.id', '=', 'tt.job_container_1_id')
                                                       ->leftJoin('transport_job_containers as tjc2', 'tjc2.id', '=', 'tt.job_container_2_id')
                                                       ->leftJoin('trip_payment_types as tpt', 'tpt.id', '=', 'trip_voucher.payment_type_id')
                                                       ->leftJoin('users as u', 'u.id', '=', 'trip_voucher.voucher_created_by')
                                                       ->select(
                                                        'trip_voucher.*',
                                                        'tt.lr_no',
                                                        'tt.is_market_lr',
                                                        'tj.job_no',
                                                        'd.name as driver_name',
                                                        'v.registration_no as registration_no',
                                                        'v.type as vehicle_owner',
                                                        'tjc.container_no as container1',
                                                        'tjc2.container_no as container2',
                                                        'srt.pickup',
                                                        'm.name as material_name',
                                                        'tpt.name as payment_type_name',
                                                        'tpt.tally_ledger as payment_type_tally_ledger',
                                                        'u.name as voucher_created_by_name',
                                                        \DB::raw("
                                                            DATE_FORMAT(trip_voucher.voucher_date,'%d-%m-%Y') as voucher_date,
                                                            DATE_FORMAT(tt.lr_date,'%d-%m-%Y') as lr_date,
                                                            r.from_place,
                                                            r.to_place,
                                                            r.back_place,
                                                            IF(r.back_place!='',CONCAT(r.from_place,'-',r.to_place,'-',r.back_place),CONCAT(r.from_place,'-',r.to_place)) as route_name
                                                        ")
                                                        )
                                                       ->where('trip_voucher.id',$id)->first()->toArray();
        
        return view('admin.transport_trip_vouchers.trip_voucher_print',$viewData);
    }

    public function printDieselVoucher(){
        
        $id = $_GET['id'];
        $viewData['trip_data']=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                                       ->leftjoin('transport_jobs as tj','tj.id','tt.transport_job_id')
                                                       ->leftJoin('service_request as sr', 'sr.id', '=', 'tj.service_request_id')
                                                       ->leftJoin('service_request_transport as srt', 'srt.service_request_id', '=', 'sr.id')
                                                       ->leftJoin('vehicles as v', 'v.id', '=', 'trip_voucher.vehicle_id')
                                                       ->leftJoin('driver_allocated_vehicles as dac', 'dac.vehicle_id', '=', 'trip_voucher.vehicle_id')
                                                       ->leftJoin('drivers as d', 'd.id', '=', 'dac.driver_id')
                                                       ->leftJoin('routes as r', 'r.id', '=', 'srt.route_id')
                                                       ->leftJoin('materials as m', 'm.id', '=', 'srt.material_id')
                                                       ->leftJoin('transport_job_containers as tjc', 'tjc.id', '=', 'tt.job_container_1_id')
                                                       ->leftJoin('transport_job_containers as tjc2', 'tjc2.id', '=', 'tt.job_container_2_id')
                                                       ->leftJoin('party as p', 'p.id', '=', 'trip_voucher.fuel_party_id')
                                                       ->leftJoin('trip_payment_types as tpt', 'tpt.id', '=', 'trip_voucher.payment_type_id')
                                                       ->select(
                                                        'trip_voucher.*',
                                                        'tt.lr_no',
                                                        'tt.is_market_lr',
                                                        'tj.job_no',
                                                        'd.name as driver_name',
                                                        'v.registration_no as registration_no',
                                                        'v.type as vehicle_owner',
                                                        'tjc.container_no as container1',
                                                        'tjc2.container_no as container2',
                                                        'srt.pickup',
                                                        'm.name as material_name',
                                                        'p.name as fuel_party_name',
                                                        'tpt.name as payment_type_name',
                                                        'tpt.tally_ledger as payment_type_tally_ledger',
                                                        \DB::raw("
                                                            DATE_FORMAT(trip_voucher.voucher_date,'%d-%m-%Y') as voucher_date,
                                                            DATE_FORMAT(tt.lr_date,'%d-%m-%Y') as lr_date,
                                                            r.from_place,
                                                            r.to_place,
                                                            r.back_place,
                                                            IF(r.back_place!='',CONCAT(r.from_place,'-',r.to_place,'-',r.back_place),CONCAT(r.from_place,'-',r.to_place)) as route_name
                                                        ")
                                                        )
                                                       ->where('trip_voucher.id',$id)->first()->toArray();
        return view('admin.transport_trip_vouchers.diesel_voucher_print',$viewData);
    }

    function authorisedByTripVoucherPaginate(Request $request){
        if ($request->ajax()) {
            $login_user_id = Auth::user()->id;
            
            $wherestr = "trip_voucher.id !='0' AND trip_voucher.payment_type_id != '15' AND trip_voucher.authorised_by='".$login_user_id."' AND trip_voucher.authorised_check='0'";
            
            if($request->vehicle_id!= ''){
                $wherestr .= " AND trip_voucher.vehicle_id =  '".$request->vehicle_id."'";
            }

            if($request->voucher_no!= ''){
                $wherestr .= " AND trip_voucher.voucher_no =  '".$request->voucher_no."'";
            }

            if($request->lr_no!= ''){
                $wherestr .= " AND tt.lr_no =  '".$request->lr_no."'";
            }

            if($request->job_no!= ''){
                $wherestr .= " AND tj.job_no =  '".$request->job_no."'";
            }

            if($request->authorised_by!= ''){
                $wherestr .= " AND trip_voucher.authorised_by =  '".$request->authorised_by."'";
            }

            if($request->payment_type_id!= ''){
                $wherestr .= " AND trip_voucher.payment_type_id =  '".$request->payment_type_id."'";
            }

            if($request->is_party_advance!= ''){
                $wherestr .= " AND trip_voucher.is_party_advance =  '".$request->is_party_advance."'";
            }

            if($request->branch!= ''){
                $wherestr .= " AND trip_voucher.branch =  '".$request->branch."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(trip_voucher.voucher_date) <=  '".$request->to_date."'";
            }
            
            
            $data=TransportTripVouchers::leftJoin('transport_trips as  tt', 'tt.id', '=', 'trip_voucher.trip_id')
                                  ->leftJoin('transport_jobs as tj', 'tj.id', '=', 'tt.transport_job_id')
                                  ->whereRaw($wherestr)
                                  ->select('trip_voucher.*')
                                  ->orderby('id','desc');

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                       $btn = '<input type="checkbox" id="row_checkbox_'.$row['id'].'" class="check_single" value="'.$row['id'].'">';
                       return $btn;
                    })
                    ->addColumn('voucher_entry_date', function($row) {
                        return ($row->voucher_entry_date!='')?date('d/m/Y',strtotime($row->voucher_entry_date)):'';
                    })->addColumn('voucher_date', function($row) {
                        return ($row->voucher_date!='')?date('d/m/Y',strtotime($row->voucher_date)):'';
                    })->addColumn('branch_name', function($row) {
                        return $row->branch;
                    })->addColumn('lr_no', function($row) {
                        return (isset($row->getTransportTripDetail->lr_no))?$row->getTransportTripDetail->lr_no:'';
                    })->addColumn('vehicle_no', function($row) {
                        return (isset($row->getVehicleDetail->registration_no))?$row->getVehicleDetail->registration_no:'';
                    })->addColumn('exp_type', function($row) {
                        return (isset($row->getPaymentTypeDetail))?$row->getPaymentTypeDetail->name:'';
                    })->addColumn('party_name', function($row) {
                        if(isset($row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getPartyDetail)){
                        return $row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getPartyDetail->name;
                      }else{
                        return '';
                      }
                    })->addColumn('job_no', function($row) {
                        return isset($row->getTransportTripDetail->getTransportJobs->job_no)?$row->getTransportTripDetail->getTransportJobs->job_no:'';
                    })->addColumn('container_no_or_weight', function($row) {
                        if(isset($row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail)){
                            $job_type=$row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->job_type;
                            $no_of_container=$row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->no_of_container;
                            $weight=$row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->weight;
                            if($job_type=='Container'){
                                return $no_of_container;
                            }else{
                                return $weight;
                            }
                        }else{
                            return '';
                        }
                    })->addColumn('route_name', function($row) {
                        if(isset($row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail)){
                            $routeData= $row->getTransportTripDetail->getTransportJobs->getServiceRequestDetail->getServiceReqTransportDetail->getRouteDetail;
                            if($routeData->back_place!=''){
                                return $routeData->from_place.'-'.$routeData->to_place.'-'.$routeData->back_place;
                            }else{
                                return $routeData->from_place.'-'.$routeData->to_place;
                            }
                        }else{
                            return '';
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        $viewData['title']='AUTHORIZED BY TRANSPORT TRIP VOUCHER';
        $viewData['parties']=Parties::where('party.ledger_type_id',1)->get();
        $viewData['vehicles']=Vehicles::get();
       // $viewData['users']=User::where('is_authorised',1)->get();
        $viewData['paymentTypes']=TransportTripPaymentTypes::get();
        return view('admin.transport_trip_vouchers.authorised_by_trip_voucher_list',$viewData);

    }

    function updateAuthorisedByTripVoucher(Request $request){
        $dataArr =  explode(',', $request->id);
        
        $login_user_id = Auth::user()->id;
        
        foreach ($dataArr as $key => $value) {
            if($value > 0){
                $updateData=array('authorised_check'=>1);
                $res=TransportTripVouchers::where('id',$value)->update($updateData);
            }
        }
        echo  1;
    }

} //class close