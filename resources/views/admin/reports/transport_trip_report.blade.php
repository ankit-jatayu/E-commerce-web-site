@extends('layouts.app')
@section('title','Trips')
@section('content')
<style type="text/css">
    td:hover {
        background-color: #ed9923;
        color: black;
    }
    .yellowClass{
        background-color: #ffb64d !important; 
    }
    .greenClass{
        background-color: #2ed8b6 !important; 
    }
    .redClass{
        background-color: #ff5370 !important; 
    }
    .infoClass{
        background-color: #D2B4DE !important; 
    }
    .blueClass{
        background-color: #00bcd4 !important; 
    }
    #dt-ajax-array_filter{
        display: none;
    }
   
</style>
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
                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>
                                        
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
                                            <th>Sr. No.</th>
                                            <th>Date</th>
                                            <th>Party</th>
                                            <th>Order Type</th>
                                            <th>Route</th>
                                            
                                            <th>Transporter</th>
                                            <th>Vehicle No</th>
                                            <th>Factory LR</th>
                                            <th>Market Rate</th>
                                            <th>Factory Rate</th>
                                            <th>Commission</th>
                                            <th>Net Weight</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Sr. No.</th>
                                            <th>Date</th>
                                            <th>Party</th>
                                            <th>Order Type</th>
                                            <th>Route</th>
                                            <th>Transporter</th>
                                            <th>Vehicle No</th>
                                            <th>Factory LR</th>
                                            <th>Market Rate</th>
                                            <th>Factory Rate</th>
                                            <th>Commission</th>
                                            <th>Net Weight</th>
                                            <th>Amount</th>
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

<div class="modal fade" id="LR-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">LR No</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="trip_id">
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" style="font-weight:bold">Pickup Date</label>
                    <div class="col-sm-4">
                        <input type="datetime-local" class="form-control" id="pickup_date_time">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" style="font-weight:bold">Drop Date</label>
                    <div class="col-sm-4">
                        <input type="datetime-local" class="form-control" id="drop_date_time" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light updateVoucher">Save</button>
                
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
                    <label>Billing Party</label>
                    <select name="party_id" id="party_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE PARTY</option>       
                        @if(!empty($billingParties))
                        @foreach($billingParties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Transporter</label>
                    <select name="transporter_id" id="transporter_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE TRANSPORTER</option>       
                        @if(!empty($TransporterParties))
                        @foreach($TransporterParties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Route</label>
                    <select name="route_id" id="route_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE ROUTE</option>       
                        @if(!empty($Routes))
                        @foreach($Routes as $k =>$row)   
                        <option value="{{$row->id}}">
                            <?php 
                                    $RouteName=(isset($row))?$row->from_place.'-'.$row->destination_1:'';
                                    $RouteName.=(isset($row) && $row->destination_2!='')?'-'.$row->destination_2:'';
                                    $RouteName.=(isset($row) && $row->destination_3!='')?'-'.$row->destination_3:'';
                                    echo $RouteName;
                            ?>
                        </option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE VEHICLE</option>       
                        @if(!empty($Vehicles))
                        @foreach($Vehicles as $k =>$vehicle)
                        @if($vehicle->type == 'market')
                        <?php  $transporter=(isset($vehicle->getTransporter->name))?$vehicle->getTransporter->name:''?>
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no .' / '.$transporter }}</option>  
                        @else
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no}}</option>  
                        @endif

                        @endforeach
                        @endif
                    </select>
                </div>  
                <div class="form-group col-lg-6 col-md-3">
                    <label>Order Type</label>
                    <select name="order_type" id="order_type" class="form-control select2" style="width:100%">
                        <option value="">CHOOSE ORDER TYPE</option>
                        <option value="Party Order">Party Order</option>
                        <option value="Factory Order">Factory Order</option>
                    </select>
                </div>
                
                <div class="form-group col-lg-6 col-md-3">
                    <label>LR No</label>
                    <input type="text" class="form-control" id="lr_no" value="">
                </div>
              
                <div class="form-group col-lg-6 col-md-3">
                    <label>From LR date</label>
                    <input type="date" class="form-control" id="from_date" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>To LR date</label>
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
  </div> {{-- modal complete  --}}

<script>
    $(document).ready(function () {
        $('.select2').select2();
        @if(Session::get('success'))
        notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif
        
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            //"pageLength": 100,
            "paging": false,
            "scrollX":true,
            "ajax": {
                url: "{{route('report.trip.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.transporter_id = $('#transporter_id').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.route_id = $('#route_id').val();
                    d.order_type = $('#order_type').val();
                    d.lr_no = $('#lr_no').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
            { "data": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "lr_date" },
            { "data": "party_name" },
            { "data": "order_type" },
            { "data": "route_name" },
            { "data": "transporter_name" },
            { "data": "vehicle_no" },
            { "data": "factory_lr" },
            { "data": "market_rate" },
            { "data": "billing_rate" },
            { "data": "commission" },
            { "data": "net_weight" },
            { "data": "net_fright" },
            ],
            "createdRow": function (row, data, index) {
			         // find checkboxes here
			        // init switch here

			        // may be something like this (again, not tested)
                
			        if(data.lr_status == '0'){
                        $(row).addClass('redClass');
                    }

			  //       var elemprimary = document.querySelector('.js-warning');
					// var switchery = new Switchery(elemprimary, { color: '#FFB64D', jackColor: '#fff' });
            },
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
                total_net_amt=0;
                if(data.length>0){
                  data.forEach((row,k)=>{
                    total_net_amt +=(parseFloat(row['net_fright']));
                  })
                }
                
                $(api.column(12).footer()).html(total_net_amt.toFixed(2));
            },
        });

        $('.filter').click(function refreshData() {

            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            location.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                party_id : $('#party_id').val(),
                vehicle_id : $('#vehicle_id').val(),
                route_id : $('#route_id').val(),
                job_no : $('#job_no').val(),
                lr_no : $('#lr_no').val(),
                movement_type : $('#movement_type').val(),
                dropdate_missing : $('#dropdate_missing').val(),
                order_type : $('#order_type').val(),
                market_freight_authorised_by : $('#market_freight_authorised_by').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('report.trip.export')}}?data='+JSON.stringify(param);
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

    function printLr(id){
       var url='{{route('transport.trip.print')}}?id='+id;
       // window.location.href=$str;
       $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    }

    function getTrip(id){
        $(".modal-title").html("LR NO : "+$("#pdt"+id).attr('lrno'));
        //$("#setdt_titl").attr('idup',id);
        
        $("#trip_id").val($("#pdt"+id).attr('lrno'));
        $("#pickup_date_time").val($("#pdt"+id).attr('pickup'));
        $("#drop_date_time").val($("#pdt"+id).attr('drop'));
        
        $("#LR-Modal").modal('toggle');
    }

    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('delete.transport.trip')}}';
        window.location.href=url+'?id='+id;
      }
    }

     // $('.updateVoucher').click(function refreshData() {
    //     var trip_id = $('#trip_id').val();
    //     var pickup_date_time = $('#pickup_date_time').val();
    //     var drop_date_time = $('#drop_date_time').val();
        
    //     $.ajax({
    //             url: '',
    //             data: {'trip_id': trip_id,'pickup_date_time':pickup_date_time,'drop_date_time':drop_date_time, "_token": "{{ csrf_token() }}"},
    //             type: 'POST',
    //             dataType:'json',
    //             'success':function(data){
    //                 $('#dt-ajax-array').DataTable().ajax.reload();
    //                 $('#LR-Modal').modal('toggle');
    //             }
    //         });
    // });
    
 </script>
 @endsection