<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$RouteModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==3;})->first();
?>

@extends('layouts.app')
@section('title','Routes')

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
                                        <h4>ROUTE</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        @if(isset($RouteModuleRights) && $RouteModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning export float-right waves-effect waves-light ml-1"><i class="icofont icofont-file-spreadsheet"></i> Export</button>
                                        @endif

                                        @if(isset($RouteModuleRights) && $RouteModuleRights->is_create==1)

                                        <a href="{{route('add.route')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                        @endif

                                        {{-- <a href="{{route('add.route')}}" class="btn waves-effect waves-light btn-warning float-right"><i class="icofont icofont-file-spreadsheet"></i>Export</a> --}}


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block" style="padding-top: 0 !important;">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Route</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Route</th>
                                                    <th>Status</th>
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
            "pageLength": 100,
            "ajax": {
                url: "{{route('data.route')}}",
                data: function(d) {
                    d.name = $('#name').val();
                    //d.mobile_no = $('#mobile_no').val();
                    // etc
                }
            },
            drawCallback : function( settings ) {
                $( "#dt-ajax-array input[type=checkbox]:checked" ).siblings().children("small").css("left","20px");
            },
            "columns": [
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "route" },
                { "data": "btn_toggel" },
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
            $('#name').val('');
            
            table.ajax.reload();
        });

         $('.export').click(function refreshData() {
            // var param={
            //     'name' : $('#name').val(),
            // };
    
            //var url='{{route('export.route')}}?data='+JSON.stringify(param);
            var url='{{route('export.route')}}';
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
                url: '{{ route('update.status.route') }}',
                data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    
                }
        	});
	    }else{
	        return false;
	    }
	}


    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('route.delete')}}';
        window.location.href=url+'?id='+id;
      }
    }
</script>
@endsection