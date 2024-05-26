@extends('layouts.app')
@section('title',(isset($editData))?'Edit Tyre Assign ':'Add Tyre Assign')
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
                                                <h4>Tyre Assign</h4>
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
                                    action=" {{ (isset($editData)) ? route('tyres.assigns.update',base64_encode($editData->id)) : route('tyres.assigns.store')}}"
                                    novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">

                                    <div class="form-group col-md-2">
                                     <label for="tyre_brand_id">Tyre </label>
                                     <select name="tyre_id" id="tyre_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Tyre Stock</option>       
                                        @if(isset($Tyres))
                                        @foreach($Tyres as $data)   
                                        <option @selected(isset($editData->tyre_id) && $editData->tyre_id == $data->id) 
                                            value='{{$data->id}}'
                                            >
                                            {{$data->mode}}|
                                            {{$data->serial_number}}|
                                            {{$data->size}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                
                                    <div  id="vehicle_id" class="form-group col-md-2">
                                         <label for="vehicle_id">Select Vehicle</label>
                                         <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;" required>
                                            <option value="">Select Vehicle</option>  

                                            @if(!empty($Vehicles))
                                            @foreach($Vehicles as $data)   
                                            <option value="{{$data->id}}"
                                               @selected(isset($editData->vehicle_id) && $editData->vehicle_id  == $data->id)
                                               >
                                               {{$data->registration_no}}
                                           </option>
                                           @endforeach
                                           @endif
                                       </select>
                                   </div>
                                  <div class="form-group col-md-2">
                                         <label for="position_vehicle_id">Position In Vehicle</label>
                                         <select name="position_vehicle_id" id="position_vehicle_id" class="form-control select2" style="width: 100%;" required>
                                            <option value="">Select Position Vehicle</option>       
                                            @if(isset($position_vehicle))
                                            @foreach($position_vehicle as $data)   
                                            <option value="{{$data->id}}"
                                                @selected(isset($editData->position_vehicle_id) && $editData->position_vehicle_id  == $data->id)
                                                >
                                                {{$data->name}}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                    <label>Start Date</label>
                                  <input type="date" name="start_date" class="form-control" id="start_date" value="{{(isset($editData->start_date))?$editData->start_date:''}}" required>
                                  </div>

                                    <div class="form-group col-md-2">
                                    <label>End Date</label>
                                  <input type="date" name="end_date" class="form-control" id="end_date" value="{{(isset($editData->end_date))?$editData->end_date:''}}" required>
                                  </div>

                            </div>

                            <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                            <a href="{{route('tyres.assigns.list')}}"  class="btn btn-danger">CANCEL</a>
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
        $("#vehicle_id").show();
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