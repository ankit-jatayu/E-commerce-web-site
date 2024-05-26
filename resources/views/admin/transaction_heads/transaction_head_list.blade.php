<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$TransactionHeadModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==11;})->first();
?>
@extends('layouts.app')
@section('title','Transaction Head')
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
                                     @if(isset($TransactionHeadModuleRights) && $TransactionHeadModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        @endif
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>
                                       @if(isset($TransactionHeadModuleRights) && $TransactionHeadModuleRights->is_create==1)
                                            <a href="{{route('transaction.head.add')}}" class="btn waves-effect waves-light btn-primary float-right">
                                                <i class="icofont icofont-plus"></i>Add New 
                                            </a>
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
                                            <th>Name</th>
                                            <th>Particulars</th>
                                            <th>Transaction Type</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>Name</th>
                                            <th>Particulars</th>
                                            <th>Transaction Type</th>
                                            
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

<div class="modal fade filterTableModal" id="filterTableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
           Filter Records 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="feather icon-x-circle"></i></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Transaction Type</label>
                    <select name="transaction_type" id="transaction_type" class="form-control select2" style="width: 100%;"  required>
                            <option value="">CHOOSE TRANSACTION TYPE</option>
                            <option value="Cash" >Cash</option>
                            <option value="Bank" >Bank</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Particulars</label>
                    <select name="type" id="type" class="form-control select2" style="width: 100%;"  required>
                            <option value="">CHOOSE PARTICULARS</option>
                            <option value="Income">Income</option>
                            <option value="Expense">Expense</option>
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
            "pageLength": 100,
            "ajax": {
                url: "{{route('transaction.head.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.transaction_type = $('#transaction_type').val();
                    d.type = $('#type').val();
                    // etc
                }
            },
            
            "columns": [
                { "data": "action" },
                { "data": "name" },
                { "data": "type" },
                { "data": "transaction_type" },
            ],
            "createdRow": function (row, data, index) {
		    },
           });

        $('.filter').click(function refreshData() {
            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            $(".filter-input").val('');
            $(".filter-input-select").val('').select2();
            table.ajax.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                party_id : $('#party_id').val(),
                route_id : $('#route_id').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('transaction.head.export')}}?data='+JSON.stringify(param);
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

    // function changeStatus(id,e){
	// 	  	//e.preventDefault();
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

    
    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('transaction.head.delete')}}';
        window.location.href=url+'?id='+id;
      }
    }
    
 </script>
 @endsection