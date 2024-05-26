<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$BillModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==7;})->first();
?>

@extends('layouts.app')
@section('title','Trips')
@section('content')

<div class="pcoded-content">

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <h4 >{{$title}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb float-right ">

                                                @if(isset($BillModuleRights) && $BillModuleRights->is_create==1)

                                                <a href="{{route('bill.add')}}" class="btn btn-primary ml-1 btn-sm">
                                                        <i class="fa fa-plus-circle"></i>Add New </a>
                                                @endif

                                                <button type="button" class="btn btn-info btn-sm ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>
                                                @if(isset($BillModuleRights) && $BillModuleRights->is_export==1)

                                                <button type="button" class="btn btn-warning export ml-1 btn-sm">
                                                        <i class="icofont icofont-file-spreadsheet"></i>Export
                                                </button>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="card-block" style="padding-top: 0 !important;">
                            <div class="table-responsive dt-responsive">
                                <table id="dt-ajax-array" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>Bill No.</th>
                                            <th>Bill Date</th>
                                            <th>Party</th>
                                            <th>With Gst</th>
                                            <th>Total Amount</th>
                                            <th>Total Reamining Amount</th>
                                            <th>Company Name</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>Bill No.</th>
                                            <th>Bill Date</th>
                                            <th>Party</th>
                                            <th>With Gst</th>
                                            <th>Total Amount</th>
                                            <th>Total Reamining Amount</th>
                                            <th>Company Name</th>
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
              {{--   <div class="form-group col-lg-6 col-md-3">
                    <label>Party</label>
                    <select name="party_id" id="party_id" class="form-control filter-input-select select2" style="width:100%;">
                        <option value="">CHOOSE PARTY</option>       
                        @if(!empty($parties))
                        @foreach($parties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>   --}}

                <div class="form-group col-lg-6 col-md-3">
                    <label>Bill No</label>
                    <input type="text" class="form-control  filter-input" id="bill_no" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>With GST</label>
                    <select name="with_gst" id="with_gst" class="form-control select2 filter-input-select" style="width: 100%;"  required >
                        <option value="">CHOOSE OPTION</option>
                        <option value="Yes"> Yes</option>
                            <option value="No" >No</option>

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
            "scrollX": true,
            "ajax": {
                url: "{{route('bill.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.with_gst = $('#with_gst').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
                { "data": "action" },
                { "data": "bill_no" },
                { "data": "bill_date" },
                { "data": "party_id" },
                { "data": "with_gst" },
                { "data": "total_amount" },
                { "data": "remain_amount" },
                { "data": "company_id" },
                { "data": "created_by" },
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
            $(".filter-input").val('');
            $(".filter-input-select").val('').select2();
            table.ajax.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                party_id : $('#party_id').val(),
                with_gst : $('#with_gst').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
            };

            var url='{{route('bill.export')}}?data='+JSON.stringify(param);
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
        url='{{route('delete.bill')}}';
        window.location.href=url+'?id='+id;
      }
    }
    
    function printBill(id){
       var url='{{route('bill.print')}}?id='+id;
       // window.location.href=url;
       $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    }

 </script>
 @endsection