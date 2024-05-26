@extends('layouts.app')
@section('title',(isset($editData)) ?'Edit Salary Voucher':'Add Salary Voucher')

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
                                                <a href="{{route('salary.voucher.list')}}"><h4>{{$title}}</h4></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                               {{--  @if(isset($editData['id']))
                                                    <a class="btn waves-effect waves-light btn-warning float-right ml-1" onclick="printLr({{$editData['id']}})"><i class="feather icon-printer" style="color: white;"></i></a>
                                                    <a href="{{route('transport.trip.add')}}" class="btn waves-effect waves-light btn-primary float-right "><i class="icofont icofont-plus"></i>Add New </a>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('salary.voucher.update',base64_encode($editData->id)) : route('salary.voucher.store')}}" novalidate="" enctype="multipart/form-data">
                                        <?php
                                         $selectedDriverID=((isset($editData->driver_id))?$editData->driver_id:0);
                                         $selectedDriverName=((isset($editData->getDriverDetail))?$editData->getDriverDetail->name:'');
                                         $selectedDriverTotalCredit=((isset($editData->getDriverDetail))?$editData->getDriverDetail->total_credit:0);
                                         
                                         $selectedVehicleNo=(isset($editData->getVehicleDetail))?$editData->getVehicleDetail->registration_no:'';
                                        ?>
                                        @csrf
                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label>Branch</label>
                                                <select name="branch" id="branch" class="form-control select2" required>
                                                    <option value="Mundra" {{('Mundra'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Mundra</option>
                                                    <option value="Gandhidham" {{('Gandhidham'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Gandhidham</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="salary_voucher_from_date">From date</label>
                                                <input type="date" class="form-control" name="salary_voucher_from_date" id="salary_voucher_from_date" 
                                                       value="{{(isset($salary_voucher_from_date) && $salary_voucher_from_date!=null )?$salary_voucher_from_date:date('Y-m-01')}}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="salary_voucher_to_date">To date</label>
                                                <input type="date" class="form-control" name="salary_voucher_to_date" id="salary_voucher_to_date" 
                                                        value="{{(isset($salary_voucher_to_date) && $salary_voucher_to_date!=null )?$salary_voucher_to_date:date('Y-m-t')}}" onchange="getVehicleData()">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Driver</label>
                                                {{-- <select name="vehicle_id" id="vehicle_id" class="form-control select2" required onchange="getVehicleData()">
                                                <option value="" transporter_party_id ="" transporter_party_name ="" driver_id ="" driver_name ="" vehicle_type='' >CHOOSE VEHICLE</option>
                                                     @if(!empty($Vehicles))
                                                     @foreach($Vehicles as $k =>$singledata)   
                                                     <option value="{{$singledata->id}}" 
                                                        {{($singledata->id==$selectedVehicleID)?'selected':''}}
                                                        transporter_party_id="{{$singledata->transporter_party_id}}"
                                                        transporter_party_name="{{$singledata->transporter_party_name}}"
                                                        driver_id="{{$singledata->driver_id}}"
                                                        driver_name="{{$singledata->driver_name}}"
                                                     >
                                                        {{$singledata->registration_no}}
                                                      </option>
                                                     @endforeach
                                                     @endif
                                                </select> --}}
                                                <select name="driver_id" id="driver_id" class="form-control select2" required onchange="getVehicleData()">
                                                <option value="" driver_name="" vehicle_id ="" vehicle_no ="" >CHOOSE DRIVER</option>
                                                     @if(!empty($drivers))
                                                     @foreach($drivers as $k =>$singledata)   
                                                     <option value="{{$singledata->id}}" 
                                                        {{($singledata->id==$selectedDriverID)?'selected':''}}
                                                        vehicle_id="{{(isset($singledata->getLastestVehicle->getVehicleDetail))?$singledata->getLastestVehicle->getVehicleDetail->id:''}}"
                                                        vehicle_no="{{(isset($singledata->getLastestVehicle->getVehicleDetail))?$singledata->getLastestVehicle->getVehicleDetail->registration_no:''}}"
                                                        driver_name="{{$singledata->name}}"
                                                        driver_total_credit="{{$singledata->total_credit}}"
                                                     >
                                                        {{$singledata->name}}
                                                      </option>
                                                     @endforeach
                                                     @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Vehicle No</label>
                                                <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{(isset($editData->vehicle_id))?$editData->vehicle_id:''}}">
                                                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control"  value="{{(isset($selectedVehicleNo))?$selectedVehicleNo:''}}" readonly placeholder="Vehicle No">
                                            </div>
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Salary Amount</label>
                                                <input type="text" name="salary_amount" class="form-control decimal" id="salary_amount" value="{{(isset($editData->salary_amount))?$editData->salary_amount:''}}" placeholder="Salary Amount" required onchange="calcPayable()">
                                            </div>
                                             <div class="form-group col-md-2 owner_lr_block">
                                                <label>Driver Total Credit</label>
                                                <input type="text" name="driver_total_credit" id="driver_total_credit" class="form-control"  value="{{(isset($selectedDriverTotalCredit))?$selectedDriverTotalCredit:0}}" readonly >
                                            </div>
                                            
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Deduct Amount</label>
                                                <input type="text" name="deduct_amount" class="form-control decimal" id="deduct_amount" value="{{(isset($editData->deduct_amount))?$editData->deduct_amount:''}}" placeholder="Deduct Amount" onchange="validateDuductAmt();calcPayable()">
                                                <span id="error-deduct-amt" style="color:red;"></span>
                                            </div>
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Payable Amount</label>
                                                <input type="text" name="payable_amount" class="form-control decimal" id="payable_amount" value="{{(isset($editData->payable_amount))?$editData->payable_amount:''}}" placeholder="Payable Amount" readonly >
                                            </div>

                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Payment Type</label>
                                                <select name="payment_type" id="payment_type" class="form-control select2" required>
                                                    <option value="">Choose Payment Type</option>
                                                    <option value="Cash" {{'Cash'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Cash</option>
                                                    <option value="Card" {{'Card'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Card</option>
                                                    <option value="Bank" {{'Bank'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Bank</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Salary Date</label>
                                                <input type="date" name="salary_voucher_date" class="form-control" id="salary_voucher_date" value="{{(isset($editData->salary_voucher_date))?$editData->salary_voucher_date:date('Y-m-d')}}" >
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control" value="{{(isset($editData->remarks))?$editData->remarks:''}}" placeholder="Remarks">{{(isset($editData->remarks))?$editData->remarks:''}}</textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('salary.voucher.list')}}"  class="btn btn-danger">CANCEL</a>

                                    </form>
                                </div>
                            </div>
                            {{-- DRIVER ALLOCATED VEHICLE DETAIL --}}
                             <div class="card driver_allocate_vehicle_block">
                                <div class="card-header" style="background-color: white">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <h4 style="text-decoration:underline;">DRIVER ALLOCATED VEHICLE DETAIL</h4>
                                        </div>
                                    </div>

                                    <div class="form-row">

                                   {{--   <div class="form-group col-md-2">
                                        <label for="from_date">From date</label>
                                        <input type="date" class="form-control filter-input" id="from_date" value="{{date('Y-m-01')}}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="to_date">To date</label>
                                        <input type="date" class="form-control filter-input" id="to_date" value="{{date('Y-m-t')}}">
                                    </div> --}}
                                    {{-- <div class="form-group col-md-2">
                                        <label>Driver</label>
                                        <select name="driver_id" id="driver_id" class="form-control select2 filter-input-select">
                                            @if(!empty($drivers))
                                                @foreach($drivers as $k =>$singledata)   
                                                <option value="{{$singledata->id}}"> {{$singledata->name}} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> --}}
                                   {{-- <div class="form-group col-md-2">
                                        <label>Driver</label>
                                        <input type="text" name="driver_name" class="form-control driver_name"  value="{{(isset($selectedDriverName))?$selectedDriverName:''}}" readonly placeholder="Driver">
                                    </div> --}}
                                 <div class="form-group col-md-2" style="margin-top: 30px;">
                                    {{-- <button type="button" class="btn btn-primary driverAllocatedVehicleDatatableFilter "><i class="icofont icofont-search"></i></button> --}}
                                    {{-- <button type="button" class="btn btn-danger driverAllocatedVehicleDatatableClear"><i class="icofont icofont-close"></i></button> --}}
                                    {{-- <button type="button" class="btn btn-warning export"><i class="icofont icofont-file-spreadsheet"></i></button> --}}
                                </div>
                            </div>
                           </div>
                           <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="driverAllocatedVehicleDatatable" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Sr</th>
                                            <th>Vehicle</th>
                                            <th>Working Duration</th>
                                            <th>Working Days</th>
                                        </tr>
                                    </thead>
                                    <tbody id="driverAllocatedVehicleDatatableBody">
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th colspan="3"><span class="float-right">Total</span></th>
                                            <th id="total_working_days"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        </div>
                            {{-- TRIP DETAIL --}}
                            <div class="card">
                                <div class="card-header" style="background-color: white">
                                     <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <h4 style="text-decoration:underline;">DRIVER TRIP DETAIL </h4>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                    {{--  <div class="form-group col-md-2">
                                        <label for="from_date">From date</label>
                                        <input type="date" class="form-control filter-input" id="from_date" value="{{date('Y-m-01')}}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="to_date">To date</label>
                                        <input type="date" class="form-control filter-input" id="to_date" value="{{date('Y-m-t')}}">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Driver</label>
                                        <input type="text" name="driver_name" class="form-control driver_name"  value="{{(isset($selectedDriverName))?$selectedDriverName:''}}" readonly placeholder="Driver">
                                    </div> --}}
                                   
                                 <div class="form-group col-md-2" style="margin-top: 30px;">
                                    {{-- <button type="button" class="btn btn-primary filter "><i class="icofont icofont-search"></i></button>
                                    <button type="button" class="btn btn-danger clear"><i class="icofont icofont-close"></i></button> --}}
                                    <button type="button" class="btn btn-warning export"><i class="icofont icofont-file-spreadsheet"></i></button>
                                </div>
                            </div>
                           </div>
                           <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="dt-ajax-array" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Party</th>
                                            <th>LR No</th>
                                            <th>Truck No</th>
                                            <th>Container No</th>
                                            <th>Route</th>
                                            <th>Transporter Name</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Party</th>
                                            <th>LR No</th>
                                            <th>Truck No</th>
                                            <th>Container No</th>
                                            <th>Route</th>
                                            <th>Transporter Name</th>
                                        </tr>
                                    </tfoot>
                                </table>
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

    $('.number').keypress(function(event) {
        if (event.which != 46 && (event.which < 47 || event.which > 59)){
            event.preventDefault();
            if((event.which == 46) && ($(this).indexOf('.') != -1)) {
                event.preventDefault();
            }
        }
    });

    $('.decimal').keypress(function(evt){
        return (/^[0-9]*\.?[0-9]*$/).test($(this).val()+evt.key);
    });

    function preventZero($idparam){
        dPurchaseQty=$('#'+$idparam).val();
        if(dPurchaseQty==0){
          $('#'+$idparam).val('');
          return false;
        }
    }

    

   
    $(document).ready(function () {
        $(".select2").select2();

        @if(Session::get('success'))
        notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif
        
        


        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{route('slry.vchr.trans.trip.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.from_date = $('#salary_voucher_from_date').val();
                    d.to_date = $('#salary_voucher_to_date').val();
                    d.driver_id = $('#driver_id').val();
                }
            },
            
            "columns": [
            //{ "data": "action" },
            { "data": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "lr_date" },
            { "data": "party_name" },
            { "data": "lr_no" },
            { "data": "vehicle_no" },
            { "data": "container_no" },
            { "data": "route_name" },
            { "data": "transporter_name" },
            ],
            "createdRow": function (row, data, index) {
                     // find checkboxes here
                    // init switch here

                    // may be something like this (again, not tested)
                    var switchElem = Array.prototype.slice.call($(row).find('.js-warning'));
                    switchElem.forEach(function (html) {
                        
                        //var switchery = new Switchery(html, { color: '#FFB64D', secondaryColor: '#dee2e6' });
                        var switchery = new Switchery(html, { color: '#FFB64D', jackColor: '#fff' });
                    });

              //       var elemprimary = document.querySelector('.js-warning');
                    // var switchery = new Switchery(elemprimary, { color: '#FFB64D', jackColor: '#fff' });
               }
           });

        $('.filter').click(function refreshData() {

            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            $('.filter-input').val('');
            $('.filter-input-select').val('').select2();
            
            table.ajax.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                from_date : $('#salary_voucher_from_date').val(),
                to_date : $('#salary_voucher_to_date').val(),
                driver_id : $('#driver_id').val(),
            };
            
            var url='{{route('slry.vchr.trans.trip.export')}}?data='+JSON.stringify(param);
            window.location.href=url; 
        });
                
        //driverAllocatedVehicleDatatable
        driverAllocatedVehicleDatatable();
    }); //dom close
    
    function driverAllocatedVehicleDatatable(){
        $.ajax({
            type: 'POST',
            url: '{{route('slry.vchr.driver.allocated.vehicles.paginate')}}',
            data: {
                _token : '{{csrf_token()}}',
                from_date : $('#salary_voucher_from_date').val(),
                to_date : $('#salary_voucher_to_date').val(),
                driver_id : $('#driver_id').val(),
            },
            dataType:'json',
            'success':function(response){
                var html='';
                var total_working_days=0;
                if(response.length>0){
                   $.each( response, function( key, row ) {
                    total_working_days += parseFloat(row['working_days']);
                    html+='<tr>';
                        html+='<td>';
                        html+=key+1;
                        html+='</td>';
                        html+='<td>';
                        html+=row['vehicle_no'];
                        html+='</td>';
                        html+='<td>';
                        html+=row['working_duration'];
                        html+='</td>';
                        html+='<td>';
                        html+=row['working_days'];
                        html+='</td>';
                    html+='</tr>';
                   }); 
                }else{
                    html+='<tr><td colspan="4" align="center">N/A</td></tr>';
                }
                
                $("#driverAllocatedVehicleDatatableBody").html(html);
                $("#total_working_days").html(total_working_days);
            }
        });
    }

    $('.driverAllocatedVehicleDatatableFilter').click(function refreshData() {
        driverAllocatedVehicleDatatable();
    });

    $('.driverAllocatedVehicleDatatableClear').click(function refreshData() {
        $('.driver_allocate_vehicle_block .filter-input').val('');
        $('.driver_allocate_vehicle_block .filter-input-select').val('').select2();
        driverAllocatedVehicleDatatable();
    });


     // function getVehicleData() {
    //     var driver_id= $('#vehicle_id option:selected').attr("driver_id");   
    //     var driver_name= $('#vehicle_id option:selected').attr("driver_name");   
    //     // var transporter_party_id= $('#vehicle_id option:selected').attr("transporter_party_id");   
    //     // var transporter_party_name= $('#vehicle_id option:selected').attr("transporter_party_name");
    //     // var vehicle_type= $('#vehicle_id option:selected').attr("vehicle_type");
    //     // var jobtype=$("#job_type").val();
    //     // var container_size=$("#container_size").val();
    //     $("#driver_id").val(driver_id);
    //     $(".driver_name").val(driver_name);
    //     // 
    // }

    function getVehicleData() {
        var vehicle_id= $('#driver_id option:selected').attr("vehicle_id");   
        var vehicle_no= $('#driver_id option:selected').attr("vehicle_no");   
        var driver_name= $('#driver_id option:selected').attr("driver_name");   
        var driver_total_credit= $('#driver_id option:selected').attr("driver_total_credit");   
        //console.log(driver_total_credit);
        $("#vehicle_id").val(vehicle_id);
        $("#vehicle_no").val(vehicle_no);
        $("#driver_total_credit").val(driver_total_credit);
        $(".driver_name").val(driver_name);
       
        driverAllocatedVehicleDatatable();
        $('#dt-ajax-array').DataTable().ajax.reload();
    }

    function calcPayable(){
        var salary_amount=($("#salary_amount").val()!='')?parseFloat($("#salary_amount").val()):0;
        var deduct_amount=($("#deduct_amount").val()!='')?parseFloat($("#deduct_amount").val()):0;
        var payable_amt=(salary_amount-deduct_amount);
        $("#payable_amount").val(payable_amt);
    }

    function validateDuductAmt(){
        var driver_total_credit=($("#driver_total_credit").val()!='')?parseFloat($("#driver_total_credit").val()):0;
        var deduct_amount=($("#deduct_amount").val()!='')?parseFloat($("#deduct_amount").val()):0;
        if(deduct_amount>driver_total_credit){
           $("#error-deduct-amt").html('deduct amt should <= driver total credit'); 
           $("#saveData").attr('disabled',true);
        }else{
           $("#error-deduct-amt").html('');
           $("#saveData").attr('disabled',false);
        }

    }
</script>
@endsection