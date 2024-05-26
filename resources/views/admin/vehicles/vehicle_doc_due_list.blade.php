@extends('layouts.app')
@section('title', 'Vehicle Due Docs')

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
                                        <h4>VEHICLE DOC DUES</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" 
                                              title="click here to filter" >
                                            <i class="feather icon-filter"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning export float-right ml-1">
                                            <i class="icofont icofont-file-spreadsheet"></i>
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
                                                    {{-- <th>Action</th> --}}
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No.</th>
                                                    <th>Due Document</th>
                                                    <th>Validity</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    {{-- <th>Action</th> --}}
                                                    <th>Sr No.</th>
                                                    <th>Vehicle No.</th>
                                                    <th>Due Document</th>
                                                    <th>Validity</th>
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
                    <label for="vehicle_id">Vehicle</label>
                    <select id="vehicle_id" class="form-control select2 filter-input-select" required name="vehicle_id" style="width:100%">
                        <option value="">Choose Vehicle</option>
                        @if(isset($vehicles))
                        @foreach($vehicles as $key => $row)
                        <option value="{{$row->id}}">{{$row->registration_no}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="due_id">Document</label>
                    <select id="due_id" class="form-control select2 filter-input-select" required name="due_id" style="width:100%">
                        <option value="">Choose Document</option>
                        @if(isset($dues))
                        @foreach($dues as $key => $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="fromDt">From Date</label>
                    <input type="date" id="fromDt" class="form-control filter-input" value="{{date('Y-m-d')}}">
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label for="toDt">To Date</label>
                    <input type="date" id="toDt" class="form-control filter-input" value="{{date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 day'))}}">
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
                url: "{{route('list.vehicle.due')}}",
                data: function(d) {
                    d.vehicle_id = $('#vehicle_id').val();
                    d.due_id = $('#due_id').val();
                    d.fromDt = $('#fromDt').val();
                    d.toDt = $('#toDt').val();
                }
            },
            "columns": [
                // { "data": "action" },
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "vehicle_id" },
                { "data": "due_id" },
                { "data": "validity" },
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
                vehicle_id : $('#vehicle_id').val(),
                due_id : $('#due_id').val(),
                fromDt : $('#fromDt').val(),
                toDt : $('#toDt').val(),
            };
            var url='{{route('export.vehicle.doc.dues')}}?data='+JSON.stringify(param);
            window.location.href=url; 
        });
    });

    // function changeStatus(id,e){
	// 	  	//e.preventDefault();
	//   	let status = $('#status_'+id).prop('checked') === true ? 1 : 0;
	//   	if(confirm("Are you sure you want to change the status?")){
	//         $.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: '{{ route('update.status.driver') }}',
    //             data: {'id': id,'status':status,"_token": "{{ csrf_token() }}",},
    //             success: function (data) {
                    
    //             }
    //     	});
	//     }else{
	//         return false;
	//     }
	// }
</script>
@endsection