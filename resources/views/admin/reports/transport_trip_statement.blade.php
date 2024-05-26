@extends('layouts.app')
@section('title','Trip Statement')
@section('content')
<style type="text/css">
    td:hover {
        background-color: #ed9923;
        color: black;
    }
    .yellowClass{
        background-color: #ffb64d !important; 
    }
    .greenClass{
        background-color: #2ed8b6 !important; 
    }
    .redClass{
        background-color: #ff5370 !important; 
    }
    .infoClass{
        background-color: #D2B4DE !important; 
    }
    .blueClass{
        background-color: #00bcd4 !important; 
    }

   
</style>
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
                                        
                                        <button class="btn btn-warning export waves-effect waves-light float-right ml-1"><i class="icofont icofont-file-spreadsheet"></i>Export</button>
                                        <button class="btn btn-primary float-right ml-1" data-toggle="modal" data-target="#filterTableModal" title="click here to filter" ><i class="feather icon-filter"></i> Filter</button>

                                        <button class="btn btn-info float-right ml-1" title="click here to billing" id="btn_checked">Billing</button>
                                        
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
                                            <th>Sr</th>
                                            <th>LR No.</th>
                                            <th>Transporter</th>
                                            <th>Vehicle No</th>
                                            <th>Route</th>
                                            <th>Market Freight</th>
                                            <th><input id='check_all' type='checkbox'></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr style="background-color: #263644;color: white;">
                                            <th>Sr</th>
                                            <th>LR No.</th>
                                            <th>Transporter</th>
                                            <th>Vehicle No</th>
                                            <th>Route</th>
                                            <th >Market Freight</th>
                                            <th id="totalFreight"></th>
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

