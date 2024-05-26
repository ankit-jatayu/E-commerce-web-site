@extends('layouts.app')
@section('title',(isset($editData))?'Edit Tyre Service Log ':'Add Tyre Service Log')
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
                                                <h4>Tyre Service Log</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" 
                                    action=" {{ (isset($editData)) ? route('tyre.service.log.update',base64_encode($editData['id'])) : route('tyre.service.log.store')}}"
                                    novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <div  class="form-row">
                                       
                                       <div  class="form-group col-md-2 ">
                                         <label for="tyre_id">Select Tyre Serial Number</label>
                                         <select name="tyre_id" id="tyre_id" onchange="getVehicleData();" class="form-control select2" style="width: 100%;" required>
                                            <option value="">Select Tyre Serial Number</option>  

                                            @if(!empty($Tyres))
                                            @foreach($Tyres as $data)   
                                            <option value="{{$data->id}}"
                                               @selected(isset($editData['tyre_id']) && $editData['tyre_id']  == $data->id)
                                               >
                                               {{$data->serial_number}}
                                           </option>
                                           @endforeach
                                           @endif
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>Vehicle Numbar</label>
                                            <input type="text" name="vehicle_no" class="form-control integers-only " id="vehicle_no" 
                                            value="{{(isset($vehicle_no))?$vehicle_no:''}}" required>
                                            <input type="hidden" id="vehicle_id" name="vehicle_id"  value="{{(isset($vehicle_id))?$vehicle_id:''}}" >
                                        </div>

                                   <div class="form-group col-md-2" >
                                     <label for="tyre_service_type_id">Select Service Type</label>
                                     <select name="tyre_service_type_id" id="tyre_service_type_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select Service Type</option>       
                                        @if(isset($TyresServiceType))
                                        @foreach($TyresServiceType as $data)   
                                        <option @selected(isset($editData['tyre_service_type_id']) && $editData['tyre_service_type_id'] == $data->id) 
                                            value='{{$data->id}}'
                                            >
                                            {{$data->name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                             <div class="form-group col-md-2">
                                <label>Service Amount</label>
                                <input type="text" name="service_amount" class="form-control integers-only" id="service_amount" value="{{(isset($service_amount))?$service_amount:''}}" required>
                            </div>

                                    
                                <div class="form-group col-md-2">
                                    <label>Service Date</label>
                                    <input type="date" name="service_date" class="form-control" id="service_date" value="{{(isset($service_date))?$service_date:''}}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks" rows="4" cols="4" value="{{(isset($remarks))?$remarks:''}}">{{(isset($remarks))?$remarks:''}}</textarea>

                                </div>


                            </div>

                            <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                            <a href="{{route('tyre.service.log.list')}}"  class="btn btn-danger">CANCEL</a>
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
		showHideVehicleBlock();
	});

function getVehicleData(){
    var tyre_id = $("#tyre_id").val();
    console.log(tyre_id);
    $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('get.VehicleData') }}',
                data: {'tyre_id': tyre_id,"_token": "{{ csrf_token() }}",},
                success: function (respond_data) {
                    // console.log(respond_data.vehicle_no);
                    $('#vehicle_no').val(respond_data.vehicle_no);
                    $('#vehicle_id').val(respond_data.vehicle_id);
                }
            });

}


    function showHideVehicleBlock(){
            var mode=$("#mode option:selected").text();
               if(mode =='Vehicle'){
                $('.vehicle_block').show();
            }
            else{
                $('.vehicle_block').hide();
            }
    }

</script>
@endsection 