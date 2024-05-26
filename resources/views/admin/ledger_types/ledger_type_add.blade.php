@extends('layouts.app')
@section('title',(isset($ledger_type_detail->name))?'Edit Location':'Add Location')
@section('content')
<style type="text/css">
	.error{
		color: red;
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
                                                <h4>LEDGER TYPE</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="page-header-breadcrumb">
                                                <a href="{{route('add.ledger.type')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($ledger_type_detail->name)) ? route('update.ledger.type',base64_encode($ledger_type_detail->id)) : route('store.ledger.type')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                              	<label for="name">Ledger Type</label>
                                              	<input type="text" name="name" class="form-control" id="name" placeholder="Ledger Type" value="{{ $ledger_type_detail->name ?? '' }}" required>
                                              	@error('name')
				                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
				                                @enderror
                                            </div>

                                            
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">SAVE</button>
                                        <a href="{{route('list.ledger.type')}}"  class="btn btn-danger">CANCEL</a>
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
	$(document).ready(function() {
		$("#main").validate();
	});
    
</script>
@endsection