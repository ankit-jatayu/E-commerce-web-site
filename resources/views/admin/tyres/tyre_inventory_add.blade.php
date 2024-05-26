@extends('layouts.app')
@section('title',(isset($editData))?'Edit Tyre':'Add Tyre')
@section('content')
<?php 
if(isset($editData) && !empty($editData)){
    extract($editData); 
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="POST" 
                                    action=" {{ (isset($editData)) ? route('tyres.update',base64_encode($editData['id'])) : route('tyres.store')}}"
                                    novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <div  class="form-row">
                                        <div class="form-group col-md-2">
                                            <label for="mode">Tyre Mode</label>
                                            <select onchange="showHideVehicleBlock()" name="mode" id="mode" class="form-control select2" style="width: 100%;" required  >
                                            <option  name="mode" value="InStock" {{('InStock'==(isset($mode)?$mode:''))?'selected':''}}>InStock</option>
                                             <option name="mode" value="Vehicle"{{('Vehicle'==(isset($mode)?$mode:''))?'selected':''}}>Vehicle</option>
                                         </select>

                                     </div>
                                     <div  class="form-group col-md-2 vehicle_block">
                                         <label for="vehicle_id">Select Vehicle</label>
                                         <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;">
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
                                   </div>
                                   <div class="form-group col-md-2" >
                                     <label for="tyre_brand_id">Tyre Brands<span style="color: #4099ff"> (<i class="feather icon-plus" data-toggle="modal" data-target="#tyre-brand-Modal"></i>)</span></label>
                                     <select name="tyre_brand_id" id="tyre_brand_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Tyre Brands</option>       
                                        @if(isset($tyres_brand))
                                        @foreach($tyres_brand as $data)   
                                        <option @selected(isset($editData['tyre_brand_id']) && $editData['tyre_brand_id'] == $data->id) 
                                            value='{{$data->id}}'
                                            >
                                            {{$data->name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

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
                                                <select  name="tread_pattern" class="form-control select2" id="tread_pattern" style="width: 100%;" required  >
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
                                        <div class="form-group col-md-2 vehicle_block">
                                         <label for="position_vehicle_id">Position In Vehicle</label>
                                         <select name="position_vehicle_id" id="position_vehicle_id" class="form-control select2" style="width: 100%;">
                                            <option value="">Choose Position In Vehicle</option>       
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
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Max Running Limit (km)</label>
                                        <input type="text" name="max_running_limit" class="form-control" id="max_running_limit" value="{{(isset($max_running_limit))?$max_running_limit:''}}" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="tyre_condition">Tyre Condition </label>
                                        <select  name="tyre_condition" class="form-control select2" id="tyre_condition" style="width: 100%;" required  >
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
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks" rows="4" cols="4" value="{{(isset($remarks))?$remarks:''}}"></textarea>

                                </div>


                            </div>

                            <button type="submit"  class="btn btn-primary" >SAVE</button>
                            <a href="{{route('tyres.list')}}"  class="btn btn-danger">CANCEL</a>
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

<div class="modal fade" id="tyre-brand-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Tyre Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <h5 class="col-sm-4">Tyre Brand</h5>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control modal_input_field" name="name" id="name" placeholder="Name">
                      <span id="name_error" class="error"></span>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light saveCustomer">Save</button>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		showHideVehicleBlock();
	});

    function showHideVehicleBlock(){
            var mode=$("#mode option:selected").text();
               if(mode =='Vehicle'){
                $('.vehicle_block').show();
            }
            else{
                $('.vehicle_block').hide();
            }
    }
        
    $('.saveCustomer').click(function(){
        var name = $('#name').val();
        if(name == ''){
          $("#name_error").html("Please enter name");
          return false;
        }
        $.ajax({
                url: '{{ route('add.tyre.popup') }}',
                data: {'name': name,
                       "_token": "{{ csrf_token() }}",
                      },
                type: 'POST',
                dataType:'json',
                'success':function(data){ 
                    var html = '<option value="">Tyre Brands</option>';
                    $.each(data['TyreBrands'], function(index, item){
                        // var isSelected = (index===data['TyreBrands'].length-1)?'selected':'';                       
                        html += "<option value = '" + item.id + "'>" + item.name+ " </option>";
                    });
                    $("#tyre_brand_id").html(html).select2();
                    $("#tyre_brand_id").change();

                    $('#tyre-brand-Modal').modal('toggle');  
                    // $('.modal_input_field').val('');
                }
            });
    });

</script>
@endsection 