@extends('layouts.app')
@section('title','Company Profile')
@section('content')
<?php 
// print_r('<pre>');
// print_r($editData);
// die();
if(isset($editData) && !empty($editData)){
    // extract($editData);

}

?>

<div class="pcoded-content">

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success background-success" style="width: 100%">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: 2px;">
                                    <i class="icofont icofont-close-line-circled text-white"></i>
                                </button>
                                <strong>{{ $message }}</strong> 
                            </div>
                            @endif

                            @if ($message = Session::get('error'))
                            <div class="alert alert-danger background-danger" style="width: 100%">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="icofont icofont-close-line-circled text-white"></i>
                                </button>
                                <strong>{{ $message }}</strong> 
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <h4>Company Profile</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="page-header-breadcrumb">
                                                <a href="{{route('add.location')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="card-block">

                                    <div class="row">
                                        <div class="col-lg-12 col-xl-12">
                                            <ul class="nav nav-tabs  tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link {{(Request::segment(1)=='company-setting' && Request::segment(2) == $editData->id)?'active':''}} " href="{{route('company.setting',base64_encode($editData->id))}}" >Company Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link  {{(Request::segment(1)=='company-bank-setting')?'active':''}} " href="{{route('company.bank.setting',base64_encode($editData->id))}}" >Bank Detail</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link  {{(Request::segment(1)=='company-terms-setting')?'active':''}} " href="{{route('company.terms.setting',base64_encode($editData->id))}}" >Terms </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content tabs card-block">
                                                <div class="tab-pane {{(Request::segment(1)=='company-setting')?'active':''}}" 
                                                id="company" role="tabpanel">
                                                <form id="main" method="post" action=" {{route('update.company.setting',base64_encode($editData->id))}}" novalidate="" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                       <div class="form-group col-md-2">
                                                        <label for="company_name">Company Name</label>
                                                        <input type="text" name="company_name" class="form-control" id="company_name" value="{{isset($editData->company_name)?$editData->company_name:'' }}" placeholder="Company Name" >

                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label>Logo</label>
                                                        <input type="file" name="logo" class="form-control" id="logo" value="">
                                                    </div> 
                                                     @if(isset($editData->logo) && $editData->logo!='')
                                                    <div class="form-group col-md-1">
                                                        <img   style="height:100px;width:75px;"src="{{asset('uploads/company_logo/'.$editData->logo)}}">
                                                    </div> 
                                                    @endif

                                                    <div class="form-group col-md-4">
                                                        <label for="address">Address</label>
                                                        <textarea type="text" name="address" class="form-control" id="address" value="" placeholder="Address" >{{isset($editData->address)?$editData->address:'' }}</textarea>

                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label>Mobile No</label>
                                                        <input type="text" name="mobileno" class="form-control " id="mobileno" value="{{isset($editData->mobileno)?$editData->mobileno:'' }}" placeholder="Mobile No" >
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label>Pan No</label>
                                                        <input type="text" name="pan_no" class="form-control" id="pan_no" value="{{isset($editData->pan_no)?$editData->pan_no:'' }}" placeholder="Pan No" >
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label>Gst No</label>
                                                        <input type="text" name="gst_no" class="form-control" id="gst_no" value="{{isset($editData->gst_no)?$editData->gst_no:'' }}" placeholder="Gst No" >
                                                    </div>

                                                     <div class="form-group col-md-3">
                                                        <label>CIN No</label>
                                                        <input type="text" name="cin_no" class="form-control" id="cin_no" value="{{isset($editData->cin_no)?$editData->cin_no:'' }}" placeholder="CIN No" >
                                                    </div>

                                                     <div class="form-group col-md-3">
                                                        <label>MSME No</label>
                                                        <input type="text" name="msme_no" class="form-control" id="msme_no" value="{{isset($editData->msme_no)?$editData->msme_no:'' }}" placeholder="MSME No" >
                                                    </div>
                                                    </div>

                                                    <div class="row">

                                                    <div class="form-group col-md-3">
                                                        <label>Stamp Label</label>
                                                        <input type="text" name="stamp_name" class="form-control" id="stamp_name" value="{{isset($editData->stamp_name)?$editData->stamp_name:'' }}" placeholder="Stamp Label" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Stamp Image</label>
                                                        <input type="file" name="stamp_img" class="form-control" id="stamp_img" value="">
                                                      
                                                    </div> 

                                                    @if(isset($editData->stamp_img) && $editData->stamp_img!='')
                                                    <div class="form-group col-md-1">
                                                        <img  style="height:100px;width:75px;" src="{{asset('uploads/company_stamp_img/'.$editData->stamp_img)}}">
                                                    </div> 
                                                    @endif

                                                    <div class="form-group col-md-4">
                                                        <label>Header Image</label>
                                                        <input type="file" name="header_image" class="form-control" 
                                                               id="header_image" value="">
                                                        <span style="color:red;">
                                                                Note: for best result upload this size 
                                                                <a target="_blank" href="{{asset('admin/images/karan-trip-print-header.png')}}" style="color:blue;">img
                                                                </a>
                                                            </span>
                                                    </div> 
                                                    
                                                    @if(isset($editData->header_image) && $editData->header_image!='')
                                                    <div class="form-group col-md-1">
                                                        <img style="height:100px;width:75px;"src="{{asset('uploads/company_header_images/'.$editData->header_image)}}">
                                                    </div> 
                                                    @endif
                                                </div>

                                                <button type="submit" class="btn btn-primary">SAVE</button>

                                            </form>       
                                        </div> {{-- tab pane close --}}

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>



            </div>
        </div>

    </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
	
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginImagePreview,
        FilePondPluginImageEdit
        );
    

    FilePond.create(document.querySelector(".filepond_tile"),{
        labelIdle: `Drag & drop your logo or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 150,
        imagePreviewWidth: 150,
        imageCropAspectRatio: '1:1',
        imageResizeTargetWidth: 200,
        imageResizeTargetHeight: 200,
        // default crop aspect ratio
        imageCropAspectRatio: 1,

        // resize to width of 200
        imageResizeTargetWidth: 200,

        // open editor on image drop
        imageEditInstantEdit: true,

        // configure Doka
        imageEditEditor: Doka.create({
            cropAspectRatioOptions: [
            {
                label: 'Free',
                value: null
            },
            {
                label: 'Portrait',
                value: 1.25
            },
            {
                label: 'Square',
                value: 1
            },
            {
                label: 'Landscape',
                value: .75
            }
            ]
        })
    });

    $('#role_id').change(function(event) {
        var role = $("#role_id option:selected").val();
        if(role == 11){
            $('.party_block').show();
        }else{
            $('.party_block').hide();
        }
        console.log(role);
    });

    
    function is_authorised_checkbox(checkboxElem) {
      if (checkboxElem.checked) {
        $("#is_authorised").val(1);
    } else {
        $("#is_authorised").val(0);
    }
}

function is_repair_authorised_checkbox(checkboxElem) {
  if (checkboxElem.checked) {
    $("#is_repair_authorised").val(1);
} else {
    $("#is_repair_authorised").val(0);
}
}
</script>
@endsection