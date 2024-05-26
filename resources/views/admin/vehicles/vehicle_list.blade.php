<?php
$user_id = Auth::user()->id;
$role = Auth::user()->role_id;

$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
$VehicleModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==14;})->first();
?>
@extends('layouts.app')
@section('title', 'Vehicles')

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
                                                <h4 >VEHICLES</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb float-right">
                                                @if(isset($VehicleModuleRights) && $VehicleModuleRights->is_create==1)
                                                <a href="{{route('add.vehicle')}}" 
                                                    class="btn btn-sm btn-primary ml-1"><i class="fa fa-plus-circle"></i>Add New
                                                </a>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-info ml-1" 
                                                        data-toggle="modal" data-target="#filterTableModal" 
                                                        title="click here to filter" >
                                                    <i class="feather icon-filter"></i> Filter
                                                </button>
                                                @if(isset($VehicleModuleRights) && $VehicleModuleRights->is_export==1)
                                                <button type="button" 
                                                    class="btn btn-sm btn-warning export ml-1">
                                                    <i class="icofont icofont-file-spreadsheet"></i>Export
                                                </button>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver</th>
                                                    <th>Model code</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver</th>
                                                    <th>Model code</th>
                                                    <th>Vehicle Type</th>
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
                    <input type="text" class="form-control filter-input" id="registration_no" placeholder="Vehicle No">
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label>Is Equipment Vehicle</label>
                    <select name="equipment_vehicle" id="equipment_vehicle" class="form-control select2 filter-input-select"  style="width: 100%">
                        <option value="">CHOOSE IS EQUIPMENT VEHICLE</option>       
                        <option value="1">Yes</option>       
                        <option value="0">No</option>       
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label>Model Code</label>
                    <select name="model_code" id="model_code" class="form-control select2 filter-input-select"  style="width: 100%">
                     <option value="">CHOOSE MODEL CODE</option>       

                     @if(!empty($VehicleModelCodes))
                     @foreach($VehicleModelCodes as $k =>$singledata)
                     <option value="{{$singledata->name}}" >{{$singledata->name}}</option>
                     @endforeach
                     @endif
                    </select>
                 </div>
                 <div class="form-group col-lg-6 col-md-3">
                    <label for="vehicle_type">Vehicle Type</label>
                    <select name="vehicle_type" id="vehicle_type" class="form-control select2 filter-input-select" style="width: 100%">
                     <option value="">CHOOSE VEHICLE TYPE</option>       
                     @if(!empty($VehicleTypes))
                     @foreach($VehicleTypes as $k =>$row)   
                     <option value="{{$row->name}}">{{$row->name}}</option>       
                     @endforeach
                     @endif
                    </select>
                  </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-3" style="margin-top: 27px;">
                    <button type="button" class="btn btn-primary filter-datatable " data-dismiss="modal" aria-label="Close" >
                        <i class="icofont icofont-search"></i> Search 
                    </button>
                    <button type="button" class="btn btn-danger clear-filter-datatable-without-reload" data-dismiss="modal" aria-label="Close" >
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
            drawCallback : function( settings ) {
                $( "#dt-ajax-array input[type=checkbox]:checked" ).siblings().children("small").css("left","20px");
            },
            "ajax": {
                "type":'POST',
                url: "{{route('data.vehicle')}}",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.registration_no = $('#registration_no').val();
                    d.equipment_vehicle = $('#equipment_vehicle').val();
                    d.model_code = $('#model_code').val();
                    d.vehicle_type = $('#vehicle_type').val();
                }
            },
            "columns": [
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "registration_no" },
                { "data": "driver_detail" },
                { "data": "model_code" },
                { "data": "vehicle_type" },
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

       
        $('.export').click(function refreshData() {
            var param={
                registration_no : $('#registration_no').val(),
                equipment_vehicle : $('#equipment_vehicle').val(),
                model_code : $('#model_code').val(),
                vehicle_type : $('#vehicle_type').val(),
            };

            var url='{{route('export.vehicle')}}?data='+JSON.stringify(param);
            window.location.href=url; 
        });
    });



    function changeStatus(id,e){
            //e.preventDefault();
        let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
        if(confirm("Are you sure you want to change the status?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('update.status.vehicle') }}',
                data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    
                }
            });
        }else{
            return false;
        }
    }
</script>
@endsection