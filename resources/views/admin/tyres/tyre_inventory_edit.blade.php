@extends('layouts.app')
@section('title',(isset($editData))?'Edit Tyre':'Add Tyre')
@section('content')
<?php 
if(isset($editData) && !empty($editData)){
    extract($editData->toArray()); 
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
                                                <h4>Tyre</h4>
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
                                    action=" {{ (isset($editData)) ? route('tyres.update',base64_encode($editData->id)) : route('tyres.store')}}"
                                    novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label for="mode">Tyre Mode</label>
                                            <select  name="mode" class="form-control select2" style="width: 100%;" required  >
                                             <option name="mode"  value="Vehicle"{{('Vehicle'==(isset($mode)?$mode:''))?'selected':''}}>Vehicle</option>
                                             <option  name="mode" value="InStock" {{('InStock'==(isset($mode)?$mode:''))?'selected':''}}>InStock</option>
                                         </select>

                                     </div>
                                     {{-- <div class="form-group col-md-2">
                                         <label for="vehicle_id">Select Vehicle</label>
                                         <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;" required>
                                            <option value="">Select Vehicle</option>  

                                            @if(!empty($Vehicles))
                                            @foreach($Vehicles as $data)   
                                            <option value="{{$data->id}}"
                                               @selected(isset($edit_dataa['vehicle_id']) && $edit_dataa['vehicle_id']  == $data->id)
                                               >
                                               {{$data->registration_no}}
                                           </option>
                                           @endforeach
                                           @endif
                                       </select>
                                   </div> --}}
                                   <div class="form-group col-md-2">
                                     <label for="tyre_brand_id">Tyre Brands</label>
                                     <select name="tyre_brand_id" id="tyre_brand_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Tyre Brands</option>       
                                        @if(isset($tyres_brand))
                                        @foreach($tyres_brand as $data)   
                                        <option @selected(isset($editData->tyre_brand_id) && $editData->tyre_brand_id == $data->id) 
                                            value='{{$data->id}}'
                                            >
                                            {{$data->name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                            {{-- <div class="form-group col-md-2">
                                              <label>Ledger Type</label> --}}
                                              <!-- <select name="party_type" id="party_type" class="form-control select2" style="width:100%">
                                                    <option value="" >CHOOSE COMPANY TYPE</option>
                                                    <option value="Company" {{('Company'==(isset($party_type)?$party_type:''))?'selected':''}}>
                                                        Company
                                                    </option>
                                                    <option value="Transporter" {{('Transporter'==(isset($party_type)?$party_type:''))?'selected':''}}>Transporter
                                                    </option>
                                                    <option value="Group Transporter" {{('Group Transporter'==(isset($party_type)?$party_type:''))?'selected':''}}>
                                                        Group Transporter
                                                    </option>
                                                    <option value="Employees" {{('Employees'==(isset($party_type)?$party_type:''))?'selected':''}}>
                                                        Employees
                                                    </option>
                                                    <option value="Driver" {{('Driver'==(isset($party_type)?$party_type:''))?'selected':''}}>
                                                        Driver
                                                    </option>
                                                </select> -->
{{-- 
                                              <select name="ledger_type_id" id="ledger_type_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">CHOOSE LEDGER TYPE</option>       
                                                    @if(!empty($ledger_types))
                                                    @foreach($ledger_types as $k =>$ledgerType)   
                                                        <option value="{{$ledgerType->id}}"
                                                            {{($ledgerType->id==(isset($ledger_type_id)?$ledger_type_id:0))?'selected':''}}
                                                        >
                                                            {{$ledgerType->name}}
                                                        </option>
                                                    @endforeach
                                                    @endif
                                                </select> --}}
                                            {{-- </div> --}}

                                            <div class="form-group col-md-2">
                                                <label>Tyre Serial Number</label>
                                                <input type="text" name="serial_number" class="form-control integers-only" id="serial_number" value="{{(isset($serial_number))?$serial_number:''}}" required>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Size</label>
                                                <input type="text" name="size" class="form-control integers-only" id="size" value="{{(isset($size))?$size:''}}" required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="tread_pattern">Tread Pattern</label>
                                                <select  name="tread_pattern" class="form-control select2" style="width: 100%;" required  >
                                                 <option name="tread_pattern"  value="Directional"{{('Directional'==(isset($tread_pattern)?$tread_pattern:''))?'selected':''}}>Directional</option>
                                                 <option value="Symmetrical" {{('Symmetrical'==(isset($tread_pattern)?$tread_pattern:''))?'selected':''}}>Symmetrical</option>
                                                 <option value="Asymmetrical" {{('Asymmetrical'==(isset($tread_pattern)?$tread_pattern:''))?'selected':''}}>Asymmetrical</option>
                                                 <option value="Directional/Asymmetrical" {{('Directional/Asymmetrical'==(isset($tread_pattern)?$tread_pattern:''))?'selected':''}}>Directional/Asymmetrical</option>
                                             </select>

                                         </div>

                                         <div class="form-group col-md-2">
                                            <label>Tread Depth (mm)</label>
                                            <input type="text" name="tread_depth" class="form-control" id="tread_depth" value="{{(isset($tread_depth))?$tread_depth:''}}" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Pressure</label>
                                            <input type="text" name="pressure" class="form-control" id="pressure" value="{{(isset($pressure))?$pressure:''}}" required>
                                        </div>
                                       {{--  <div class="form-group col-md-2">
                                         <label for="position_vehicle_id">Position In Vehicle</label>
                                         <select name="position_vehicle_id" id="position_vehicle_id" class="form-control select2" style="width: 100%;" required>
                                            <option value=""></option>       
                                            @if(isset($position_vehicle))
                                            @foreach($position_vehicle as $data)   
                                            <option value="{{$data->id}}"
                                                @selected(isset($edit_dataa['position_vehicle_id']) && $edit_dataa['position_vehicle_id']  == $data->id)
                                                >
                                                {{$data->name}}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div> --}}
                                    <div class="form-group col-md-2">
                                        <label>Max Running Limit (km)</label>
                                        <input type="text" name="max_running_limit" class="form-control" id="max_running_limit" value="{{(isset($max_running_limit))?$max_running_limit:''}}" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="tyre_condition">Tread Pattern</label>
                                        <select  name="tyre_condition" class="form-control select2" style="width: 100%;" required  >
                                         <option   value="New"{{('New'==(isset($tyre_condition)?$tyre_condition:''))?'selected':''}}>New</option>
                                         <option value="Retread" {{('Retread'==(isset($tyre_condition)?$tyre_condition:''))?'selected':''}}>Retread</option>
                                         <option value="Regroove" {{('Regroove'==(isset($tyre_condition)?$tyre_condition:''))?'selected':''}}>Regroove</option>
                                     </select>

                                 </div>
                                 <div class="form-group col-md-2">
                                    <label>Tyre Odo</label>
                                    <input type="text" name="odo" class="form-control" id="odo" value="{{(isset($odo))?$odo:''}}" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Manufacturer Date</label>
                                    <input type="date" name="manufacturer_dt" class="form-control" id="manufacturer_dt" value="{{(isset($manufacturer_dt))?$manufacturer_dt:''}}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks" rows="4" cols="4" 
                                    value="{{(isset($remarks))?$remarks:''}}">{{(isset($remarks))?$remarks:''}}</textarea>

                                </div>


                            </div>

                            <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                            <a href="{{route('party.list')}}"  class="btn btn-danger">CANCEL</a>
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