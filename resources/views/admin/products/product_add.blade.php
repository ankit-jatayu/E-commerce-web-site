@extends('layouts.app')
@section('title',(isset($editData->id ))?'Edit Product':'Add Product')
@section('content')
<style type="text/css">
	.error{
    border-color:red;
    color:red;
  }
</style>
<div class="pcoded-content">

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div id="alert-msg-block"></div>
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
                                                <h4>{{(isset($editData->id ))?'Edit Product':'Add Product'}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="page-header-breadcrumb">
                                                <a href="{{route('product.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action="{{(isset($editData->id )) ? route('product.update',base64_encode($editData->id)) : route('product.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                              	<label for="name">Name<span style="color:red">*</span></label>
                                              	<input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ $editData->name ?? '' }}" required>
                                              	@error('name')
				                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
				                                @enderror
                                            </div>
                                            
                                        </div>
                                          <button type="submit" class="btn btn-primary">SAVE</button>
                                        <a href="{{route('product.list')}}"  class="btn btn-danger">CANCEL</a>
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
	// $(document).ready(function() {
	// 	$("#main").validate();
	// });
    
</script>
@endsection