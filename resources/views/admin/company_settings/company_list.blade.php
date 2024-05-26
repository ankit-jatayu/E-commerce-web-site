@extends('layouts.app')
@section('title','Users')
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
                                    <div class="page-header-title" >
                                        <h4>COMPANY</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="page-header-breadcrumb">
                                        {{-- <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" >
                                            <i class="feather icon-filter"></i>
                                        </button> --}}
                                        {{-- <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button> --}}
                                        {{-- <a href="{{route('add.user')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block">
                                    <div class="table-responsive dt-responsive">
                                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Company Name</th>
                                                    <th>Address</th>
                                                    <th>Gst_no</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr style="background-color: #263644;color: white;">
                                                    <th>Sr No.</th>
                                                    <th>Company Name</th>
                                                    <th>Address</th>
                                                    <th>Gst_no</th>
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
                        <label>User Type</label>
                        <select name="role_id" id="role_id" class="form-control select2 filter-input-select"  style="width: 100%">
                         <option value="">CHOOSE USER TYPE</option>       

                         @if(!empty($roles))
                         @foreach($roles as $k =>$singledata)
                         <option value="{{$singledata->id}}" >{{$singledata->name}}</option>
                         @endforeach
                         @endif
                     </select>
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label for="party_id">Party</label>
                    <select name="party_id" id="party_id" class="form-control select2 filter-input-select" style="width: 100%">
                     <option value="">CHOOSE PARTY</option>       
                     @if(!empty($parties))
                     @foreach($parties as $k =>$party)   
                     <option value="{{$party->id}}">{{$party->name}}</option>       
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
                url: "{{route('data.company')}}",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.role_id = $('#role_id').val();
                    d.party_id = $('#party_id').val();
                    // etc
                }
            },
            "columns": [
                { "data": 'DT_RowIndex', orderable: false, searchable: false },
                { "data": "company_name" },
                { "data": "address" },
                { "data": "gst_no" },
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
                role_id : $('#role_id').val(),
                party_id : $('#party_id').val(),
                // service_request_type_id : $('#service_request_type_id').val(),
                // bill_name : $('#bill_name').val(),
                // from_date : $('#from_date').val(),
                // to_date : $('#to_date').val(),
            };

            var url='{{route('user.exports')}}?data='+JSON.stringify(param);
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
                url: '{{ route('update.status.user') }}',
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