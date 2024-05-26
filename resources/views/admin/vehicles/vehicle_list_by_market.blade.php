@extends('layouts.app')
@section('title', 'Vehicles By Market')

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
                                        <h4>MARKET VEHICLES</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" 
                                                data-target="#filterTableModal" title="click here to filter" >
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
                                                    <th>Transporter</th>
                                                    <th>Job No</th>
                                                    <th>Change Vehicle Status</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No</th>
                                                    <th>Transporter</th>
                                                    <th>Job No</th>
                                                    <th>Change Vehicle Status</th>
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
                    <label for="registration_no">Vehicle No</label>
                    <input type="text" class="form-control" id="registration_no" placeholder="registration no">
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

        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                url: "{{route('data.vehicle.market')}}",
                data: function(d) {
                    d.registration_no = $('#registration_no').val();
                    // etc
                }
            },
            "columns": [
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "registration_no" },
                { "data": "transporter_party_name" },
                { "data": "job_no" },
                { "data": "change_status" },
            ],
            
        });

        $('.filter').click(function refreshData() {
            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            location.reload();
        });
    });

    function changeVehicleStatus(id,trip_id,e){
            //e.preventDefault();
        let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
        let vehicle_status = $('#status_'+id).val();
        if(confirm("Are you sure you want to change the status?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.market.vehicle.type.status') }}',
                data: {'id': id,'trip_id':trip_id,'vehicle_status':'Available',"_token": "{{ csrf_token() }}",},
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