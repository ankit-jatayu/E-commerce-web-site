<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$ConsigneeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==22;})->first();
?>
@extends('layouts.app')
@section('title','Consignees')

@section('content')
<div class="pcoded-content">

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12" id="alert-msg-block">
                            {{-- @if ($message = Session::get('success'))
                            <div class="alert alert-success background-success" style="width: 100%">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: 2px;">
                                    <i class="icofont icofont-close-line-circled text-white"></i>
                                </button>
                                <strong>{{ $message }}</strong> 
                            </div>
                            @endif --}}

                            {{-- @if ($message = Session::get('error'))
                            <div class="alert alert-danger background-danger" style="width: 100%">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="icofont icofont-close-line-circled text-white"></i>
                                </button>
                                <strong>{{ $message }}</strong> 
                            </div>
                            @endif --}}
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 0px;">  
                        <div class="card-header" >
                            <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <h4>Consignees/Consigner</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <!-- @if(isset($ConsigneeModuleRights) && $ConsigneeModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        @endif -->
                                        @if(isset($ConsigneeModuleRights) && $ConsigneeModuleRights->is_create==1)
                                        <a href="{{route('consignee.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
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
                                    <div class="form-row">
                                        {{-- <div class="form-group col-md-2">
                                            <label for="name">Location</label>
                                            <input type="text" class="form-control" id="name" placeholder="name">
                                        </div>

                                        <div class="form-group col-md-2" style="margin-top: 30px;">
                                            <button type="button" class="btn btn-primary filter "><i class="icofont icofont-search"></i></button>
                                            <button type="button" class="btn btn-danger clear"><i class="icofont icofont-close"></i></button>
                                            
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="card-block" style="padding-top: 0 !important;">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Company Name</th>
                                                    <th>GST No </th>
                                                    <th>Address</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Company Name</th>
                                                    <th>GST No </th>
                                                    <th>Address</th>
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

<script>
    $(document).ready(function () {
        @if(Session::get('success'))
            notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif


        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
             url: "{{route('consignee.paginate')}}",
             type: "POST",
             data: function(d) {
                    d._token = '{{csrf_token()}}';
                    // d.party_id = $('#party_id').val();
                    // d.transporter_id = $('#transporter_id').val();
                    // d.vehicle_id = $('#vehicle_id').val();
                    // d.route_id = $('#route_id').val();
                    // d.lr_no = $('#lr_no').val();
                    // d.is_market_lr = $('#is_market_lr').val();
                    // d.bill_id = $('#bill_id').val();
                    // d.market_freight_authorised_by = $('#market_freight_authorised_by').val();
                    // d.from_date = $('#from_date').val();
                    // d.to_date = $('#to_date').val();
                    // etc
             }
         },
         "columns": [
            { "data": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "company_name" },
            { "data": "gst_no" },
            { "data": "address" },
            { "data": "action" }
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
            $('#name').val('');
            
            table.ajax.reload();
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


    function deleteRecord(id){
        if(confirm('are you sure want to delete this record?')){
            $.ajax({
                'type': "POST",
                'url': '{{route('consignee.delete')}}',
                'data': {"_token": "{{ csrf_token() }}",'id': id},
                success: function (response) {
                    if(response==1){
                         $("#alert-msg-block").html('<div class="alert alert-danger background-danger" style="width: 100%"><strong> Consignee Deleted !!</strong></div>');
                    }else{
                        $("#alert-msg-block").html('<div class="alert alert-danger background-danger" style="width: 100%"><strong> Consignee Not Deleted !!</strong></div>');
                    }
                    $('#dt-ajax-array').DataTable().ajax.reload();
                    
                    setTimeout(function() {
                        $("#alert-msg-block").html('');
                    }, 2500);
                }
            });
        }
    }

    
</script>
@endsection