<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$AccontBookModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==8;})->first();
?>

@extends('layouts.app')
@section('title','Account Book')
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
                                        @if(isset($AccontBookModuleRights) && $AccontBookModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        @endif
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>
 
                    
                                        @if(isset($AccontBookModuleRights) && $AccontBookModuleRights->is_create==1)
                                            <a href="{{route('account.book.add')}}" class="btn waves-effect waves-light btn-primary float-right">
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
                                            <th>Payment Date</th>
                                            <th>Party</th>
                                            <th>Transaction Type</th>
                                            <th>Account Type</th>
                                            <th>Particulars</th>
                                            <th>Head Type</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Branch</th>
                                            <th>Narration</th>
                                            <th>Remarks</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>Payment Date</th>
                                            <th>Party</th>
                                            <th>Transaction Type</th>
                                            <th>Account Type</th>
                                            <th>Particulars</th>
                                            <th>Head Type</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Branch</th>
                                            <th>Narration</th>
                                            <th>Remarks</th>
                                            <th>Created By</th>

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
                <div class="form-group col-lg-6 col-md-3">
                    <label>Party</label>
                    <select name="party_id" id="party_id" class="form-control filter-input-select select2" style="width:100%;">
                        <option value="">CHOOSE PARTY</option>       
                        @if(!empty($Parties))
                        @foreach($Parties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Transaction Type</label>
                    <select name="transaction_type" id="transaction_type" class="form-control select2" style="width: 100%;"  required onchange="getTransationHeads()">
                            <option value="">CHOOSE TRANSACTION TYPE</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Account Type</label>
                    <select name="account_type_id" id="account_type_id" class="form-control select2" style="width: 100%;"  required >
                        <option value="">CHOOSE ACCOUNT TYPE</option>
                        @if(!empty($AccountType))
                            @foreach($AccountType as $k =>$row)   
                                <option value="{{$row->id}}">
                                    {{$row->name}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Particulars</label>
                    <select name="type" id="type" class="form-control select2" style="width: 100%;"  required onchange="getTransationHeads()">
                            <option value="">CHOOSE PARTICULARS</option>
                            <option value="Income">Income</option>
                            <option value="Expense">Expense</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Transaction Head</label>
                    <select name="head_type_id" id="head_type_id" class="form-control select2" style="width: 100%;"  required >
                        <option value="">CHOOSE TRANSACTION HEAD</option>
                        @if(!empty($selectedTransHeads))
                            @foreach($selectedTransHeads as $k =>$row)   
                                <option value="{{$row->id}}">
                                    {{$row->name}}
                                </option>
                            @endforeach
                        @endif
                    </select>

                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>From date</label>
                    <input type="date" class="form-control  filter-input" id="from_date" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>To date</label>
                    <input type="date" class="form-control  filter-input" id="to_date" value="">
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
            "scrollX": true,
            "ajax": {
                url: "{{route('account.book.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.head_type_id = $('#head_type_id').val();
                    d.type = $('#type').val();
                    d.account_type_id = $('#account_type_id').val();
                    d.transaction_type = $('#transaction_type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
                { "data": "action" },
                { "data": "entry_date" },
                { "data": "party_id" },
                { "data": "transaction_type" },
                { "data": "account_type_id" },
                { "data": "type" },
                { "data": "head_type_id" },
                { "data": "debit" },
                { "data": "credit" },
                { "data": "branch" },
                { "data": "narration" },
                { "data": "remarks" },
                { "data": "created_by" },
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
                head_type_id : $('#head_type_id').val(),
                type : $('#type').val(),
                account_type_id : $('#account_type_id').val(),
                transaction_type : $('#transaction_type').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('account.book.export')}}?data='+JSON.stringify(param);
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

   
    function deleteRecord(id){
      if(confirm('are you sure want to delete this record?')){
        url='{{route('account.book.delete')}}';
        window.location.href=url+'?id='+id;
      }
    }

    function getTransationHeads(){
        var transaction_type= $('#transaction_type').val();   
        var type= $('#type').val();   
        // $(".transaction_type_error").html('');
        // if(transporter_type == ''){
        //    $(".transaction_type_error").html('field is required*');
        //     return false;
        // }

        if(transaction_type!='' && type!=''){
            $.ajax({
                type: "POST",
                url: '{{ route('account.book.get.transaction.head') }}',
                data: {
                        "_token": "{{ csrf_token() }}",
                        'transaction_type': transaction_type,
                        'type': type,
                },
                dataType: "json",
                success: function (data) {
                    var options = '<option value="">CHOOSE TRANSACTION HEAD</option>';
                    if(data.length>0){
                        $(data).each((index, row) => {
                            options += '<option value="'+row.id+'">'+row.name+'</option>';
                        });
                    } //if close

                    $('#head_type_id').html(options).select2();
                }
             });    
        } 
        
    }
    
 </script>
 @endsection