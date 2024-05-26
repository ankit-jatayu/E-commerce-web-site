<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$SalaryVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==15;})->first();
?>
@extends('layouts.app')
@section('title','Salary Vouchers')

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
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal"
                                             data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i>
                                         </button>
                                        @if(isset($SalaryVchModuleRights) && $SalaryVchModuleRights->is_export==1)

                                        <button type="button" class="btn btn-warning float-right ml-1 export">
                                            <i class="icofont icofont-file-spreadsheet"></i>
                                        </button>
                                        @endif
                                        @if(isset($SalaryVchModuleRights) && $SalaryVchModuleRights->is_create==1)

                                    	<a href="{{route('salary.voucher.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
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
                                            <th>Action</th>
                                            <th>SR No.</th>
                                            <th>Voucher No.</th>
                                            <th>Voucher From Dt.</th>
                                            <th>Voucher To. Dt.</th>
                                            <th>Branch</th>
                                            <th>Vehicle</th>
                                            <th>Driver</th>
                                            <th>Salary Amount</th>
                                            <th>Deduct Amount</th>
                                            <th>Payable Amount</th>
                                            <th>Payment Type</th>
                                            <th>Salary Date</th>
                                            <th>Remarks</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>SR No.</th>
                                            <th>Voucher No.</th>
                                            <th>Voucher From Dt.</th>
                                            <th>Voucher To. Dt.</th>
                                            <th>Branch</th>
                                            <th>Vehicle</th>
                                            <th>Driver</th>
                                            <th>Salary Amount</th>
                                            <th>Deduct Amount</th>
                                            <th>Payable Amount</th>
                                            <th>Payment Type</th>
                                            <th>Salary Date</th>
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
                    <label for="from_date">From date</label>
                    <input type="date" class="form-control filter-input" id="from_date" value="{{date('Y-m-01')}}">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="to_date">To date</label>
                    <input type="date" class="form-control filter-input" id="to_date" value="{{date('Y-m-t')}}">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>Branch</label>
                    <select name="branch" id="branch" class="form-control select2 filter-input-select" style="width: 100%;">
                        <option value="">CHOOSE BRANCH</option>
                        <option value="Mundra" {{('Mundra'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Mundra</option>
                        <option value="Gandhidham" {{('Gandhidham'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Gandhidham</option>
                    </select>
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="vehicle_id">Vehicle </label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2 filter-input-select" style="width: 100%;">
                     <option value="">CHOOSE VEHICLE</option>       
                     @if(!empty($Vehicles))
                     @foreach($Vehicles as $k =>$row)   
                     <option value="{{$row->id}}">{{$row->registration_no}}</option>       
                     @endforeach
                     @endif
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
        $(".select2").select2();

        @if(Session::get('success'))
        notify('top', 'center', 'fa fa-check', 'success', '', '','{{Session::get('success')}}');
        @endif
        
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX":true,
            "ajax": {
                url: "{{route('salary.voucher.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    // d.party_id = $('#party_id').val();
                    // d.service_request_type_id = $('#service_request_type_id').val();
                    // d.bill_name = $('#bill_name').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.branch = $('#branch').val();
                    // etc
                }
            },
            
            "columns": [
            { "data": "action" },
            { "data": 'DT_RowIndex', orderable: false, searchable: false },
            { "data": "salary_voucher_no" },
            { "data": "salary_voucher_from_date" },
            { "data": "salary_voucher_to_date" },
            { "data": "branch" },
            { "data": "vehicle_id" },
            { "data": "driver_id" },
            { "data": "salary_amount" },
            { "data": "deduct_amount" },
            { "data": "payable_amount" },
            { "data": "payment_type" },
            { "data": "salary_voucher_date" },
            { "data": "remarks" },
            { "data": "created_by" },
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
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
                vehicle_id : $('#vehicle_id').val(),
                branch : $('#branch').val(),
            };

            var url='{{route('salary.voucher.export')}}?data='+JSON.stringify(param);
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
        url='{{route('delete.salary.voucher')}}';
        window.location.href=url+'?id='+id;
      }
    }
    // function changeStatus(id,e){
	// 	  	//e.preventDefault();
    //         let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
    //         if(confirm("Are you sure you want to change the status?")){
    //          $.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: '{{ route('update.status.location') }}',
    //             data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
    //             success: function (data) {

    //             }
    //         });
    //      }else{
    //          return false;
    //      }
    //  }

     function printVoucher(id){
       var url='{{route('print.salary.voucher')}}?id='+id;
        //window.location.href=url;
       $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    }
   
 </script>
 @endsection