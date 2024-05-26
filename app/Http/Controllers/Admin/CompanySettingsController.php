<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Parties;
use App\Models\Roles;
use App\Models\ProjectModules;
use App\Models\UserProjectModules;
use App\Models\CompanySettings;
use DataTables;
use Auth;
use Response;
use Illuminate\Support\Facades\Hash;


class CompanySettingsController extends Controller
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
    
     public function allCompanyList(Request $request) {
        if ($request->ajax()) {
            
            $club_id = Auth::user()->club_id;
            // $data = User::where('id','!=',1)->get();
            $wherestr = " company_settings.id != 0 ";

            if($request->role_id > 0){
                $wherestr .= " AND role_id ='".$request->role_id."' ";
            }

            if($request->party_id!=''){
                $wherestr .= " AND party_id ='".$request->party_id."' ";
            }


            $data=CompanySettings::select('company_settings.*')->whereRaw($wherestr);
                       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                           $btn = '<a href="'.route('company.setting',base64_encode($row->id)).'" class="edit btn btn-primary btn-sm"><i class="feather icon-edit"></i></a>';
                           return $btn;
                    })
                    ->addColumn('profile_pic', function ($row) { 
                           $url=asset("profile_pic/$row->profile_pic"); 
                           return ' <img src='.$url.' border="0" width="100" class="img-rounded" align="center" />'; 
                    })->addColumn('btn_toggel', function($row) {
                            if($row->status == '1'){
                                $html = '<input type="checkbox" id="status_'.$row->id.'" checked class="js-warning" onchange = changeStatus('.$row->id.') />';
                            }else{
                                $html = '<input type="checkbox" id="status_'.$row->id.'" class="js-warning"  onchange = changeStatus('.$row->id.') />';
                            }
                            return $html;
                    })->addColumn('user_type', function($row) {
                        return (isset($row->getRoleDetail->name))? $row->getRoleDetail->name:'';
                    })
                    ->rawColumns(['action','profile_pic','btn_toggel'])
                    ->make(true);
        }

        return view('admin.company_settings.company_list');
    }

    function viewCompanySettings($id,Request $request){
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            
            $companyData=array(
                'company_name'     =>$company_name,
                'address'          =>$address,
                'mobileno'          =>$mobileno,
                'pan_no'          =>$pan_no,
                'gst_no'          =>$gst_no ,
                'cin_no'          =>$cin_no ,
                'msme_no'          =>$msme_no ,
                'stamp_name'          =>$stamp_name,
            );

           
            $file1 = $request->file('logo');
            if($file1){
                $lastData=CompanySettings::where('id',$id)->first();
                if($lastData->logo!=null){
                    unlink(public_path('uploads/company_logo/'.$lastData->logo));
                }

                $fileName = time().'.'.$file1->extension(); 
                $file1->move(public_path('uploads/company_logo/'), $fileName); 
                $companyData['logo'] = $fileName;
                
            }
            
             $file1 = $request->file('stamp_img');
            if($file1){
                $lastData=CompanySettings::where('id',$id)->first();
                if($lastData->stamp_img!=null){
                    unlink(public_path('uploads/company_stamp_img/'.$lastData->stamp_img));
                }

                $fileName = time().'.'.$file1->extension(); 
                $file1->move(public_path('uploads/company_stamp_img/'), $fileName); 
                $companyData['stamp_img'] = $fileName;
                
            }

            $file1 = $request->file('header_image');
            if($file1){
                $lastData=CompanySettings::where('id',$id)->first();
                if($lastData->header_image!=null){
                    unlink(public_path('uploads/company_header_images/'.$lastData->header_image));
                }

                $fileName = time().'.'.$file1->extension(); 
                $file1->move(public_path('uploads/company_header_images/'), $fileName); 
                $companyData['header_image'] = $fileName;
            }

            $company_id = CompanySettings::where('id',$id)->update($companyData);
            if($company_id!=''){
                return redirect('company-list')->with('success', 'Company Profile Updated!');
            }else{
                return redirect('company-list')->with('error', 'Company Profile not Updated!');
            }
        }
        
        $viewData['editData']=CompanySettings::where('id',$id)->first();
        return view('admin.company_settings.add_company',$viewData);

    }
    function viewBankDetail($id,Request $request){
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            
            $companyData=array(
    
                'bank_name'          =>$bank_name ,
                'bank_account_holder_name'  =>$bank_account_holder_name ,
                'account_no'          =>$account_no ,
                'ifsc_no'          =>$ifsc_no ,

            );


            $company_id = CompanySettings::where('id',$id)->update($companyData);
            if($company_id!=''){
                return redirect('company-list')->with('success', 'Company Profile Updated!');
            }else{
                return redirect('company-list')->with('error', 'Company Profile not Updated!');
            }
        }

        $viewData['editData']=CompanySettings::where('id',$id)->first();
        
        return view('admin.company_settings.add_bank_detail',$viewData);
    }

     function viewTermsSelection($id,Request $request){
        $id = base64_decode($id);
        if(!empty($request->all())){
            extract($_POST);
            
            $companyData=array(
                'term_1'          =>$term_1 ,
                'term_2'          =>$term_2 ,
                'term_3'          =>$term_3 ,
                'term_4'          =>$term_4 ,
                'term_5'          =>$term_5 ,
            );


            $company_id = CompanySettings::where('id',$id)->update($companyData);
            if($company_id!=''){
                return redirect('company-list')->with('success', 'Company Profile Updated!');
            }else{
                return redirect('company-list')->with('error', 'Company Profile not Updated!');
            }
        }
    
        $viewData['editData']=CompanySettings::where('id',$id)->first();
        return view('admin.company_settings.add_terms_selection',$viewData);
    }
}