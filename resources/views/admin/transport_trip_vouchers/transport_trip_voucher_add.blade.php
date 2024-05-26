@extends('layouts.app') @section('title',(isset($editData))?'Edit Trip Voucher':'Add Trip Voucher') @section('content')

<?php 
    if(isset($editData) && !empty($editData)){
        extract($editData->toArray());
    }
?>

<style type="text/css">
    @if(isset($editData) && $editData->payment_type_id=2) /*if payment_type_id==diesel*/
        .fuel-station-block{
            display:block;
        }
    @else
        .fuel-station-block{
            display:none;
        }
    @endif
    
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
                                                    <h4>{{$title}}</h4>

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="page-header-breadcrumb float-right ">
                                                    @if(isset($editData->id)) 
                                                    {{-- @if($payment_type_id == 2)
                                                    <a class="btn waves-effect btn-sm ml-1" onclick="printDiesel({{$editData->id}})"><i class="feather icon-printer" style="color: white;"></i></a> @else
                                                    <a class="btn btn-sm btn-warning  ml-1" onclick="printTrip({{$editData->id}})"><i class="feather icon-printer" style="color: white;"></i></a> @endif --}}
                                                    <a href="{{route('transport.trip.voucher.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i>Add New </a> 
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <form id="main" method="post" action=" {{ (isset($editData)) ? route('transport.trip.voucher.update',base64_encode($editData->id)) : route('transport.trip.voucher.store')}}" novalidate="" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-row">
                                                {{--
                                                <div class="form-group col-md-2">
                                                    <label>Ref. No</label>
                                                    <input type="text" name="voucher_no" class="form-control" id="voucher_no" value="{{(isset($voucher_no))?$voucher_no:$newVoucherNo}}" readonly>
                                                </div> --}} {{--
                                                <div class="form-group col-md-2">
                                                    <label for="is_party_advance">Party Advance</label>
                                                    <br>
                                                    <input type="checkbox" name="is_party_advance" id="is_party_advance" value="1" {{(isset($is_party_advance) && $is_party_advance=='1' ) ? 'checked' : ''}}>
                                                </div> --}}

                                                <div class="form-group col-md-2">
                                                    <label for="is_party_advance">Party Advance</label>
                                                    <div class="col-sm-12">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input type="checkbox" id="is_party_advance" name="is_party_advance" value="{{$is_party_advance ?? 0}}" 
                                                                @checked((isset($is_party_advance) && $is_party_advance==1 ))
                                                                >
                                                                <span class="cr">
                                                          <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                        </span>

                                                            </label>
                                                        </div>

                                                        <span class="messages"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Branch</label>
                                                    <select name="branch" id="branch" class="form-control select2" required style="width:100%">
                                                        <option value="Gandhidham" {{( 'Gandhidham'==(isset($branch)?$branch: ''))? 'selected': ''}}>Gandhidham</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Date</label>
                                                    <input type="date" name="voucher_entry_date" class="form-control" id="voucher_entry_date" value="{{(isset($voucher_entry_date))?$voucher_entry_date:date('Y-m-d')}}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>VCH Date</label>
                                                    <input type="date" name="voucher_date" class="form-control" id="voucher_date" value="{{(isset($voucher_date))?$voucher_date:date('Y-m-d')}}" required>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Payment Mode</label>
                                                    <select name="payment_mode" id="payment_mode" class="form-control select2" required style="width: 100%;">
                                                        <option value="Cash" @selected( 'Cash'==(isset($payment_mode)?$payment_mode: ''))>Cash</option>
                                                        <option value="Credit" @selected( 'Credit'==(isset($payment_mode)?$payment_mode: ''))>Credit</option>
                                                        <option value="Bank" @selected( 'Bank'==(isset($payment_mode)?$payment_mode: ''))>Bank</option>
                                                    </select>
                                                </div>
                                                {{--
                                                <input type="hidden" name="budgeted_advance" id="budgeted_advance" value="{{(isset($budgeted_advance))?$budgeted_advance:''}}">
                                                <input type="hidden" name="budgeted_diesel" id="budgeted_diesel" value="{{(isset($budgeted_diesel))?$budgeted_diesel:''}}">
                                                <input type="hidden" name="budgeted_sez" id="budgeted_sez" value="{{(isset($budgeted_sez))?$budgeted_sez:''}}"> --}}

                                                <div class="form-group col-md-2">
                                                    <label>Vehicle No.<span style="color:red">*</span></label>
                                                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" required onchange="getVehicleData()" style="width:100%">
                                                        <option value="" is_market_lr="" transporter="">CHOOSE VEHICLE</option>
                                                        @if(!empty($vehicles)) @foreach($vehicles as $k =>$singledata)
                                                        <?php 
                                                            $isSelected="";
                                                                if(isset($editData->vehicle_id) && $editData->vehicle_id== $singledata->id){
                                                                    $isSelected="selected";
                                                                }
                                                            ?>
                                                            <option value="{{$singledata->id}}" is_market_lr="{{$singledata->is_market_lr}}" transporter="{{$singledata->transporter}}" {{$isSelected}}>
                                                                {{$singledata->registration_no}}
                                                            </option>
                                                            @endforeach @endif
                                                    </select>
                                                    @error('vehicle_id')
                                                    <label class="invalid-feedback" style="display: block;">{{ $message }}
                                                    </label>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label>Trip No</label>
                                                    <select name="trip_id" id="trip_id" class="form-control select2" onchange="getTripData()" style="width:100%">
                                                        <option value="" driver_id="" driver_name="">CHOOSE TRIP NO</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 trip_transporter_block">
                                                    <label>Transporter</label>
                                                    <input type="text" class="form-control" id="trip_transporter" value="{{(isset($trip_transporter))?$trip_transporter:''}}" readonly>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Driver Name</label>
                                                    <input type="text" name="driver_name" class="form-control" id="driver_name" value="{{(isset($driver_name))?$driver_name:''}}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Payment Type <span style="color:red">*</span></label>
                                                    <select name="payment_type_id" id="payment_type_id" class="form-control select2" required style="width:100%">
                                                        <option value="">CHOOSE PAYMENT TYPE</option>
                                                        @if(!empty($paymentTypes)) @foreach($paymentTypes as $k =>$singledata)
                                                        <option value="{{$singledata->id}}" {{($singledata->id==(isset($payment_type_id)?$payment_type_id:0))?'selected':''}}>{{$singledata->name}}</option>
                                                        @endforeach @endif
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 fuel-station-block">
                                                    <label>Fuel Station <span style="color:red">*</span></label>
                                                    <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPartyModal('fuel_station_id')"></span>

                                                    <select name="fuel_station_id" id="fuel_station_id" 
                                                        class="form-control select2 input-field" style="width:100%">
                                                        <option value="">CHOOSE FUEL STATION</option>
                                                        @if(!empty($fuelStations))
                                                            @foreach($fuelStations as $k =>$row)
                                                            <option value="{{$row->id}}" 
                                                                @selected(($row->id==(isset($fuel_station_id)?$fuel_station_id:0))?'selected':'')
                                                            >
                                                                {{$row->name}}
                                                            </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 fuel-station-block">
                                                    <label>Fuel Qty <span style="color:red">*</span></label>
                                                    <input type="text" name="fuel_qty" 
                                                        class="form-control decimal-only input-field" 
                                                        id="fuel_qty" placeholder="Fuel Qty"
                                                        value="{{(isset($fuel_qty))?$fuel_qty:''}}"
                                                    >
                                                </div>
                                                <div class="form-group col-md-2 fuel-station-block">
                                                    <label>Fuel Rate <span style="color:red">*</span></label>
                                                    <input type="text" name="fuel_rate" 
                                                        class="form-control decimal-only input-field" 
                                                        id="fuel_rate" placeholder="Fuel Rate"
                                                        value="{{(isset($fuel_rate))?$fuel_rate:''}}"
                                                    >
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Amount</label>
                                                    <input type="text" name="amount" 
                                                           class="form-control decimal-only " id="amount" 
                                                           placeholder="Amount" 
                                                           value="{{(isset($amount))?$amount:''}}">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea class="form-control" name="remarks" id="remarks">{{$editData->remarks ?? ''}}</textarea>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                            <a href="{{route('transport.trip.voucher.list')}}" class="btn btn-danger">CANCEL</a>

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


