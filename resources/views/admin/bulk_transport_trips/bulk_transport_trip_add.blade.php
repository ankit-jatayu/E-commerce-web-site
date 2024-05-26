@extends('layouts.app')
@section('title',(isset($editData))?'Edit Bulk Trip':'Add Bulk Trip')
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
                                               <h4>{{$title}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                                @if(isset($editData->id))
                                                    {{-- <a class="btn waves-effect waves-light btn-warning float-right ml-1" onclick="printLr({{$editData->id}})"><i class="feather icon-printer" style="color: white;"></i></a> --}}
                                                    <a href="{{route('bulk.transport.trip.add')}}" class="btn waves-effect waves-light btn-primary float-right "><i class="icofont icofont-plus"></i>Add New </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('bulk.transport.trip.update',base64_encode($editData->id)) : route('bulk.transport.trip.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="trip_id" id="trip_id" value="{{(isset($editData->id))?$editData->id:''}}">
                                         
                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                              <label for="is_market_lr">Market Vehicle</label>
                                              <div class="col-sm-12">
                                                <div class="checkbox-fade fade-in-primary">
                                                  <label>
                                                    <input type="checkbox" id="is_market_lr" name="is_market_lr" value="1" {{(isset($is_market_lr) && $is_market_lr=='1') ? 'checked' : ''}} onchange="showHideMarketLrBlock()">
                                                    <span class="cr">
                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                    </span>
                                                    
                                                  </label>
                                                </div>

                                                <span class="messages"></span>
                                              </div>
                                            </div>

                                            <!-- <div class="form-group col-md-2">
                                                <label>Trip Date&Time</label>
                                                <input type="datetime-local" name="entry_date" class="form-control" id="entry_date" value="{{(isset($entry_date))?$entry_date:date('Y-m-d H:i')}}" >
                                            </div> -->
                                          
                                            <div class="form-group col-md-2">
                                                <label>LR. Date</label>
                                                <input type="date" name="lr_date" class="form-control" id="lr_date" value="{{(isset($lr_date))?$lr_date:date('Y-m-d')}}" >
                                            </div>
                                            
                                           
                                             <div class="form-group col-md-2">
                                                <label for="route_id">Route</label>
                                                <select name="route_id" id="route_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">CHOOSE ROUTE</option>       
                                                    @if(!empty($Routes))
                                                    @foreach($Routes as $k =>$route)   
                                                        <option value="{{$route->id}}"
                                                            {{($route->id==(isset($route_id)?$route_id:0))?'selected':''}}
                                                        >
                                                            <?php 
                                                                $routeData= $route;
                                                                $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                                                                $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                                                                $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                                                                echo $RouteName;
                                                            ?>
                                                        </option>       
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Billing Party</label>
                                                <select name="party_id" id="party_id" class="form-control select2" style="width: 100%;"  required>
                                                    <option value="">CHOOSE BILLING PARTY</option>
                                                    @if(!empty($billing_parties))
                                                        @foreach($billing_parties as $k =>$singledata)   
                                                            <option value="{{$singledata->id}}" 
                                                                    {{($singledata->id==(isset($party_id)?$party_id:0))?'selected':''}}
                                                            >
                                                                {{$singledata->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('party_id')
                                                    <label class="invalid-feedback">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Vehicle No.</label>
                                               
                                                <select name="vehicle_id" id="vehicle_id" class="form-control owner_lr_block_input select2" required onchange="getVehicleData()">
                                                <option value="" transporter_party_id ="" transporter_party_name ="" driver_id ="" driver_name =""  >CHOOSE VEHICLE</option>
                                                    @if(!empty($Vehicles))
                                                        @foreach($Vehicles as $k =>$singledata)   
                                                            <option value="{{$singledata['id']}}" 
                                                                {{($singledata['id']==(isset($vehicle_id)?$vehicle_id:0))?'selected':''}}
                                                                transporter_party_id="{{$singledata['transporter_party_id']}}"
                                                                transporter_party_name="{{$singledata['transporter_party_name']}}"
                                                                driver_id="{{$singledata['driver_id']}}"
                                                                driver_name="{{$singledata['driver_name']}}"
                                                             >
                                                            {{$singledata['registration_no']}}
                                                          </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>    
                                            
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Driver</label>
                                                <input type="hidden" name="driver_id" id="driver_id" value="{{(isset($driver_id))?$driver_id:''}}">
                                                <input type="text" name="driver_name" class="form-control" id="driver_name" value="{{(isset($edit_selected_driver_name))?$edit_selected_driver_name:''}}" readonly>
                                            </div>
                                            
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Transporter</label>
                                                <input type="hidden" name="transporter_id" id="transporter_party_id" value="{{(isset($transporter_id))?$transporter_id:1}}">
                                                <input type="text" name="transporter_party_name" class="form-control" id="transporter_party_name" value="{{(isset($edit_selected_vehicle_transporter_name))?$edit_selected_vehicle_transporter_name:''}}" readonly>
                                            </div>

                                           <div class="form-group col-md-2 market_lr_block">
                                                <label>Transporter</label>
                                                <select name="market_transporter_id" id="market_transporter_id" class="form-control market_lr_block_input select2" style="width:100%">
                                                    <option value="" tds_per="">CHOOSE TRANSPORTER</option>
                                                    @if(!empty($transport_parties))
                                                        @foreach($transport_parties as $k =>$singledata)   
                                                            <option value="{{$singledata->id}}" 
                                                                tds_per="{{$singledata->tds_per}}"
                                                                {{($singledata->id==(isset($transporter_id)?$transporter_id:0))?'selected':''}}>
                                                                {{$singledata->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('vehicle_id')
                                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-2 market_lr_block" style="display:none">
                                                <label>Vehicle No</label>
                                                <input type="text" name="market_vehicle_no" class="form-control market_lr_block_input" id="market_vehicle_no" value="{{(isset($market_vehicle_no))?$market_vehicle_no:''}}" placeholder="Vehicle No" >
                                                <label class="invalid-feedback" id="market_vehicle_running_err" style="display: block;"></label>
                                            </div>

                                            
                                            <div class="form-group col-md-2 market_lr_block" style="display:none">
                                                <label>Market Freight</label>
                                                <input type="text" name="market_freight" class="form-control market_lr_block_input decimal-only" id="market_freight" value="{{(isset($market_freight))?$market_freight:''}}" placeholder="Market Freight">
                                            </div>

                                            <!-- <div class="form-group col-md-2 market_lr_block" style="display:none;">
                                                <label>TDS Amt</label>
                                                <input type="text" name="tds_amount" placeholder="TDS Amt" class="form-control decimal-only" id="tds_amount" value="{{(isset($tds_amount))?$tds_amount:''}}" readonly>
                                            </div> -->
                                            <div class="form-group col-md-2">
                                                <label>Material</label>
                                                <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#material-modal"></span>
                                                <select name="material_id" id="material_id" class="form-control select2" required >
                                                <option value="" >CHOOSE MATERIAL</option>
                                                    @if(!empty($materials))
                                                        @foreach($materials as $k =>$row)   
                                                            <option value="{{$row['id']}}" 
                                                                {{($row['id']==(isset($material_id)?$material_id:''))?'selected':''}}
                                                             >
                                                            {{$row['name']}}
                                                          </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Consignees</label>
                                                <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#consignees-modal"></span>
                                                <select name="consigness_id" id="consigness_id" class="form-control select2">
                                                <option value="" >CHOOSE CONSIGNEES</option>
                                                    @if(!empty($consignees))
                                                        @foreach($consignees as $k =>$row)   
                                                            <option value="{{$row['id']}}" 
                                                                {{($row['id']==(isset($consigness_id)?$consigness_id:''))?'selected':''}}
                                                             >
                                                            {{$row['company_name']}}
                                                          </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Consigner</label>
                                                <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#consigner-modal"></span>
                                                <select name="consigner_id" id="consigner_id" class="form-control select2">
                                                <option value="" >CHOOSE CONSIGNER</option>
                                                    @if(!empty($consignees))
                                                        @foreach($consignees as $k =>$row)   
                                                            <option value="{{$row['id']}}" 
                                                                {{($row['id']==(isset($consigner_id)?$consigner_id:''))?'selected':''}}
                                                             >
                                                            {{$row['company_name']}}
                                                          </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>   
                                            <div class="form-group col-md-2">
                                                <label>Rate</label>
                                                <input type="text" name="rate" placeholder="Rate" class="form-control decimal-only" id="rate" value="{{ isset($rate) ? $rate : '' }}" required onchange="calculateFreight()">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Loading Weight</label>
                                                <input type="text" name="l_mt" placeholder="Loading Weight" class="form-control decimal-only" id="l_mt" value="{{ isset($l_mt) ? $l_mt : '' }}" onchange="calculateFreight()" required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Unloading Weight</label>
                                                <input type="text" name="u_mt" placeholder="Unloading Weight" class="form-control decimal-only" id="u_mt" value="{{ isset($u_mt) ? $u_mt : '' }}"  required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Freight</label>
                                                <input type="text" name="freight" placeholder="Freight" class="form-control decimal-only" id="freight" value="{{(isset($freight))?$freight:''}}" required>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Driver Advance</label>
                                                <input type="text" name="driver_advance" placeholder="Driver Advance" class="form-control decimal-only" id="driver_advance" 
                                                value="{{(isset($editTripVchrDriverAdvAmt))?$editTripVchrDriverAdvAmt:''}}">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Diesel</label>
                                                <input type="text" name="diesel" placeholder="Diesel" class="form-control decimal-only" id="diesel" 
                                                value="{{(isset($editTripVchrDieselAmt))?$editTripVchrDieselAmt:''}}">
                                            </div>
                                         
                                            <div class="form-group col-md-4 lr_scan-block">
                                                <label>Lr Copy</label><br>
                                                <input type="file" name="lr_scan" id="lr_scan" >
                                                 @if(isset($editData['lr_scan']) && $editData['lr_scan']!='')
                                                    <a href="{{URL::to('public/uploads/lr_scan/'.$editData['lr_scan'])}}" target="_blank">
                                                        <img src ="{{asset('admin/images/Download-Icon.png')}}" class="img-40">
                                                    </a>
                                                 @endif 
                                            </div> 
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('bulk.transport.trip.list')}}"  class="btn btn-danger">CANCEL</a>

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

<div class="modal fade bd-example-modal-md" id="material-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Material Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="material_name">Name <span class="error">*</span></label>
        <input type="text" name="material_name" class="form-control" id="material_name" placeholder="Name"required>
    </div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" onclick="addMaterialPopup()">Save </button>
</div>
</div>
</div>
</div>

<div class="modal fade bd-example-modal-md" id="consignees-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consignees Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <div class="form-row">
            <div class="form-group col-md-6">
             <label for="company_name">Company Name<span style="color:red">*</span></label>
             <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Company Name" required>
             @error('company_name')
             <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
             @enderror
         </div>
         <div class="form-group col-md-6">
            <label for="gst_no">GST No<span style="color:red">*</span></label>
            <input type="text" name="gst_no" class="form-control" id="gst_no" placeholder="GST No" required>
            @error('gst_no')
            <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
            @enderror
        </div>
        <div class="form-group col-md-12">
            <label> Address<span style="color:red">*</span></label>
            <textarea  name="address" class="form-control" id="address" placeholder="Please provide Address"></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" onclick="addConsigneesPopup()">Save </button>
</div>
</div>
</div>
</div>

<div class="modal fade bd-example-modal-md" id="consigner-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consigner Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <div class="form-row">
            <div class="form-group col-md-6">
             <label for="c_name">Company Name<span style="color:red">*</span></label>
             <input type="text" name="c_name" class="form-control" id="c_name" placeholder="Company Name" required>
             @error('c_name')
             <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
             @enderror
         </div>
         <div class="form-group col-md-6">
            <label for="g_no">GST No<span style="color:red">*</span></label>
            <input type="text" name="g_no" class="form-control" id="g_no" placeholder="GST No" required>
            @error('g_no')
            <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
            @enderror
        </div>
        <div class="form-group col-md-12">
            <label> Address<span style="color:red">*</span></label>
            <textarea  name="consigner_address" class="form-control" id="consigner_address" placeholder="Please provide Address"></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" onclick="addConsignerPopup()">Save </button>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        showHideMarketLrBlock();
    });

    function showHideMarketLrBlock(){
        checkBox = document.getElementById('is_market_lr');
        if(checkBox.checked) {
            $(".market_lr_block").show();
            $(".owner_lr_block").hide();

            $(".market_lr_block_input").attr("required","required");
            $(".owner_lr_block_input").removeAttr("required");
            

        }else{
            $(".market_lr_block").hide();
            $(".owner_lr_block").show();

            $(".owner_lr_block_input").attr("required","required");
            $(".market_lr_block_input").removeAttr("required");
        }
    }

   
    function calculateFreight() {
        var rate = parseFloat($("#rate").val()); 
        var lmt = parseFloat($("#l_mt").val());

        var freight = rate * lmt;

        $("#freight").val(freight); 
    }


    $("#is_trip").on('change', function(){
        var is_trip = $("#is_trip").val();
        //console.log(is_trip)
        if(is_trip=="Old"){
           $(".old_trip_block").show();
           $("#old_trip_no").attr("required","required") 
        }else{
           $(".old_trip_block").hide();
           $("#old_trip_no").removeAttr("required") 
        }
    })
    
    function getVehicleData() {
        var driver_id= $('#vehicle_id option:selected').attr("driver_id");   
        var driver_name= $('#vehicle_id option:selected').attr("driver_name");   
        var transporter_party_id= $('#vehicle_id option:selected').attr("transporter_party_id");   
        var transporter_party_name= $('#vehicle_id option:selected').attr("transporter_party_name");
        
        $("#driver_id").val(driver_id);
        $("#driver_name").val(driver_name);
        $("#transporter_party_id").val(transporter_party_id);
        $("#transporter_party_name") .val(transporter_party_name);
        

    }

    function addMaterialPopup() {
        var material_name = $('#material_name').val();

        $.ajax({
            url: "{{ route('add.material') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "material_name": material_name,
            },
            dataType:'json',
            success: function (Result) {

                var html = '<option value="" >CHOOSE MATERIAL</option>';
                var selectedPartyId = null;

                $.each(Result, function (index, item) {
                    html += "<option value='" + item.id + "'>" + item.name + "</option>";
                    selectedPartyId = item.id;  
                });
                $("#material_id").html(html);
                if (selectedPartyId !== null) {
                    $("#material_id").val(selectedPartyId);
                }
                $("#material_id").select2();
                $('#material-modal').modal('hide');
            }

        });
    }

    function addConsigneesPopup() {
        var company_name = $('#company_name').val();
        var gst_no = $('#gst_no').val();
        var address = $('#address').val();

        $.ajax({
            url: "{{ route('add.consigness') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "company_name": company_name,
                "gst_no": gst_no,
                "address": address,
            },
            dataType:'json',
            success: function (Result) {

                var html = '<option value="" >CHOOSE CONSIGNEES</option>';
                var selectedPartyId = null;

                $.each(Result, function (index, item) {
                    html += "<option value='" + item.id + "'>" + item.company_name + "</option>";
                    selectedPartyId = item.id;  
                });
                $("#consigness_id").html(html);
                if (selectedPartyId !== null) {
                    $("#consigness_id").val(selectedPartyId);
                }
                $("#consigness_id").select2();
                $('#consignees-modal').modal('hide');
            }

        });
    }

    function addConsignerPopup() {
        var c_name = $('#c_name').val();
        var g_no = $('#g_no').val();
        var consigner_address = $('#consigner_address').val();

        $.ajax({
            url: "{{ route('add.consigner') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "c_name": c_name,
                "g_no": g_no,
                "consigner_address": consigner_address,
            },
            dataType:'json',
            success: function (Result) {

                var html = '<option value="" >CHOOSE CONSIGNEES</option>';
                var selectedPartyId = null;

                $.each(Result, function (index, item) {
                    html += "<option value='" + item.id + "'>" + item.company_name + "</option>";
                    selectedPartyId = item.id;  
                });
                $("#consigner_id").html(html);
                if (selectedPartyId !== null) {
                    $("#consigner_id").val(selectedPartyId);
                }
                $("#consigner_id").select2();
                $('#consigner-modal').modal('hide');
            }

        });
    }
</script>
@endsection