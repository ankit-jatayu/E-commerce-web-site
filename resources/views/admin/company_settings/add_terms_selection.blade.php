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
                                                    <a class="nav-link {{(Request::segment(1)=='company-setting')?'active':''}} " href="{{route('company.setting',base64_encode($editData->id))}}" >Company Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link  {{(Request::segment(1)=='company-bank-setting')?'active':''}} " href="{{route('company.bank.setting',base64_encode($editData->id))}}" >Bank Detail</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link  {{(Request::segment(1)=='company-terms-setting')?'active':''}} " href="{{route('company.terms.setting',base64_encode($editData->id))}}" >Terms </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content tabs card-block">
                                                <div class="tab-pane {{(Request::segment(1)=='company-terms-setting')?'active':''}}" 
                                                id="bank" role="tabpanel">
                                                <form id="main" method="post" action=" {{route('update.company.terms.setting',base64_encode($editData->id))}}" novalidate="" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                       <div class="form-group col-md-6">
                                                        <label for="term_1">Terms 1</label>
                                                        <input type="text" name="term_1" class="form-control" id="term_1" value="{{isset($editData->term_1)?$editData->term_1:'' }}" placeholder="Terms 1">

                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="term_2">Terms 2</label>
                                                        <input type="text" name="term_2" class="form-control" id="term_2" value="{{isset($editData->term_2)?$editData->term_2:'' }}" placeholder="Terms 2">

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="term_3">Terms 3</label>
                                                        <input type="text" name="term_3" class="form-control" id="term_3" value="{{isset($editData->term_3)?$editData->term_3:'' }}" placeholder="Terms 3">

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="term_4">Terms 4</label>
                                                        <input type="text" name="term_4" class="form-control" id="term_4" value="{{isset($editData->term_4)?$editData->term_4:'' }}" placeholder="Terms 4">

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="term_5">Terms 5</label>
                                                        <input type="text" name="term_5" class="form-control" id="term_5" value="{{isset($editData->term_5)?$editData->term_5:'' }}" placeholder="Terms 5">

                                                    </div>
                                                     

                                                
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