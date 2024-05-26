<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;
use Storage;

use App\Models\RouteRates;
use App\Models\Routes;
use App\Models\Parties;
use DB;

class RouteRatesController extends Controller{
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
        $viewData['parties']=Parties::where('status',1)->get();
        $viewData['routes']=Routes::where('status',1)->get();
        return $viewData;
    }
    public function index(){
        $viewData = $this->commonViewData();
        $viewData['title']='Factory Rates';
        return view('admin.route_rates.route_rate_list',$viewData);
    }

    function paginate(Request $request){
        if ($request->ajax()) {
            
            $wherestr = "route_rates.id !='0' ";

            $login_user_role = Auth::user()->role_id;
           
            if($request->party_id!= ''){
                $wherestr .= " AND route_rates.party_id =  '".$request->party_id."'";
            }
            
            if($request->route_id!= ''){
                $wherestr .= " AND route_rates.route_id =  '".$request->route_id."'";
            }

            if($request->from_date!= ''){
                $wherestr .= " AND DATE(route_rates.applicable_date) >=  '".$request->from_date."'";
            }

            if($request->to_date!= ''){
                $wherestr .= " AND DATE(route_rates.applicable_date) <=  '".$request->to_date."'";
            }
            
            $data=RouteRates::whereRaw($wherestr)->orderby('route_rates.id','desc');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '';
                        $btn .= '&nbsp;&nbsp;<a href="'.route('route.rate.edit',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm">
                                    <i class="feather icon-edit"></i></a>';
                        $btn .= '&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="deleteRecord('.$row->id.')"><i class="feather icon-trash"></i></button>';
                        
                        return $btn;
                    })
                    ->addColumn('applicable_date', function($row) {
                      return isset($row->applicable_date)?date('d/m/Y',strtotime($row->applicable_date)):'';
                    })
                    ->addColumn('party_id', function($row) {
                      return (isset($row->getSelectedParty))?$row->getSelectedParty->name:'';
                    })->addColumn('route_id', function($row) {
                      $routeData=(isset($row->getSelectedRoute))?$row->getSelectedRoute:[];
                      $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                      $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                      $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                      
                      return $RouteName;
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
                            'party_id'      =>$party_id,
                            'route_id'      =>$route_id,
                            'rate'          =>$rate,
                            'distance'      =>$distance,
                            'ptpk'          =>$ptpk,
                            'applicable_date'=>$applicable_date,
                        );
         
            $response_id = RouteRates::create($insertData)->id;
            if($response_id!= ''){
                return redirect('/factory-rate-list')->with('success', 'Factory Rate Added Successfully!!');
            }else{
                return redirect('/factory-rate-list')->with('error', 'Please try again!');
            }
        }

        $viewData=$this->commonViewData();
        $viewData['title']="Add Factory Rate";

        return view('admin.route_rates.route_rate_add',$viewData);
    }

    function editData($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);

           $updateData=array(
                            'party_id'      =>$party_id,
                            'route_id'      =>$route_id,
                            'rate'          =>$rate,
                            'distance'      =>$distance,
                            'ptpk'          =>$ptpk,
                            'applicable_date'=>$applicable_date,
                        );
            $response = RouteRates::where('id',$id)->update($updateData);
            if($response){
                return redirect('/factory-rate-list')->with('success', 'Factory Rate Updated Successfully!!');
            }else{
                return redirect('/factory-rate-list')->with('error', 'Please try again!');
            }
        }  //if close


        $editData = RouteRates::find($id);
        
        $viewData=$this->commonViewData();
        
        $viewData['editData'] = $editData;
        $viewData['title']="Edit Factory Rate";
        return view('admin.route_rates.route_rate_add',$viewData);
    }

    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=RouteRates::where('id',$id)->delete();
            if($response){
                return redirect('factory-rate-list')->with('success', 'Factory Rate deleted successfully!');
            }else{
                return redirect('factory-rate-list')->with('error', 'Please try again!');
            }
        }
    }
    
    

    
    function exportPrimaryRecord(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

            $wherestr = "route_rates.id !='0' ";
            if(isset($party_id) && $party_id!= ''){
                $wherestr .= " AND route_rates.party_id =  '".$party_id."'";
            }
            if(isset($route_id) && $route_id!= ''){
                $wherestr .= " AND route_rates.route_id =  '".$route_id."'";
            }
            

            if(isset($from_date) && $from_date!= ''){
                $wherestr .= " AND DATE(route_rates.applicable_date) >=  '".$from_date."'";
            }

            if(isset($to_date) && $to_date!= ''){
                $wherestr .= " AND DATE(route_rates.applicable_date) <=  '".$to_date."'";
            }
            
            $data=RouteRates::whereRaw($wherestr)->get();
          
          $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=route_rates.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


          $columns = array('sr', 'party','route', 'rate','distance','ptpk','applicable_date');

          $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $row) {
                $routeData=(isset($row->getSelectedRoute))?$row->getSelectedRoute:[];
                $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                
                fputcsv($file,array($sr,
                                    (isset($row->getSelectedParty))?$row->getSelectedParty->name:'',
                                    $RouteName,
                                    $row->rate,
                                    $row->distance,
                                    $row->ptpk,
                                    (isset($row->applicable_date))?date('d/m/Y',strtotime($row->applicable_date)):'',
                                )
                        );
                $sr++;
            }
            fclose($file);
          };
        return Response::stream($callback, 200, $headers);
    } //func close

} //class close