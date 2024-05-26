<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$TripVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==6;})->first();
?>
@extends('layouts.app')
@section('title','Trip Vouchers')

@section('content')

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
                                    <div class="page-header-breadcrumb float-right">
                                        @if(isset($TripVchModuleRights) && $TripVchModuleRights->is_create==1)

                                        <a href="{{route('transport.trip.voucher.add')}}" class="btn btn-sm btn-primary ml-1">
                                                <i class="fa fa-plus-circle"></i>Add New 
                                        </a>
                                        @endif
                                         <button type="button" class="btn btn-info btn-sm ml-1" 
                                                 data-toggle="modal" data-target="#filterTableModal" 
                                                 title="click here to filter" >
                                                <i class="feather icon-filter"></i> Filter
                                        </button>

                                        @if(isset($TripVchModuleRights) && $TripVchModuleRights->is_export==1)

                                        <button type="button" 
                                            class="btn btn-warning export btn-sm ml-1">
                                            <i class="icofont icofont-file-spreadsheet"></i>Export
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                </div>
                        <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="dt-ajax-array" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>VCH No.</th>
                                            <th>VCH Date</th>
                                            <th>LR No.</th>
                                            <th>Vehicle</th>
                                            <th>Payment Mode</th>
                                            <th>Payment Type</th>
                                            <th>Fuel Station</th>
                                            <th>Fuel Qty</th>
                                            <th>Fuel Rate</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>VCH No.</th>
                                            <th>VCH Date</th>
                                            <th>LR No.</th>
                                            <th>Vehicle</th>
                                            <th>Payment Mode</th>
                                            <th>Payment Type</th>
                                            <th>Fuel Station</th>
                                            <th>Fuel Qty</th>
                                            <th>Fuel Rate</th>
                                            <th>Amount</th>
                                            <th>Action</th>
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

<div class="modal fade filterTableModal" id="filterTableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
           Filter Records 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="feather icon-x-circle"></i></button>
        </div>
        <div class="modal-body">
            <div class="row">
               <div class="form-group col-lg-6 col-md-3">
                <label for="vehicle_id">Vehicle</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE VEHICLE</option>       
                    @if(!empty($vehicles))
                    @foreach($vehicles as $k =>$vehicle)   
                    <option value="{{$vehicle['id']}}">{{$vehicle['registration_no']}}</option>       
                    @endforeach
                    @endif
                </select>
            </div>  

            <div class="form-group col-lg-6 col-md-3">
                <label for="voucher_no">Voucher No</label>
                <input type="text" class="form-control" id="voucher_no" value="">
            </div>

            <div class="form-group col-lg-6 col-md-3">
                <label for="lr_no">LR No</label>
                <input type="text" class="form-control" id="lr_no" value="">
            </div>

            <div class="form-group col-lg-6 col-md-3">
                <label for="is_party_advance">Party Advance</label>
                <select name="is_party_advance" id="is_party_advance" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE</option>   
                    <option value="1">YES</option>   
                    <option value="0">NO</option>   
                </select>
            </div>

            <div class="form-group col-lg-6 col-md-3">
                <label for="branch">Branch</label>
                <select name="branch" id="branch" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE BRANCH</option>   
                    <option value="Mundra">Mundra</option>   
                    <option value="Gandhidham">Gandhidham</option>   
                </select>
            </div>

           {{--  <div class="form-group col-lg-6 col-md-3">
                <label for="authorised_by">Authorised by</label>
                <select name="authorised_by" id="authorised_by" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE USER</option>       
                    @if(!empty($users))
                    @foreach($users as $k =>$vehicle)   
                    <option value="{{$vehicle['id']}}">{{$vehicle['name']}}</option>       
                    @endforeach
                    @endif
                </select>
            </div>   --}}

            <div class="form-group col-lg-6 col-md-3">
                <label for="payment_type_id">Payment type</label>
                <select name="payment_type_id" id="payment_type_id" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE PAYMENT TYPE</option>       
                    @if(!empty($paymentTypes))
                    @foreach($paymentTypes as $k =>$payment_type)   
                    <option value="{{$payment_type['id']}}">{{$payment_type['name']}}</option>       
                    @endforeach
                    @endif
                </select>
            </div>

            {{-- <div class="form-group col-lg-6 col-md-3">
                <label for="fuel_party_id">Fuel Station</label>
                <select name="fuel_party_id" id="fuel_party_id" class="form-control select2" style="width: 100%;">
                    <option value="" >CHOOSE FUEL TYPE</option>
                    @if(!empty($fuels))
                    @foreach($fuels as $k =>$singledata)   
                    <option value="{{$singledata['id']}}">{{$singledata['name']}}</option>
                    @endforeach
                    @endif
                </select>
            </div> --}}

            <div class="form-group col-lg-6 col-md-3">
                <label for="payment_by">Payment By</label>
                <select name="payment_by" id="payment_by" class="form-control select2" style="width: 100%;">
                    <option value="">CHOOSE PAYMENT BY</option>   
                    <option value="Card">Card</option>   
                    <option value="Cash">Cash</option>   
                </select>
            </div>
            <div class="form-group col-lg-6 col-md-3">
                <label for="from_date">From vch date</label>
                <input type="date" class="form-control" id="from_date" value="">
            </div>
            <div class="form-group col-lg-6 col-md-3">
                <label for="to_date">To vch date</label>
                <input type="date" class="form-control" id="to_date" value="">
            </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-3" style="margin-top: 27px;">
                    <button type="button" class="btn btn-primary filter " data-dismiss="modal" aria-label="Close" >
                        <i class="icofont icofont-search"></i> Search 
                    </button>
                    <button type="button" class="btn btn-danger clear" data-dismiss="modal" aria-label="Close" >
                        <i class="feather icon-x-circle"></i> Clear
                    </button>
                </div>
            </div>          
        </div>
      </div>
    </div>
  </div> {{-- filter modal close --}}

