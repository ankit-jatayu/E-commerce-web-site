@extends('layouts.app')
@section('title', 'Vehicles By Status')

@section('content')
<div class="pcoded-content">

    <?php 
        $status = base64_decode($status);
        $param = explode('_', $status);
        
        $vehicle_type = $param[0];
        $request_type = $param[1];
    ?>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card" style="margin-bottom: 0px;">  
                        <div class="card-header" >
                            <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <h4>{{$vehicle_type}} | {{$request_type}} | VEHICLES</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" >
                                            <i class="feather icon-filter"></i>
                                        </button>
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

                                <div class="card-block">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver Name</th>
                                                    @if($request_type == 'ALL')
                                                    <th>Vehicle Status</th>
                                                    @endif
                                                    @if($request_type == 'JOB' || $request_type == 'UNLOADING')
                                                    <th>Job Detail</th>
                                                    @endif
                                                    @if($request_type != 'FREE')
                                                    <th>Change Vehicle Status</th>
                                                    @endif
                                                    @if($request_type == 'REPAIR')
                                                    <th>Hours</th>
                                                    <th>Estimated Time</th>
                                                    <th>Follow up by </th>
                                                    <th>At Location</th>
                                                    <th>complain</th>
                                                    @endif

                                                    @if($request_type == 'FREE')
                                                    <th>ADD TO Repair</th>
                                                    <th>CFS LIST</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver Name</th>
                                                    @if($request_type == 'ALL')
                                                    <th>Vehicle Status</th>
                                                    @endif
                                                    @if($request_type == 'JOB' || $request_type == 'UNLOADING')
                                                    <th>Job Detail</th>
                                                    @endif
                                                    @if($request_type != 'FREE')
                                                    <th>Change Vehicle Status</th>
                                                    @endif
                                                    @if($request_type == 'REPAIR')
                                                    <th>Hours</th>
                                                    <th>Estimated Time</th>
                                                    <th>Follow up by </th>
                                                    <th>At Location</th>
                                                    <th>complain</th>
                                                    @endif
                                                    @if($request_type == 'FREE')
                                                    <th>ADD TO Repair</th>
                                                    <th>CFS LIST</th>
                                                    @endif
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

