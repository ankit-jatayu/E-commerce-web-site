@extends('layouts.app')
@section('title','Route Rates')
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
                                        <button type="button" class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        <button type="button" class="btn btn-info float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>
 
                                        @if(Auth::user()->role_id != 12)
                                            <a href="{{route('route.rate.add')}}" class="btn waves-effect waves-light btn-primary float-right">
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
                                            <th>Party</th>
                                            <th>Route</th>
                                            <th>Rate</th>
                                            <th>Distance(KM)</th>
                                            <th>PTPK</th>
                                            <th>Applicable Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Action</th>
                                            <th>Party</th>
                                            <th>Route</th>
                                            <th>Rate</th>
                                            <th>Distance(KM)</th>
                                            <th>PTPK</th>
                                            <th>Applicable Date</th>
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
                        @if(!empty($parties))
                        @foreach($parties as $k =>$party)   
                        <option value="{{$party->id}}">{{$party->name}}</option>       
                        @endforeach
                        @endif
                    </select>
                </div>  
                <div class="form-group col-lg-6 col-md-3">
                    <label>Route</label>
                    <select name="route_id" id="route_id" class="form-control filter-input-select select2" style="width: 100%;" >
                        <option value="">CHOOSE ROUTE</option>
                        @if(!empty($routes))
                            @foreach($routes as $k =>$row)
                                <option value="{{$row->id}}" >
                                    <?php 
                                    $RouteName=(isset($row))?$row->from_place.'-'.$row->destination_1:'';
                                    $RouteName.=(isset($row) && $row->destination_2!='')?'-'.$row->destination_2:'';
                                    $RouteName.=(isset($row) && $row->destination_3!='')?'-'.$row->destination_3:'';
                                    echo $RouteName;
                                    ?>
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
            "ajax": {
                url: "{{route('route.rate.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.route_id = $('#route_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    // etc
                }
            },
            
            "columns": [
                { "data": "action" },
                { "data": "party_id" },
                { "data": "route_id" },
                { "data": "rate" },
                { "data": "distance" },
                { "data": "ptpk" },
                { "data": "applicable_date" },
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

            var url='{{route('route.rate.export')}}?data='+JSON.stringify(param);
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
        url='{{route('route.rate.delete')}}';
        window.location.href=url+'?id='+id;
      }
    }
    
 </script>
 @endsection