<script>
    $(document).ready(function () {
        $('.select2').select2();
        @if(Session::get('success'))
        notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif
        
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX":true,
            "ajax": {
                url: "{{route('transport.trip.voucher.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.voucher_no = $('#voucher_no').val();
                    d.lr_no = $('#lr_no').val();
                    d.job_no = $('#job_no').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.authorised_by = $('#authorised_by').val();
                    d.payment_type_id = $('#payment_type_id').val();
                    d.payment_by = $('#payment_by').val();
                    d.fuel_party_id = $('#fuel_party_id').val();
                    d.is_party_advance = $('#is_party_advance').val();
                    d.branch = $('#branch').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
                        { "data": "voucher_no" },
                        { "data": "voucher_date" },
                        { "data": "lr_no" },
                        { "data": "vehicle_no" },
                        { "data": "payment_mode" },
                        { "data": "payment_type" },
                        { "data": "fuel_station_id" },
                        { "data": "fuel_qty" },
                        { "data": "fuel_rate" },
                        { "data": "amount" },
                        { "data": "action" }
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
            location.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                voucher_no : $('#voucher_no').val(),
                lr_no : $('#lr_no').val(),
                job_no : $('#job_no').val(),
                vehicle_id : $('#vehicle_id').val(),
                authorised_by : $('#authorised_by').val(),
                payment_type_id : $('#payment_type_id').val(),
                payment_by : $('#payment_by').val(),
                fuel_party_id : $('#fuel_party_id').val(),
                is_party_advance : $('#is_party_advance').val(),
                branch : $('#branch').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('transport.trip.voucher.export')}}?data='+JSON.stringify(param);
            window.location.href=url; 
        });

        function notify(from, align, icon, type, animIn, animOut,msg){
            $.growl({
                icon: icon,
                title: msg,
                message: '',
                url: ''
            },{
                element: 'body',
                type: type,
                allow_dismiss: true,
                placement: {
                    from: from,
                    align: align
                },
                offset: {
                    x: 60,
                    y: 300
                },
                spacing: 10,
                z_index: 999999,
                delay: 2500,
                timer: 2000,
                url_target: '_blank',
                mouse_over: false,
                animate: {
                    enter: animIn,
                    exit: animOut
                },
                icon_type: 'class',
                template: '<div data-growl="container" class="alert" role="alert">' +
                '<button type="button" class="close" data-growl="dismiss">' +
                '<span aria-hidden="true">&times;</span>' +
                '<span class="sr-only">Close</span>' +
                '</button>' +
                '<span data-growl="icon"></span>' +
                '<span data-growl="title"></span>' +
                '<span data-growl="message"></span>' +
                '<a href="#" data-growl="url"></a>' +
                '</div>'
            });
        };
    });

    function changeStatus(id,e){
		  	//e.preventDefault();
            let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
            if(confirm("Are you sure you want to change the status?")){
             $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.status.location') }}',
                data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
                success: function (data) {

                }
            });
         }else{
             return false;
         }
    }

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

    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('delete.transport.trip.voucher')}}';
        window.location.href=url+'?id='+id;
      }
    }

 </script>
 @endsection