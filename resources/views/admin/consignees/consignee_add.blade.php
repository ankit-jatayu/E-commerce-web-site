@extends('layouts.app')
@section('title',(isset($editData->id ))?'Edit Consignee':'Add Consignee')
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
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <h4>Consignee/Consigner</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="page-header-breadcrumb">
                                                <a href="{{route('consignee.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData->id )) ? route('consignee.update',base64_encode($editData->id)) : route('consignee.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                              	<label for="company_name">Company Name<span style="color:red">*</span></label>
                                              	<input type="text" name="company_name" class="form-control" id="company_name" placeholder="Company Name" value="{{ $editData->company_name ?? '' }}" required>
                                              	@error('company_name')
				                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
				                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="gst_no">GST No<span style="color:red">*</span></label>
                                                <input type="text" name="gst_no" class="form-control" id="gst_no" placeholder="GST No" value="{{ $editData->gst_no ?? '' }}" required>
                                                @error('gst_no')
                                            <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                        @enderror
                                            </div>



                                      <div class="form-group col-md-4">
                                        <label> Address<span style="color:red">*</span></label>
                                        <textarea  name="address" class="form-control" id="address"  placeholder="Please provide Address">{{ $editData->address ?? '' }}</textarea>
                                    </div>
                                </div>
                                          <button type="submit" class="btn btn-primary">SAVE</button>
                                        <a href="{{route('consignee.list')}}"  class="btn btn-danger">CANCEL</a>
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