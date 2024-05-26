<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$TyresServiceTypeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==21;})->first();
?>
@extends('layouts.app')
@section('title','Tyres Service Type')

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
                                        <h4>Tyres Service Type</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                       {{--  <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" >
                                            <i class="feather icon-filter"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button> --}}
                                        @if(isset($TyresServiceTypeModuleRights) && $TyresServiceTypeModuleRights->is_create==1)
                                        <a href="{{route('tyre.service.add')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
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
                                <div class="card-block">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Service Type Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Brand Name</th>
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
                <div class="form-group col-lg-6 col-md-3">
                    <label for="name">Driver name</label>
                    <input type="text" class="form-control filter-input" id="name" placeholder="name">
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
            "ajax": {
                url: "{{route('tyre.service.list')}}",
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
                { "data": "name" },
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
                // bill_name : $('#bill_name').val(),
                name : $('#name').val(),
            };
        });
    });
      function deleteRecord(id){
        var id = id;
       if(confirm("Are you sure you want to Delete Detail ?")){
        $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('delete.tyre.service') }}',
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