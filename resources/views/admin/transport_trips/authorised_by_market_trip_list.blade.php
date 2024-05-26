@extends('layouts.app')
@section('title','Authorised By Market Trips')
@section('content')
<style type="text/css">
    td:hover {
        background-color: #ed9923;
        color: black;
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
                                        <h4>{{$title}}</h4>
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
                                            <th>LR Date</th>
                                            <th>Party</th>
                                            <th>Job No</th>
                                            <th>LR No.</th>
                                            <th>Vehicle No</th>
                                            <th>Cont. No/Weight</th>
                                            <th>Size</th>
                                            <th>Route</th>
                                            <th>Our Rate(After Party commision)</th>
                                            <th>Market Booking Rate</th>
                                            <th>Transporter</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>#</th>
                                            <th>LR Date</th>
                                            <th>Party</th>
                                            <th>Job No</th>
                                            <th>LR No.</th>
                                            <th>Vehicle No</th>
                                            <th>Cont. No/Weight</th>
                                            <th>Size</th>
                                            <th>Route</th>
                                            <th>Our Rate(After Party commision)</th>
                                            <th>Market Booking Rate</th>
                                            <th>Transporter</th>
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

{{-- <div class="modal fade" id="LR-Modal" tabindex="-1" role="dialog">
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
</div> --}}

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
                    <label for="party_id">Party</label>
                    <select name="party_id" id="party_id" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE PARTY</option>       
                        @if(!empty($parties))
                        @foreach($parties as $k =>$party)   
                        <option value="{{$party['id']}}">{{$party['name']}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label for="route_id">Route</label>
                    <select name="route_id" id="route_id" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE ROUTE</option>       
                        @if(!empty($routes))
                        @foreach($routes as $k =>$route)   
                        <option value="{{$route['id']}}">{{$route['from_place'].'-'.$route['to_place'].'-'.$route['back_place']}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label for="vehicle_id">Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE VEHICLE</option>       
                        @if(!empty($vehicles))
                        @foreach($vehicles as $k =>$vehicle)
                        @if($vehicle->type == 'market')
                        <?php 
                        $transporter=(isset($vehicle->getTransporter) && $vehicle->getTransporter->name!=null)?$vehicle->getTransporter->name:'';
                        ?>
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no .' / '.$transporter}}</option>  
                        @else
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no}}</option>  
                        @endif

                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label for="job_no">Job No</label>
                    <input type="text" class="form-control" id="job_no" value="">
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="lr_no">LR No</label>
                    <input type="text" class="form-control" id="lr_no" value="">
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="movement_type">Movement Nature</label>
                    <select name="movement_type" id="movement_type" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE MOVEMENT</option>   
                        <option value="Export">Export</option>   
                        <option value="Import">Import</option>   
                        <option value="Domestic">Domestic</option>   
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="dropdate_missing">Dropdate Missing</label>
                    <select name="dropdate_missing" id="dropdate_missing" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE OPTION</option>   
                        <option value="1">Yes</option>   
                        <option value="2">No</option>   

                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label for="is_market_lr">Market Trip</label>
                    <select name="is_market_lr" id="is_market_lr" class="form-control select2" style="width: 100%;">
                        <option value="">CHOOSE OPTION</option>   
                        <option value="1">Yes</option>   
                        <option value="0">No</option>   

                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="from_date">From LR date</label>
                    <input type="date" class="form-control" id="from_date" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="to_date">To LR date</label>
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
                url: "{{route('list.authorisedby.market.trips')}}",
                type: "GET",
                data: function(d) {
                   // d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.route_id = $('#route_id').val();
                    d.job_no = $('#job_no').val();
                    d.lr_no = $('#lr_no').val();
                    d.movement_type = $('#movement_type').val();
                    d.dropdate_missing = $('#dropdate_missing').val();
                    d.is_market_lr = $('#is_market_lr').val();
                    d.market_freight_authorised_by = $('#market_freight_authorised_by').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
            { "data": "action" ,orderable: false, searchable: false },
            { "data": "lr_date" },
            { "data": "party_name" },
            { "data": "job_no" },
            { "data": "lr_no" },
            { "data": "vehicle_no" },
            { "data": "container_no_or_wght" },
            { "data": "size" },
            { "data": "route_name" },
            { "data": "our_rate" },
            { "data": "market_booking_rate" },
            { "data": "transporter" },
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

        // $('.export').click(function refreshData() {
        //     var param={
        //         party_id : $('#party_id').val(),
        //         vehicle_id : $('#vehicle_id').val(),
        //         route_id : $('#route_id').val(),
        //         job_no : $('#job_no').val(),
        //         lr_no : $('#lr_no').val(),
        //         movement_type : $('#movement_type').val(),
        //         dropdate_missing : $('#dropdate_missing').val(),
        //         is_market_lr : $('#is_market_lr').val(),
        //         market_freight_authorised_by : $('#market_freight_authorised_by').val(),
        //         from_date : $('#from_date').val(),
        //         to_date : $('#to_date').val(),
        //     };

        //     var url='{{route('transport.trip.export')}}?data='+JSON.stringify(param);
        //     window.location.href=url; 
        // });

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
                url: '{{ route('update.authorise.market.trips') }}',
                data: {"_token": "{{ csrf_token()}}",
                       'id': checked,
                      },
                dataType:'json',
                success: function (responseData) {
                   table.ajax.reload();
                   $('#check_all').prop('checked', false); 
                   notify('top', 'center', 'fa fa-check', 'success', '', '','TRANSPORT TRIP AUTHORIZED SUCCESSFULLY');
                }
            }); 
           
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

    // function changeStatus(id,e){
    //         //e.preventDefault();
    //     let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
    //     if(confirm("Are you sure you want to change the status?")){
    //         $.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: '{{ route('update.status.location') }}',
    //             data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
    //             success: function (data) {

    //             }
    //         });
    //     }else{
    //         return false;
    //     }
    // }

    // function printLr(id){
    //    var url='{{route('transport.trip.print')}}?id='+id;
    //    // window.location.href=$str;
    //    $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    // }

    // function getTrip(id){
    //     $(".modal-title").html("LR NO : "+$("#pdt"+id).attr('lrno'));
    //     //$("#setdt_titl").attr('idup',id);
        
    //     $("#trip_id").val($("#pdt"+id).attr('lrno'));
    //     $("#pickup_date_time").val($("#pdt"+id).attr('pickup'));
    //     $("#drop_date_time").val($("#pdt"+id).attr('drop'));
        
    //     $("#LR-Modal").modal('toggle');
    // }

    // $('.updateVoucher').click(function refreshData() {
    //     var trip_id = $('#trip_id').val();
    //     var pickup_date_time = $('#pickup_date_time').val();
    //     var drop_date_time = $('#drop_date_time').val();
        
    //     $.ajax({
    //             url: '{{ route('update.transport.trip.drop.date') }}',
    //             data: {'trip_id': trip_id,'pickup_date_time':pickup_date_time,'drop_date_time':drop_date_time, "_token": "{{ csrf_token() }}"},
    //             type: 'POST',
    //             dataType:'json',
    //             'success':function(data){
    //                 $('#dt-ajax-array').DataTable().ajax.reload();
    //                 $('#LR-Modal').modal('toggle');
    //             }
    //         });
    // });

    // function deleteRecord(id){
    //   if(confirm('are you sure want to delete this record?')){
    //     url='{{route('delete.transport.trip')}}';
    //     window.location.href=url+'?id='+id;
    //   }
    // }
    
 </script>
 @endsection