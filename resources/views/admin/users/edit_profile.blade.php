@extends('layouts.app')
@section('title','Edit User Profile')
@section('content')

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
                                                <h4>EDIT USER PROFILE</h4>
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
                                    <form id="main" method="post" action="{{route('update.profile')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                              	<label for="password">Password</label>
                                              	<input type="password" name="password" class="form-control" id="password" value="" placeholder="Password" required >
                                              	@error('password')
				                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
				                                @enderror
                                            </div>

                                            <div class="form-group col-md-2">
                                              	<label for="password-confirm">Confirm Password </label>
                                              	<input type="password" name="password_confirmation" class="form-control" id="password-confirm" value="" placeholder="Confirm Password" data-rule-equalTo="#password">
                                              	
                                            </div>
                                         
                                        </div>

                                        <button type="submit" class="btn btn-primary">SAVE</button>
                                        {{-- <a href="{{route('list.user')}}"  class="btn btn-danger">CANCEL</a> --}}
                                    </form>
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