@extends('layouts.app')
@section('title','Driver Doc Dues')

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
                                        <h4>DRIVER DOC DUES</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" 
                                                data-target="#filterTableModal" title="click here to filter" >
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
                                                    <th>Driver</th>
                                                    <th>Due Document</th>
                                                    <th>Validity</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    {{-- <th>Action</th> --}}
                                                    <th>Sr No.</th>
                                                    <th>Driver</th>
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
                    <label for="driver_id">Driver</label>
                    <select id="driver_id" class="form-control select2 filter-input-select" required name="driver_id" style="width:100%">
                        <option value="">Choose Driver</option>
                        @if(isset($drivers))
                            @foreach($drivers as $key => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="due_name">Document</label>
                    <select name="due_name" id="due_name" class="form-control select2 filter-input-select" style="width:100%">
                        <option value="" >Choose Document</option>
                        <option value="License" >License</option>
                        <option value="Adani pass">Adani pass</option>
                        <option value="Bank passbook/cheque">Bank passbook/cheque</option>
                        <option value="Adhaar card">Adhaar card</option>
                        <option value="Pan Card">Pan Card</option>
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
        $(".select2").select2();
        var table  = $('#dt-ajax-array').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{route('list.driver.due')}}",
                data: function(d) {
                    d.driver_id = $('#driver_id').val();
                    d.due_name = $('#due_name').val();
                    d.fromDt = $('#fromDt').val();
                    d.toDt = $('#toDt').val();
                    //d.mobile_no = $('#mobile_no').val();
                    // etc
                }
            },
            "columns": [
                // { "data": "action" },
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "driver_id" },
                { "data": "due_name" },
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
                driver_id : $('#driver_id').val(),
                due_name : $('#due_name').val(),
                fromDt : $('#fromDt').val(),
                toDt : $('#toDt').val(),
            };
            var url='{{route('export.driver.doc.dues')}}?data='+JSON.stringify(param);
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
                url: '{{ route('update.status.driver') }}',
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