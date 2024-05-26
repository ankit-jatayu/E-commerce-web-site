@extends('layouts.app')
@section('title','Authorised Trip Vouchers')
@section('content')
<div class="pcoded-content">

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card" style="margin-bottom: 0px;">  
                        <div class="card-header" >
                            <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <h4 >{{$title}}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                       <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" 
                                                data-target="#filterTableModal" title="click here to filter" >
                                            <i class="feather icon-filter"></i>
                                        </button>
                                        <button class="btn btn-success btn-sm float-right ml-1" id="btn_checked"><i class="fa fa-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color: white">
                                    
                                </div>
                        <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="dt-ajax-array" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                            <th><input id='check_all' type='checkbox' value=""></th>
                                            <th>Date</th>
                                            <th>Party Name</th>
                                            <th>Job No.</th>
                                            <th>Lr No.</th>
                                            <th>Vehicle No.</th>
                                            <th>Cont. No./Wght</th>
                                            <th>Route</th>
                                            <th>Vcr No</th>
                                            <th>Exp Type</th>
                                            <th>Voucher Amount</th>
                                            <th>Voucher Remarks</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Party Name</th>
                                            <th>Job No.</th>
                                            <th>Lr No.</th>
                                            <th>Vehicle No.</th>
                                            <th>Cont. No./Wght</th>
                                            <th>Route</th>
                                            <th>Vcr No</th>
                                            <th>Exp Type</th>
                                            <th>Voucher Amount</th>
                                            <th>Voucher Remarks</th>
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
{{-- filter modal --}}
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
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no}}</option>       
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
                    <label for="job_no">JOB No</label>
                    <input type="text" class="form-control" id="job_no" value="">
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

                <div class="form-group col-lg-6 col-md-3">
                    <label for="payment_type_id">Payment type</label>
                    <select name="payment_type_id" id="payment_type_id" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE PAYMENT TYPE</option>       
                        @if(!empty($paymentTypes))
                        @foreach($paymentTypes as $k =>$payment_type)   
                        <option value="{{$payment_type->id}}">{{$payment_type->name}}</option>       
                        @endforeach
                        @endif
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
            "ajax": {
                url: "{{route('list.authorisedby.trip.vouchers')}}",
                type: "GET",
                data: function(d) {
                    //d._token = '{{csrf_token()}}';
                    d.voucher_no = $('#voucher_no').val();
                    d.lr_no = $('#lr_no').val();
                    d.job_no = $('#job_no').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.authorised_by = $('#authorised_by').val();
                    d.payment_type_id = $('#payment_type_id').val();
                    d.is_party_advance = $('#is_party_advance').val();
                    d.branch = $('#branch').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
                        { "data": "action",orderable: false, searchable: false },
                        { "data": "voucher_date" },
                        { "data": "party_name" },
                        { "data": "job_no" },
                        { "data": "lr_no" },
                        { "data": "vehicle_no" },
                        { "data": "container_no_or_weight" },
                        { "data": "route_name" },
                        { "data": "voucher_no" },
                        { "data": "exp_type" },
                        { "data": "total_amount" },
                        { "data": "remarks" },
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

        $("#check_all").change(function() {
            if ($(this).is(':checked') == true) {
                $(".check_single").each(function() {
                    $(this).prop('checked', true);
                });
            }else{
                $(".check_single").each(function() {
                    $(this).prop('checked', false);
                });
            };
        });

        $("#btn_checked").click(function() {
            var checked = "";
            $(".check_single").each(function() {
                if ($(this).is(":checked")) {
                    checked += ","+$(this).val();
                }
            });
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.authorise.trip.vouchers') }}',
                data: {"_token": "{{ csrf_token()}}",
                       'id': checked,
                      },
                dataType:'json',
                success: function (responseData) {
                   table.ajax.reload();
                   $('#check_all').prop('checked', false); 
                   notify('top', 'center', 'fa fa-check', 'success', '', '','TRIP VOUCHER AUTHORIZED SUCCESSFULLY');
                }
            }); 
           
        });

        // $('.export').click(function refreshData() {
        //     var param={
        //         voucher_no : $('#voucher_no').val(),
        //         lr_no : $('#lr_no').val(),
        //         job_no : $('#job_no').val(),
        //         vehicle_id : $('#vehicle_id').val(),
        //         authorised_by : $('#authorised_by').val(),
        //         payment_type_id : $('#payment_type_id').val(),
        //         is_party_advance : $('#is_party_advance').val(),
        //         branch : $('#branch').val(),
        //         from_date : $('#from_date').val(),
        //         to_date : $('#to_date').val(),
        //     };

        //     var url='{{route('transport.trip.voucher.export')}}?data='+JSON.stringify(param);
        //     window.location.href=url; 
        // });

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

    // function changeStatus(id,e){
	// 	  	//e.preventDefault();
    //         let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
    //         if(confirm("Are you sure you want to change the status?")){
    //          $.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: '{{ route('update.status.location') }}',
    //             data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
    //             success: function (data) {

    //             }
    //         });
    //      }else{
    //          return false;
    //      }
    // }

    // function printTrip(id){
    //    var url='{{route('transport.trip.voucher.print')}}?id='+id;
    //    // window.location.href=$str;
    //    $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    // }

    // function printDiesel(id){
    //    var url='{{route('transport.diesel.voucher.print')}}?id='+id;
    //    // window.location.href=$str;
    //    $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    // }

    // function deleteRecord(id){
    //   if(confirm('are you sure want to delete this record?')){
    //     url='{{route('delete.transport.trip.voucher')}}';
    //     window.location.href=url+'?id='+id;
    //   }
    // }

 </script>
 @endsection