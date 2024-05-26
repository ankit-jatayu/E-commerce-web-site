<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\Locations;


class LocationsController extends Controller
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
    

    public function allLocationList(Request $request) {
        if ($request->ajax()) {
            
            $wherestr = " id != '' ";

            if($request->name!= ''){
                $wherestr .= " AND name =  '".$request->name."'";
            }

            $data=Locations::whereRaw($wherestr)->orderBy('id','DESC');
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                             $user_id = Auth::user()->id;
                        $all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
                        $PlaceModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==2;})->first();
                        $btn ='';
                        if(isset($PlaceModuleRights) && $PlaceModuleRights->is_edit==1){
                           $btn = '<a href="'.route('edit.location',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
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

        return view('admin.locations.location_list');
    }

    public function addLocation(Request $request) {
        if(!empty($request->all())){
            $check=Locations::where('name',$request->name)->where('place_type',$request->place_type)->count();
            if($check>0){
                return redirect()->back()->with('error','Location already exist in '.$request->place_type);
            }

            $data = array();
            $data['name'] = $request->input('name');
            $data['place_type'] = $request->input('place_type');
            $location_id = Locations::create($data);
            if($location_id!= ''){
                return redirect('location-list')->with('success', 'Location Created successfully!');
            }else{
                return redirect('location-list')->with('error', 'Please try again!');
            }
        }
        return view('admin.locations.location_add');
    }

    public function editLocation($id, Request $request) {
        $id = base64_decode($id);
        if(!empty($request->all())){
            $check=Locations::where('id','!=',$id)->where('name',$request->name)
                              ->where('place_type',$request->place_type)->count();
            if($check>0){
                return redirect()->back()->with('error','Location already exist in '.$request->place_type);
            }

            $data = Locations::where('id',$id)->first();
            $data->name = $request->input('name');
            $data->place_type = $request->input('place_type');
           
            $data->save();
            $location_id = $data->id;
            if($location_id!= ''){
                return redirect('location-list')->with('success', 'Location Detail Updated successfully!');
            }else{
                return redirect('location-list')->with('error', 'Please try again!');
            }
        }
        $viewData['editData'] = Locations::where('id',$id)->first();
        
        return view('admin.locations.location_add',$viewData);
    }

    public function updateLocationStatus(Request $request) {
        
        $data = Locations::where('id',$request->input('id'))->first();
        $data->status = $request->input('status');
        $data->save();
        
        echo '1';
    }

    function exportLocation(){
        $paramData=json_decode($_GET['data'],true);
        extract($paramData);

        $wherestr = " id !='0' ";

        // if($name!= ''){
        //     $wherestr .= " AND name =  '".$name."'";
        // }

        $data=Locations::whereRaw($wherestr)->select('*')->get();
       
        $headers = array(
                            "Content-type" => "text/csv",
                            "Content-Disposition" => "attachment; filename=location.csv",
                            "Pragma" => "no-cache",
                            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                            "Expires" => "0"
                        );


        $columns = array('sr', 'name', 'state');

        $callback = function() use ($data, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $sr=1;
            foreach($data as $value) {
                fputcsv($file,array($sr,
                                    $value->name,
                                    $value->place_type,
                                )
                        );
                $sr++;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
   
}