@include('admin.common_components.add_party_via_modal')


    <script type="text/javascript">
        // $('.number').keypress(function(event) {
        //     if (event.which != 46 && (event.which < 47 || event.which > 59)){
        //         event.preventDefault();
        //         if((event.which == 46) && ($(this).indexOf('.') != -1)) {
        //             event.preventDefault();
        //         }
        //     }
        // });
    
        // $('.decimal').keypress(function(evt){
        //     return (/^[0-9]*\.?[0-9]*$/).test($(this).val()+evt.key);
        // });
    
        // function preventZero($idparam){
        //     dPurchaseQty=$('#'+$idparam).val();
        //     if(dPurchaseQty==0){
        //         $('#'+$idparam).val('');
        //         return false;
        //     }
        // }
    
        $('body').on('change','#main #is_party_advance',function(e){
            if(this.checked==true){
                $('#main #is_party_advance').val(1);
            }else{
                $('#main #is_party_advance').val(0);
            }
        });
    
        $(document).ready(function() {
           getVehicleData();
        });
       
        function getVehicleData() {
            var is_market_lr=$("#vehicle_id option:selected").attr('is_market_lr');
            var transporter=$("#vehicle_id option:selected").attr('transporter');
            $("#trip_transporter").val(transporter);
    
            if(is_market_lr=='1'){
                $(".trip_transporter_block").show();
            }else{
                $(".trip_transporter_block").hide();
            }
    
    
            var vehicle_id=$("#vehicle_id option:selected").val();
            var trip_id = '{{$trip_id ?? ''}}';
            if(vehicle_id!=''){
                $.ajax({
                    type: "POST",
                    url: '{{ route('get.transport.trip') }}',
                    data: {'vehicle_id': vehicle_id,'trip_id':trip_id,"_token": "{{ csrf_token() }}",},
                    dataType:'json',
                    success: function (data) {
                        
                        var html ='<option value="" driver_id="" driver_name="">CHOOSE TRIP NO </option>';
                        //console.log(data);
    
                        $(data).each((index, data) => {
                            var route_name=data['from_station'];
                            if(data['to_station']!=''){
                                route_name+='_'+data['to_station'];
                            }
                            if(data['back_to_station']!=''){
                                route_name+='_'+data['back_to_station'];
                            }
    
                            var selected = '';
                            if(data['id'] == trip_id){
                                selected = 'selected';
                            }
                            html +='<option value="'+data['id']+'" driver_id="'+data['driver_id']+'" driver_name="'+data['driver_name']+'"  '+selected+'>'+data['lr_no']+'_'+route_name+'</option>';
                        });
    
                        $("#trip_id").html(html).select2();
                        getTripData();
                    }
                });    
            }
            
        }
    
        function getTripData() {
            var vehicle_id=$("#vehicle_id option:selected").val();
            var trip_id=$("#trip_id option:selected").val();
    
            var driver_id= $('#trip_id option:selected').attr("driver_id");   
            var driver_name= $('#trip_id option:selected').attr("driver_name");
            
            $("#driver_id").val(driver_id);
            $("#driver_name").val(driver_name);
           // getPaymentType();
            
        }

        $('body').on('change', '#main #payment_type_id', function() {
            var payment_type_id = $('option:selected','#main #payment_type_id').val();
            if(payment_type_id==2){
                $("#main .fuel-station-block").show();
                $("#main .fuel-station-block .input-field").attr('required','required');
            }else{
                $("#main .fuel-station-block").hide();
                $("#main .fuel-station-block .input-field").removeAttr('required');
            }
        });


        function openPartyModal(curnt_dropdown_id){
            if(curnt_dropdown_id=='consignor_id'){
                $('#party-modal #partyModalTitle').html('Add Consignor');
            }else if(curnt_dropdown_id=='consignee_id'){
                $('#party-modal #partyModalTitle').html('Add Consignee');
            }else if(curnt_dropdown_id=='payable_party_id'){
                $('#party-modal #partyModalTitle').html('Add Payable Party');
            }else if(curnt_dropdown_id=='fuel_station_id'){
                $('#party-modal #partyModalTitle').html('Add Fuel Station');
            }


            $('#party-modal').modal('show');
            $('#party-modal #curnt_dropdown_id').val(curnt_dropdown_id);

        }

        function addPartyViaPopup() {
            var name = $('#party-modal #name').val();
            var party_type_id = $('#party-modal #party_type_id').val();
           
            $('#party-modal .error').html(' ');
            if(name==''){
                $('#party-modal #name_error').html('field is required');
                return false
            }
            if(party_type_id.length==0){
                $('#party-modal #party_type_error').html('field is required');
                return false;
            }

            $.ajax({
                url: "{{ route('trip.add.consignor') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "name": name,
                    "party_type_id": party_type_id,
                },
                success: function (party_id) {
                    var curnt_dropdown_id= $('#party-modal #curnt_dropdown_id').val();

                    if(party_id==0){
                        $('#party-modal #name_error').html('party already exist !!');
                        return false;
                    }else{
                        var html = '<option value="'+party_id+'" >'+$('#party-modal #name').val()+'</option>';
                        
                        if(curnt_dropdown_id!='payable_party_id'){
                          $("#"+curnt_dropdown_id).append(html);
                          $("#"+curnt_dropdown_id).val(party_id).select2();

                        }else if(curnt_dropdown_id=='payable_party_id'){
                            $("#payable_party_id_disp").append(html);
                            $("#payable_party_id_disp").val(party_id).select2();
                            $("#payable_party_id").val(party_id);
                        }

                        $('#party-modal').modal('hide');
                        $('#party-modal .modal-input').val('');
                        $('#party-modal .modal-input-select').val(null).trigger('change');
                    }
                    
                }

            });
        }


        //old 
    
        // function getPaymentType(){
        //     var paymenttype=$("#payment_type_id option:selected").text();
        //     var payment_type_id=$("#payment_type_id option:selected").val();
        //     var trip_id=$("#trip_id option:selected").val();
        //     var voucher_id = '{{$id ?? ''}}';
    
        //     $.ajax({
        //         type: "POST",
        //         dataType: "json",
        //         url: '{{ route('get.budgeted.trip') }}',
        //         data: {'payment_type_id': payment_type_id,'trip_id':trip_id,'voucher_id':voucher_id,"_token": "{{ csrf_token() }}",},
        //         success: function (data) {
        //             $("#budgeted_advance").val(data.remain_budgeted_advance);
        //             $("#budgeted_diesel").val(data.remain_budgeted_diesel);
        //             $("#budgeted_sez").val(data.remain_budgeted_sez);
                     
        //             $("#budgeted_advance_label").text(data.remain_budgeted_advance);
        //             $("#budgeted_diesel_label").text(data.remain_budgeted_diesel);
        //             $("#budgeted_sez_label").text(data.remain_budgeted_sez);
    
        //             if(paymenttype=='Driver Advance'){
        //                 //$("#total_amount").val(0); 
        //                 $('.cash_block').show();
        //                 $('.card_block').show();
        //                 $('.qty_block').hide();
        //                 $('.additional_block').show();
        //                 $(".budget_advance_label_block").show();
        //                 $(".budget_diesel_label_block").hide();
        //                 $(".budget_sez_label_block").hide();
    
        //                 preventEnterOverBudgetAdvance();
        //             }else if(paymenttype=='Diesel'){
        //                 //$("#total_amount").val(0); 
        //                 $('.qty_block').show();
        //                 $('.cash_block').hide();
        //                 $('.card_block').hide();
        //                 $('.additional_block').hide();
        //                 $(".budget_diesel_label_block").show();
        //                 $(".budget_advance_label_block").hide();
        //                 $(".budget_sez_label_block").hide();
        //                 preventEnterOverBudgetDiesel();
        //             }else if(paymenttype=='Adani Sez Entry'){
        //                 $("#total_amount").val(0); 
        //                 $('.cash_block').show();
        //                 $('.card_block').show();
        //                 $('.qty_block').hide();
        //                 $('.additional_block').show();
        //                 $(".budget_advance_label_block").hide();
        //                 $(".budget_diesel_label_block").hide();
        //                 $(".budget_sez_label_block").show();
        //                 preventEnterOverBudgetSEZ();
        //             }else{
        //                 $('.qty_block').hide();
        //                 $('.cash_block').hide();
        //                 $('.card_block').hide();
        //                 $('.additional_block').show();
        //                 $(".budget_diesel_label_block").hide();
        //                 $(".budget_advance_label_block").hide();
        //                 $(".budget_sez_label_block").hide();
        //             }
        //             showIsAuthorisedBlock();
        //         }
        //     }); 
    
        // }
    
        // function showIsAuthorisedBlock(){
        //     var additional_cash_amount = ($("#additional_cash_amount").val()!='')?parseFloat($("#additional_cash_amount").val()):0;
        //     var additional_card_amount = ($("#additional_card_amount").val()!='')?parseFloat($("#additional_card_amount").val()):0;
        //     var additional_qty = ($("#additional_qty").val()!='')?parseFloat($("#additional_qty").val()):0;
        //     var paymenttype=$("#payment_type_id option:selected").text();
        //     if(paymenttype=='Driver Advance' || paymenttype=='Diesel' || paymenttype=='Adani Sez Entry'){
        //         if(additional_cash_amount>0 || additional_card_amount>0 || additional_qty>0){
        //             $(".authorised_by_block").show();
        //             $('#remarks').prop('required',true);
        //         }else{
        //             $(".authorised_by_block").hide();
        //             $('#remarks').prop('required',false);
        //         }
        //     }else{
        //         $(".authorised_by_block").show();
        //         $('#remarks').prop('required',true);
        //     }
        // }//func close
    
        // function calcTotalAmt(){
        //     var cash_amount = ($("#cash_amount").val()!='')?parseFloat($("#cash_amount").val()):0;
        //     var additional_cash_amount = ($("#additional_cash_amount").val()!='')?parseFloat($("#additional_cash_amount").val()):0;
        //     var card_amount = ($("#card_amount").val()!='')?parseFloat($("#card_amount").val()):0;
        //     var additional_card_amount = ($("#additional_card_amount").val()!='')?parseFloat($("#additional_card_amount").val()):0;
        //     var total=(cash_amount+additional_cash_amount+card_amount+additional_card_amount);
        //     $("#total_amount").val(total.toFixed(2)); 
        // }
    
        // function calcTotalQty(){
        //     var qty = ($("#qty").val()!='')?parseFloat($("#qty").val()):0;
        //     var additional_qty = ($("#additional_qty").val()!='')?parseFloat($("#additional_qty").val()):0;
        //     var total=(qty+additional_qty);
        //     $("#total_qty").val(total.toFixed(2)); 
        // }
    
        // function preventEnterOverBudgetAdvance(){
        //     var cash_amount = ($("#cash_amount").val()!='')?parseFloat($("#cash_amount").val()):0;
        //     var card_amount = ($("#card_amount").val()!='')?parseFloat($("#card_amount").val()):0;
        //     var total=(cash_amount+card_amount);
        //     var budgeted_advance =($("#budgeted_advance").val()!='')?parseFloat($("#budgeted_advance").val()):0;
        //     var budgeted_sez =($("#budgeted_sez").val()!='')?parseFloat($("#budgeted_sez").val()):0;
    
        //     var paymenttype=$("#payment_type_id option:selected").text();
        //     if(paymenttype=='Driver Advance'){
        //         if(total>budgeted_advance){
        //             $("#saveData").prop("disabled",true);
        //             alert('invalid amount');
        //             $("#total_amount").val(0); 
        //             $("#cash_amount").val('');
        //             $("#card_amount").val('');
        //             $("#cash_amount").focus();
        //         }else{
        //             $("#saveData").prop("disabled",false);
        //         }
        //     }else if(paymenttype=='Adani Sez Entry'){
        //         if(total>budgeted_sez){
        //             $("#saveData").prop("disabled",true);
        //             alert('invalid amount');
        //             $("#total_amount").val(0); 
        //             $("#cash_amount").val('');
        //             $("#card_amount").val('');
        //             $("#cash_amount").focus();
        //         }else{
        //             $("#saveData").prop("disabled",false);
        //         }
        //     }        
        // }
       
        // function preventEnterOverBudgetDiesel(){
        //     var qty = ($("#qty").val()!='')?parseFloat($("#qty").val()):0;
        //     var budgeted_diesel =($("#budgeted_diesel").val()!='')?parseFloat($("#budgeted_diesel").val()):0;
        //     if(qty>budgeted_diesel){
        //         $("#saveData").prop("disabled",true);
        //         alert('invalid quantity');
        //         $("#qty").val('');
        //         $("#qty").focus();
        //     }else{
        //         $("#saveData").prop("disabled",false);
        //     }
        // }
    
        // function preventEnterOverBudgetSEZ(){
        //     var qty = ($("#qty").val()!='')?parseFloat($("#qty").val()):0;
        //     var budgeted_sez =($("#budgeted_sez").val()!='')?parseFloat($("#budgeted_sez").val()):0;
        //     if(qty>budgeted_sez){
        //         $("#saveData").prop("disabled",true);
        //         alert('invalid amount');
        //         $("#qty").val('');
        //         $("#qty").focus();
        //     }else{
        //         $("#saveData").prop("disabled",false);
        //     }
        // }
    
        function printTrip(id){
           var url='{{route('transport.trip.voucher.print')}}?id='+id;
           // window.location.href=$str;
           $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
        }
    
        function printDiesel(id){
           var url='{{route('transport.diesel.voucher.print')}}?id='+id;
           // window.location.href=$str;
           $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
        }
    </script>
    @endsection