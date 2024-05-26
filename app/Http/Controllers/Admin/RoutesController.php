<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Routes;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Hash;


class RoutesController extends Controller
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
        $viewData=array();
        $viewData['locations'] = Locations::get();
        return $viewData;
    }
    public function allRouteList(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " id !='0' ";

            // if($request->name!= ''){
            //     $wherestr .= " AND name =  '".$request->name."'";
            // }

            $data=Routes::whereRaw($wherestr);
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                          $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $RouteModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==3;})->first();
                        $btn ='';
                        if(isset($RouteModuleRights) && $RouteModuleRights->is_edit==1){
                           $btn .= '<a href="'.route('edit.route',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';

                            }
                        if(isset($RouteModuleRights) && $RouteModuleRights->is_delete==1){
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
                    })->addColumn('route', function($row) {
                        $RouteName="";
                        if($row->destination_1 != ''){
                            $RouteName = $row->from_place.'-'.$row->destination_1;
                        }
                        if($row->destination_2 != ''){
                            $RouteName .= '-'.$row->destination_2;
                        }
                        if($row->destination_3 != ''){
                            $RouteName .= '-'.$row->destination_3;
                        }
                        return $RouteName;
                    })
                    ->rawColumns(['action','btn_toggel','route'])
                    ->make(true);
        }

        return view('admin.routes.route_list');
    }

     public function addRoute(Request $request) {
        if(!empty($request->all())){
            $data = array();
            $data['from_place'] = $request->input('from_place');
            $data['destination_1'] = $request->input('destination_1');
            $data['destination_2'] = $request->input('destination_2');
            $data['destination_3'] = $request->input('destination_3');
            
            $route_id = Routes::create($data);
            if($route_id!= ''){
                return redirect('route-list')->with('success', 'Route Created successfully!');
            }else{
                return redirect('route-list')->with('error', 'Please try again!');
            }
        }

        $viewData=$this->commonViewData();
        return view('admin.routes.route_add',$viewData);
    }

    public function editRoute($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){

            $data = Routes::where('id',$id)->first();
            $data->from_place = $request->input('from_place');
            $data->destination_1 = $request->input('destination_1');
            $data->destination_2 = $request->input('destination_2');
            $data->destination_3 = $request->input('destination_3');
            $data->save();
            
            $route_id = $data->id;
            if($route_id!= ''){
                return redirect('route-list')->with('success', 'Route Detail Updated successfully!');
            }else{
                return redirect('route-list')->with('error', 'Please try again!');
            }
        }
        
        $viewData=$this->commonViewData();
        $viewData['editData'] = Routes::where('id',$id)->first();
        return view('admin.routes.route_add',$viewData);
    }

    public function updateRouteStatus(Request $request) {
        
        $data = Routes::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }
    function deletePrimaryRecord(){
        $id=(isset($_GET['id']))?$_GET['id']:'';
        if($id!=''){
            $response=Routes::where('id',$id)->delete();
            if($response){
                return redirect('route-list')->with('success', 'Route deleted successfully!');
            }else{
                return redirect('route-list')->with('error', 'Please try again!');
            }
        }
    }

    function exportRoute(){
        // $paramData=json_decode($_GET['data'],true);
        // extract($paramData);

        $wherestr = " id !='0' ";

        // if($name!= ''){
        //     $wherestr .= " AND name =  '".$name."'";
        // }

        $data=Routes::whereRaw($wherestr)->select('*')->get();

        $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=route.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );

        $columns = array('sr', 'from_place', 'destination_1', 'destination_2', 'destination_3');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                fputcsv($file,array($sr,
                                    $value->from_place,
                                    $value->destination_1,
                                    $value->destination_2,
                                    $value->destination_3,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
   
}