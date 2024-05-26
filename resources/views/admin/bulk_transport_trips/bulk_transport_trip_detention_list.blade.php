@extends('layouts.app')
@section('title','Trips Detention List ')

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
                                        <button class="btn btn-success btn-sm float-right ml-1" id="btn_checked">Remarks</button>
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
                                            <th>LR No.</th>
                                            <th>Job No</th>
                                            <th>LR Date</th>
                                            <th>Vehicle</th>
                                            <th>Request Party</th>
                                            <th>Estimated time</th>
                                            <th>Detention</th>
                                            <th>Detention Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>LR No.</th>
                                            <th>Job No</th>
                                            <th>LR Date</th>
                                            <th>Vehicle</th>
                                            <th>Request Party</th>
                                            <th>Estimated time</th>
                                            <th>Detention</th>
                                            <th>Detention Remarks</th>
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

<div class="modal fade" id="LR-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DETENTION REMARKS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="trip_ids">
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" style="font-weight:bold">Remarks</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="detention_remarks">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light updateTrip">Save</button>
                
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
                    <label for="party_id">Party</label>
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
                    <label for="route_id">Route</label>
                    <select name="route_id" id="route_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE ROUTE</option>       
                        @if(!empty($routes))
                        @foreach($routes as $k =>$route)   
                        <option value="{{$route->id}}">{{$route->from_place.'-'.$route->to_place.'-'.$route->back_place}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label for="vehicle_id">Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE VEHICLE</option>       
                        @if(!empty($vehicles))
                        @foreach($vehicles as $k =>$vehicle)   
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no}}</option>       
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
                    <select name="movement_type" id="movement_type" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE MOVEMENT</option>   
                        <option value="Export">Export</option>   
                        <option value="Import">Import</option>   
                        <option value="Domestic">Domestic</option>   
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="is_market_lr">Market Trip</label>
                    <select name="is_market_lr" id="is_market_lr" class="form-control select2" style="width:100%;">
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
                url: "{{route('transport.trip.detention.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.route_id = $('#route_id').val();
                    d.job_no = $('#job_no').val();
                    d.lr_no = $('#lr_no').val();
                    d.movement_type = $('#movement_type').val();
                    d.dropdate_missing = $('#dropdate_missing').val();
                    d.is_market_lr = $('#is_market_lr').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
            { "data": "lr_no" },
            { "data": "job_no" },
            { "data": "lr_date" },
            { "data": "vehicle_no" },
            { "data": "party_name" },
            { "data": "estimeted_time" },
            { "data": "hour_diff" },            
            { "data": "detention_remarks" },
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
                party_id : $('#party_id').val(),
                vehicle_id : $('#vehicle_id').val(),
                route_id : $('#route_id').val(),
                job_no : $('#job_no').val(),
                lr_no : $('#lr_no').val(),
                movement_type : $('#movement_type').val(),
                dropdate_missing : $('#dropdate_missing').val(),
                is_market_lr : $('#is_market_lr').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('transport.trip.export')}}?data='+JSON.stringify(param);
            window.location.href=url; 
        });

        $("#check_all").change(function() {
            if ($(this).is(':checked') == true) {
                $(".check_lr").each(function() {
                    $(this).prop('checked', true);
                });
            }else{
                $(".check_lr").each(function() {
                    $(this).prop('checked', false);
                });
            };
        });

        $("#btn_checked").click(function() {
            var checked = "";
            $(".check_lr").each(function() {
                if ($(this).is(":checked")) {
                    checked += ","+$(this).val();
                }
            });
            
            $('#trip_ids').val(checked);
            $("#LR-Modal").modal('toggle');
        });

        $('.updateTrip').click(function refreshData() {
            var trip_ids = $('#trip_ids').val();
            var detention_remarks = $('#detention_remarks').val();
            
            $.ajax({
                    url: '{{ route('update.transport.trip.detention') }}',
                    data: {'trip_ids': trip_ids,'type':'transporter_detention' ,'detention_remarks':detention_remarks, "_token": "{{ csrf_token() }}"},
                    type: 'POST',
                    dataType:'json',
                    'success':function(data){
                        $('#dt-ajax-array').DataTable().ajax.reload();
                        $('#LR-Modal').modal('toggle');
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
 </script>
 @endsection