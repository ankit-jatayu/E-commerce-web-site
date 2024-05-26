<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$TripModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==5;})->first();
?>
@extends('layouts.app')
@section('title','Bulk Trips')
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
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i></button>
                                       @if(isset($TripModuleRights) && $TripModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        @endif
                                        @if(isset($TripModuleRights) && $TripModuleRights->is_create==1)
                                        <a href="{{route('bulk.transport.trip.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                        @endif
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
                                            <th>Action</th>
                                            <th>LR No.</th>
                                            <th>LR Date</th>
                                            <th>Party</th>
                                            <th>Vehicle</th>
                                            <th>Route</th>
                                            <th>Transporter</th>
                                            <th>Rate</th>
                                            <th>L MT</th>
                                            <th>UN MT</th>
                                            <th>Total Trip Amount</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>LR No.</th>
                                            <th>LR Date</th>
                                            <th>Party</th>
                                            <th>Vehicle</th>
                                            <th>Route</th>
                                            <th>Transporter</th>
                                            <th>Rate</th>
                                            <th>L MT</th>
                                            <th>UN MT</th>
                                            <th>Total Trip Amount</th>
                                            <th>Detail</th>
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
                    <label>Party</label>
                    <select name="party_id" id="party_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE PARTY</option>       
                        @if(!empty($parties))
                        @foreach($parties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Transporter</label>
                    <select name="transporter_id" id="transporter_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE TRANSPORTER</option>       
                        @if(!empty($transporter))
                        @foreach($transporter as $k =>$transporter)   
                        <option value="{{$transporter->id}}">{{$transporter->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Route</label>
                    <select name="route_id" id="route_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE ROUTE</option>       
                        @if(!empty($routes))
                        @foreach($routes as $k =>$route)   
                        <option value="{{$route->id}}">
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

                <div class="form-group col-lg-6 col-md-3">
                    <label>Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE VEHICLE</option>       
                        @if(!empty($vehicles))
                        @foreach($vehicles as $k =>$vehicle)
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
                    <label>LR No</label>
                    <input type="text" class="form-control" id="lr_no" value="">
                </div>

                {{-- <div class="form-group col-lg-6 col-md-3">
                    <label>Movement Nature</label>
                    <select name="movement_type" id="movement_type" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE MOVEMENT</option>   
                        <option value="Export">Export</option>   
                        <option value="Import">Import</option>   
                        <option value="Domestic">Domestic</option>   
                    </select>
                </div> --}}

                <div class="form-group col-lg-6 col-md-3">
                    <label>Dropdate Missing</label>
                    <select name="dropdate_missing" id="dropdate_missing" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE OPTION</option>   
                        <option value="1">Yes</option>   
                        <option value="2">No</option>   

                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Bill Panding</label>
                    <select name="bill_id" id="bill_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE OPTION</option>   
                        <option value="0" @selected($bill_panding == "0")>Yes</option>   
                        <option value="1">No</option>   

                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Market Trip</label>
                    <select name="is_market_lr" id="is_market_lr" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE OPTION</option>   
                        <option value="1" @selected($market_trip == "1")>Yes</option>   
                        <option value="0">No</option>   

                    </select>
                </div>
                {{-- <div class="form-group col-lg-6 col-md-3">
                    <label>Authorised By</label>
                    <select name="market_freight_authorised_by" id="market_freight_authorised_by" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE USER </option>       
                        @if(!empty($authories_list))
                        @foreach($authories_list as $k =>$user)   
                        <option value="{{$user['id']}}">{{$user['name']}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  --}}                      
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
            "ajax": {
                url: "{{route('bulk.transport.trip.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.transporter_id = $('#transporter_id').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.route_id = $('#route_id').val();
                    d.lr_no = $('#lr_no').val();
                    d.is_market_lr = $('#is_market_lr').val();
                    d.bill_id = $('#bill_id').val();
                    d.market_freight_authorised_by = $('#market_freight_authorised_by').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
            { "data": "action" },
            { "data": "lr_no" },
            { "data": "lr_date" },
            { "data": "party_name" },
            { "data": "vehicle_no" },
            { "data": "route_name" },
            { "data": "transporter_name" },
            { "data": "rate" },
            { "data": "l_mt" },
            { "data": "u_mt" },
            { "data": "freight" },
            { "data": "trip_detail" },
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
                party_id : $('#party_id').val(),
                transporter_id : $('#transporter_id').val(),
                vehicle_id : $('#vehicle_id').val(),
                route_id : $('#route_id').val(),
                job_no : $('#job_no').val(),
                lr_no : $('#lr_no').val(),
                movement_type : $('#movement_type').val(),
                dropdate_missing : $('#dropdate_missing').val(),
                is_market_lr : $('#is_market_lr').val(),
                rate : $('#rate').val(),
                l_mt : $('#l_mt').val(),
                market_freight_authorised_by : $('#market_freight_authorised_by').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('bulk.transport.trip.export')}}?data='+JSON.stringify(param);
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
       var url='{{route('bulk.transport.trip.print')}}?id='+id;
       window.location.href=url;
       //$("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    }

    function getTrip(id){
        $(".modal-title").html("LR NO : "+$("#pdt"+id).attr('lrno'));
        //$("#setdt_titl").attr('idup',id);
        
        $("#trip_id").val($("#pdt"+id).attr('lrno'));
        $("#pickup_date_time").val($("#pdt"+id).attr('pickup'));
        $("#drop_date_time").val($("#pdt"+id).attr('drop'));
        
        $("#LR-Modal").modal('toggle');
    }

    $('.updateVoucher').click(function refreshData() {
        var trip_id = $('#trip_id').val();
        var pickup_date_time = $('#pickup_date_time').val();
        var drop_date_time = $('#drop_date_time').val();
        
        $.ajax({
                url: '{{ route('update.bulk.transport.trip.drop.date') }}',
                data: {'trip_id': trip_id,'pickup_date_time':pickup_date_time,'drop_date_time':drop_date_time, "_token": "{{ csrf_token() }}"},
                type: 'POST',
                dataType:'json',
                'success':function(data){
                    $('#dt-ajax-array').DataTable().ajax.reload();
                    $('#LR-Modal').modal('toggle');
                }
            });
    });

    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('delete.bulk.transport.trip')}}';
        window.location.href=url+'?id='+id;
      }
    }
    
 </script>
 @endsection