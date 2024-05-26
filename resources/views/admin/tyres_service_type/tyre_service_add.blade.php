@extends('layouts.app')
@section('title',(isset($editData))?'Edit Tyres Service Type':'Add Tyres Service Type')
@section('content')
<?php 
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

                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <h4>Tyres Service Type</h4>
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
                                    <form id="main" method="post" 
                                    action=" {{ (isset($editData)) ? route('tyre.service.update',base64_encode($editData->id)) : route('tyre.service.store')}}"
                                    novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">

                                    <div class="form-group col-md-2">
                                    <label>Service Type Name </label>
                                  <input type="text" name="name" class="form-control" id="name" value="{{(isset($editData->name))?$editData->name:''}}" required>
                                  </div>
                                


                            </div>

                            <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                            <a href="{{route('tyre.service.list')}}"  class="btn btn-danger">CANCEL</a>
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
		getPaymentType();
	});


  function getPaymentType(){
     var paymenttype=$("#payment_type_id option:selected").text();
     if(paymenttype=='Driver Advance'){
        $("#total_amount").val(0); 
        $('.cash_block').show();
        $('.qty_block').hide();
        $(".budget_advance_label_block").show();
        $(".budget_diesel_label_block").hide();
    }else if(paymenttype=='Diesel'){
        $("#total_amount").val(0); 
        $('.qty_block').show();
        $('.cash_block').hide();
        $('.additional_block').hide();
        $(".budget_diesel_label_block").show();
        $(".budget_advance_label_block").hide();
    }else{
        $('.qty_block').hide();
        $('.cash_block').hide();
        $('.card_block').hide();
        $('.additional_block').show();
        $(".budget_diesel_label_block").hide();
        $(".budget_advance_label_block").hide();
    }


}

</script>
@endsection 