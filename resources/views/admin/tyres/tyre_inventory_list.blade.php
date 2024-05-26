<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$TyreModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==17;})->first();
?>
@extends('layouts.app')
@section('title','Tyre')
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
                                        <h4>{{$title}}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        
                                    	{{-- <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" >
                                            <i class="feather icon-filter"></i> Filter
                                        </button> --}}
                                        @if(isset($TyreModuleRights) && $TyreModuleRights->is_create==1)

                                        <a href="{{route('tyres.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color: white">
                                </div>
                            <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="dt-ajax-array" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                             <th>SR No.</th>
                                            <th>Tyre Mode</th>
                                            <th>Vehicel Name</th>
                                            <th>Tyre Brands</th>
                                            <th>Tyre Serial Number</th>
                                            <th>Size</th>
                                            <th>Tread Pattern</th>
                                            <th>Tread Depth (mm)</th>
                                            <th>Pressure</th>
                                            <th>Position In Vehicle </th>
                                            <th>Max Running Limit (km)</th>
                                            <th>Tread Pattern</th>
                                            <th>Tyre Odo</th>
                                            <th>Manufacturer Date</th>
                                            <th>Remarks</th>  
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>SR No.</th>
                                            <th>Tyre Mode</th>
                                            <th>Vehicel Name</th>
                                            <th>Tyre Brands</th>
                                            <th>Tyre Serial Number</th>
                                            <th>Size</th>
                                            <th>Tread Pattern</th>
                                            <th>Tread Depth (mm)</th>
                                            <th>Pressure</th>
                                            <th>Position In Vehicle </th>
                                            <th>Max Running Limit (km)</th>
                                            <th>Tread Condition</th>
                                            <th>Tyre Odo</th>
                                            <th>Manufacturer Date</th>
                                            <th>Remarks</th>  
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
                        <label for="is_nickname">Nickname Blank</label>
                        <select name="is_nickname" id="is_nickname" class="form-control select2 filter-input-select" style="width:100%">
                        <option value="">Choose Nickname Blank</option>       
                        <option value="Yes">Yes</option>       
                        <option value="No">No</option>       

                     </select>
                </div> --}}

                <div class="form-group col-lg-6 col-md-3">
                    <label>Ledger Type</label>
                    <select name="ledger_type_id" id="ledger_type_id" class="form-control select2" style="width: 100%;" required>
                        <option value="">CHOOSE LEDGER TYPE</option>       
                        @if(!empty($ledger_types))
                        @foreach($ledger_types as $k =>$ledgerType)   
                            <option value="{{$ledgerType->id}}">
                                {{$ledgerType->name}}
                            </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="from_date">From date</label>
                    <input type="date" class="form-control filter-input" id="from_date" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="to_date">To date</label>
                    <input type="date" class="form-control filter-input" id="to_date" value="">
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
        @if(Session::get('success'))
        notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif
        
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 100,
            "scrollX":true,
            drawCallback : function( settings ) {
                $( "#dt-ajax-array input[type=checkbox]:checked" ).siblings().children("small").css("left","20px");
            },
            "ajax": {
                url: "{{route('tyres.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    // d.party_id = $('#party_id').val();
                    d.ledger_type_id = $('#ledger_type_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    //d.mobile_no = $('#mobile_no').val();
                    // etc
                }
            },
            
            "columns": [
             { "data": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "mode" },
            { "data": "vehicle_id" },
            { "data": "tyre_brand_id" },
            { "data": "serial_number" },
            { "data": "size" },
            { "data": "tread_pattern" },
            { "data": "tread_depth" },
            { "data": "pressure" },
            { "data": "position_vehicle_id" },
            { "data": "max_running_limit" },
            { "data": "tyre_condition" },
            { "data": "odo" },
            { "data": "manufacturer_dt" },
            { "data": "remarks" },
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
            $('.filter-input').val('');
            $('.filter-input-select').val('').select2();
            table.ajax.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                // party_id : $('#party_id').val(),
                // service_request_type_id : $('#service_request_type_id').val(),
                ledger_type_id : $('#ledger_type_id').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('party.export')}}?data='+JSON.stringify(param);
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
                url: '{{ route('update.status.party') }}',
                data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
                success: function (data) {

                }
            });
         }else{
             return false;
         }
    }

    function deleteRecord(id){
        var id = id;
       if(confirm("Are you sure you want to Delete Detail ?")){
        $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('delete.tyres') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}", },
                success: function (data) {
                $('#dt-ajax-array').DataTable().ajax.reload();
                alert("Record Deleted");
                }
            });
        }else{
            return false;
        }
    }
 </script>
 @endsection