<div class="modal fade" id="addEstimatedTimeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Estimated Time</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12 form-group">
                <label>Complain</label>
                <input type='text' placeholder="Complain" id='complain' class='form-control modal-input' class="form-control">
            </div>
            <div class="col-md-12 form-group">
                <label>Estimeted time</label>
                <input type='text' placeholder="Estimeted time" id='estimeted_time' class='form-control modal-input integers-only' class="form-control">
            </div>
            <div class="col-md-12 form-group">
                <label>Followup by</label>
                <input type="hidden" id="vehicle_id" value="">
                <select id="followup_by" class="form-control select2 modal-input-select" style="width:100%">
                    <option value="">Choose Followup by</option>
                    @if(isset($users))
                        @foreach($users as $k =>$row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    @endif
                </select>
                
                {{-- <input type='text' placeholder="Followup by" id='followup_by' class='form-control modal-input' class="form-control"> --}}
            </div>
            <div class="col-md-12 form-group">
                <label>Location</label>
                <input type='text' placeholder="Location" id='location' class='form-control modal-input' class="form-control">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="addEstimatedTime(event)">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
               {{-- <div class="form-group col-lg-6 col-md-3">
                                            <label for="route_id">Route</label>
                                            <select name="route_id" id="route_id" class="form-control select2">
                                                <option value="">CHOOSE ROUTE</option>       
                                                @if(!empty($routes))
                                                    @foreach($routes as $k =>$route)   
                                                        <option value="{{$route['id']}}">{{$route['from_place'].'-'.$route['to_place'].'-'.$route['back_place']}}</option>       
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div> --}}
                                        
                                        <div class="form-group col-lg-6 col-md-3">
                                            <label for="registration_no">Vehicle No</label>
                                            <input type="text" class="form-control" id="registration_no" placeholder="registration no">
                                        </div>
                                        
                                        <div class="form-group col-lg-6 col-md-3">
                                            <label for="vehicle_status">Vehicle Status</label>
                                            <select name="vehicle_status" id="vehicle_status" class="form-control select2" style="width:100%">
                                                <option value="">CHOOSE STATUS</option>   
                                                <option value="Available">Available</option>   
                                                <option value="CFS">CFS</option>
                                                <option value="On Job">On Job</option>
                                                <option value="Unloading">Unloading</option>
                                                <option value="Repair">Repair</option>
                                            </select>
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
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                url: "{{route('data.vehicle.status','1')}}",
                data: function(d) {
                    d.status = '{{$request_type}}';
                    d.vehicle_type = '{{$vehicle_type}}';
                    d.vehicle_status = $('#vehicle_status').val();
                    d.registration_no = $('#registration_no').val();;
                    // etc
                }
            },
            "columns": [
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "gps" },
                { "data": "driver_name" , searchable: false },
                <?php if($request_type == 'ALL'){ ?>
                { "data": "vehicle_status" },
                <?php } ?>
                <?php if($request_type == 'JOB' || $request_type == 'UNLOADING'){ ?>
                { "data": "job_detail" },
                <?php } ?>
                <?php if($request_type != 'FREE'){ ?>
                { "data": "change_status" },
                <?php } ?>
                <?php if($request_type == 'REPAIR'){ ?>
                { "data": "time_diff" },
                { "data": "estimeted_time" },
                { "data": "followup_by" },
                { "data": "location" },
                { "data": "complain" },
                <?php } ?>
                <?php if($request_type == 'FREE'){ ?>
                { "data": "estimeted_time_add" },
                { "data": "cfs_list" },
                <?php } ?>
            ],
        });

        $('.filter').click(function refreshData() {
            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            
            location.reload();
        });     
    });

    function changeVehicleStatus(id,e){
            //e.preventDefault();
        let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
        let vehicle_status = $('#status_'+id).val();
        if(confirm("Are you sure you want to change the status?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.vehicle.type.status') }}',
                data: {'id': id,'vehicle_status':vehicle_status,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    notify('top', 'center', 'fa fa-check', 'success', '', '','Status Change successfully');
                    $('#dt-ajax-array').DataTable().ajax.reload();
                }
            });
        }else{
            return false;
        }
    }

    function changeCfsStatus(id,e){
            //e.preventDefault();
        
        let cfs_id = $('#cfs_'+id).val();
        if(confirm("Are you sure you want to change the cfs?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.vehicle.cfs') }}',
                data: {'id': id,'cfs_id':cfs_id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    notify('top', 'center', 'fa fa-check', 'success', '', '','CFS Change successfully');
                    $('#dt-ajax-array').DataTable().ajax.reload();
                }
            });
        }else{
            return false;
        }
    }



    // $(document).on('onclick', '.estimeted_time', function(event) {
    //     var id = $(this).data('id');
    //     console.log('id');
    //     $("#addEstimatedTimeModal").show('modal');

    // });

    function openEstimatedTimeModal(id){
        $("#addEstimatedTimeModal #vehicle_id").val(id);
        $('#addEstimatedTimeModal').modal('show');
    }

    function addEstimatedTime(e){
        event.preventDefault();
        var complain = $("#addEstimatedTimeModal #complain").val();
        var vehicle_id = $("#addEstimatedTimeModal #vehicle_id").val();
        var estimeted_time = $("#addEstimatedTimeModal #estimeted_time").val();
        var followup_by = $("#addEstimatedTimeModal #followup_by").val();
        var location = $("#addEstimatedTimeModal #location").val();

        if(estimeted_time > 0){
            if(confirm("Are you sure you want to change the repair?")){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('update.vehicle.type.status') }}',
                    data: {"_token": "{{ csrf_token()}}",
                           'id': vehicle_id,
                           'vehicle_status':'Repair',
                           'complain':complain,
                           'estimeted_time':estimeted_time,
                           'followup_by':followup_by,
                           'location':location,
                        },
                    dataType:'json',
                    success: function (responseData) {
                       $('#addEstimatedTimeModal').modal('hide');
                       $("#addEstimatedTimeModal .modal-input").val('');
                       $("#addEstimatedTimeModal .modal-input-select").val('').select2();
                       
                       notify('top', 'center', 'fa fa-check', 'success', '', '','Added to Repair successfully');
                        $('#dt-ajax-array').DataTable().ajax.reload();
                    }
                }); 
            }else{
                return false;
            }
        }
    }

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
                    y: 200
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
</script>
@endsection