<div class="modal fade" id="billingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">TRANSPORTER BILLING DETAIL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="modal-input-field" id="trip_ids">
                
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Transporter Bill No</label>
                        <input type="text" class="form-control modal-input-field" id="transporter_bill_no">
                    </div>
                
                    <div class="form-group col-md-12">
                        <label>Transporter Bill Date</label>
                        <input type="date" class="form-control modal-input-field" id="transporter_bill_date" >
                    </div>
                
                    <div class="form-group col-md-12">
                        <label>Transporter Bill Document</label>
                        <input type="file" class="form-control modal-input-field" id="transporter_bill_doc" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light updateTripTransporterBillingDetail">Save</button>
                
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
                    <select name="party_id" id="party_id" class="form-control select2" style="width:100%;">
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
                    <select name="route_id" id="route_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE ROUTE</option>       
                        @if(!empty($routes))
                        @foreach($routes as $k =>$route)   
                        <option value="{{$route->id}}">
                            <?php 
                                $routeData= $route;
                                $RouteName=(isset($routeData))?$routeData->from_place.'-'.$routeData->destination_1:'';
                                $RouteName.=(isset($routeData) && $routeData->destination_2!='')?'-'.$routeData->destination_2:'';
                                $RouteName.=(isset($routeData) && $routeData->destination_3!='')?'-'.$routeData->destination_3:'';
                                echo $RouteName;
                            ?>
                        </option>       
                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE VEHICLE</option>       
                        @if(!empty($vehicles))
                        @foreach($vehicles as $k =>$vehicle)
                        @if($vehicle->type == 'market')
                        <?php  $transporter=(isset($vehicle->getTransporter->name))?$vehicle->getTransporter->name:''?>
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no .' / '.$transporter }}</option>  
                        @else
                        <option value="{{$vehicle->id}}">{{$vehicle->registration_no}}</option>  
                        @endif

                        @endforeach
                        @endif
                    </select>
                </div>  

                <div class="form-group col-lg-6 col-md-3">
                    <label>Bill Pending</label>
                    <select name="is_bill_pending" id="is_bill_pending" class="form-control select2" style="width:100%;">
                        <option value="">CHOOSE BILL PENDING</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-3">
                    <label>LR No</label>
                    <input type="text" class="form-control" id="lr_no" value="">
                </div>
                 
                <div class="form-group col-lg-6 col-md-3">
                    <label>From LR date</label>
                    <input type="date" class="form-control" id="from_date" value="">
                </div>
                <div class="form-group col-lg-6 col-md-3">
                    <label>To LR date</label>
                    <input type="date" class="form-control" id="to_date" value="">
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
            "paging":false,
            "ajax": {
                url: "{{route('report.transporter.trips.statement.paginate')}}",
                type: "POST",
                data: function(d) {
                    d._token = '{{csrf_token()}}';
                    d.party_id = $('#party_id').val();
                    d.vehicle_id = $('#vehicle_id').val();
                    d.route_id = $('#route_id').val();
                    d.lr_no = $('#lr_no').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.is_bill_pending = $('#is_bill_pending').val();

                    // d.is_market_lr = $('#is_market_lr').val();
                    // d.market_freight_authorised_by = $('#market_freight_authorised_by').val();
                   
                    // etc
                }
            },
            
            "columns": [
            { "data": "id" },
            { "data": "lr_no" },
            { "data": "transporter" },
            { "data": "vehicle_no" },
            { "data": "route_name" },
            { "data": "market_freight" },
            { "data": "action" ,"orderable": false, }
            ],
            "createdRow": function (row, data, index) {
			  if(data.lr_status == '0'){
                $(row).addClass('redClass');
              }
            },
            "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
                return nRow;
            },
            
           });

        $('.filter').click(function refreshData() {
            table.ajax.reload();
        });

        $('.clear').click(function refreshData() {
            location.reload();
        });

        $('.export').click(function refreshData() {
            var param={
                party_id : $('#party_id').val(),
                vehicle_id : $('#vehicle_id').val(),
                route_id : $('#route_id').val(),
                job_no : $('#job_no').val(),
                lr_no : $('#lr_no').val(),
                //movement_type : $('#movement_type').val(),
                // dropdate_missing : $('#dropdate_missing').val(),
                // is_market_lr : $('#is_market_lr').val(),
                // market_freight_authorised_by : $('#market_freight_authorised_by').val(),
                from_date : $('#from_date').val(),
                to_date : $('#to_date').val(),
                is_bill_pending : $('#is_bill_pending').val(),
            };

            var url='{{route('report.transporter.trips.statement.export')}}?data='+JSON.stringify(param);
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
    
     $("#check_all").change(function() {
            if ($(this).is(':checked') == true) {
                $(".check_lr").each(function() {
                    $(this).prop('checked', true);
                    
                    var id = $(this).val();
                    var market_freight=$(this).prop('checked', true).attr('market_freight');
                    $("#checked_market_freight_"+id).val(parseFloat(market_freight));

                });
            }else{
                $(".check_lr").each(function() {
                    $(this).prop('checked', false);
                     var id = $(this).val();
                    $("#checked_market_freight_"+id).val(parseFloat(0));
                });
            };
            calcCheckedFreight();
     });

     function getCheckedMarketFreight(id,market_freight){
        if ($("#lr_"+id).is(':checked') == true) {
            $("#checked_market_freight_"+id).val(parseFloat(market_freight));   
        }else{
            $("#checked_market_freight_"+id).val(parseFloat(0));   
        }
        calcCheckedFreight();

     }

     function calcCheckedFreight(){
        var checkedMarketFreight = $("input[name='checkedMarketFreight[]']").map(function(){return $(this).val();}).get();
        
        if(checkedMarketFreight.length>0){
            var totalFreight=checkedMarketFreight.reduce(function(a, b) { return parseFloat(a, 10) + parseFloat(b, 10);})
            $("#totalFreight").html(totalFreight);
        }
    
     }

     $("#btn_checked").click(function() {
            var checked = "";
            $(".check_lr").each(function() {
                if ($(this).is(":checked")) {
                    checked += ","+$(this).val();
                }
            });
            
            $('#trip_ids').val(checked);
            $("#billingModal").modal('toggle');
    });

    $('.updateTripTransporterBillingDetail').click(function () {
            var trip_ids = $('#trip_ids').val();
            var transporter_bill_no = $('#transporter_bill_no').val();
            var transporter_bill_date = $('#transporter_bill_date').val();
            
            if(trip_ids==""){
                alert("PLEASE SELECT TRIPS FOR BILLING ");
                return false;
            }

            var formdata = new FormData();
            formdata.append('_token',"{{csrf_token()}}");
            formdata.append('trip_ids', trip_ids);
            formdata.append('transporter_bill_no', transporter_bill_no);
            formdata.append('transporter_bill_date', transporter_bill_date);
            formdata.append('transporter_bill_doc', $('#transporter_bill_doc')[0].files[0]);

            $.ajax({
                    url: '{{ route('update.transport.trip.bill.detail') }}',
                    data: formdata,
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    dataType:'json',
                    'success':function(data){
                        $('#dt-ajax-array').DataTable().ajax.reload();
                        $("#billingModal .modal-input-field").val('');
                        $('#check_all').prop('checked', false);
                        $('#billingModal').modal('toggle');
                    }
                });
    });

 </script>
 @endsection