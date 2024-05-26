@extends('layouts.app')
@section('title',(isset($editData))?'Edit Bill':'Add Bill')
@section('content')
<?php 
if(isset($editData) && !empty($editData)){
    extract($editData->toArray());
   
}

?>
<style type="text/css">
    input[type=checkbox] {
        zoom: 1.5;
    }
    .table td,th {
        padding:5px 21px 5px 5px !important;
        font-size:12px;
    }
    
</style>
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
                                               <h4>{{$title}}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                                @if(isset($editData->id))
                                                    {{-- <a class="btn waves-effect waves-light btn-warning float-right ml-1" onclick="printLr({{$editData->id}})"><i class="feather icon-printer" style="color: white;"></i></a> --}}
                                                    <a href="{{route('transport.trip.add')}}" class="btn waves-effect waves-light btn-primary float-right "><i class="icofont icofont-plus"></i>Add New </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('bill.update',base64_encode($editData->id)) : route('bill.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="bill_id" value="{{(isset($editData->id))?$editData->id:''}}">
                                        <input type="hidden" name="total_amount" id="total_amount" value="{{(isset($editData->total_amount))?$editData->total_amount:''}}">

                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label>Bill No <span style="color:red">*</span></label>
                                               <input type="text" name="bill_no" class="form-control "  placeholder="Bill No" id="bill_no" value="{{(isset($bill_no))?$bill_no:''}}" required>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Bill Date</label>
                                                <input type="date" name="bill_date" class="form-control" id="bill_date" value="{{(isset($bill_date))?$bill_date:date('Y-m-d')}}" >
                                            </div>
                                            
                                            <div class="form-group col-md-2">
                                                <label>Payable By <span style="color:red">*</span></label>
                                                <select name="party_id" id="party_id" class="form-control select2"
                                                    style="width: 100%;"  required >
                                                    <option value="">CHOOSE PAYABLE BY</option>
                                                    @if(!empty($partiesData))
                                                        @foreach($partiesData as $k =>$row)   
                                                            <option value="{{$row->id}}" 
                                                                    {{($row->id==(isset($party_id)?$party_id:0))?'selected':''}}
                                                            >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>With GST <span style="color:red">*</span></label>
                                                <select name="with_gst" id="with_gst" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE OPTION</option>
                                                    <option value="Yes" @selected('Yes'==(isset($with_gst)?$with_gst:'')) >Yes</option>
                                                    <option value="No" @selected('No'==(isset($with_gst)?$with_gst:''))>No</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label>Company <span style="color:red">*</span> </label>
                                                <select name="company_id" id="company_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE COMPANY</option>
                                                    @if(!empty($companiesData))
                                                        @foreach($companiesData as $k =>$row)   
                                                            <option value="{{$row->id}}" 
                                                                    {{($row->id==(isset($company_id)?$company_id:0))?'selected':''}}
                                                            >
                                                                {{$row->company_name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Trip From Date</label>
                                                <input type="date" class="form-control" id="trip_from_date" value="{{date('Y-m-01')}}" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Trip To Date</label>
                                                <input type="date" class="form-control" id="trip_to_date" value="{{date('Y-m-t')}}" >
                                            </div>
                                            <div class="form-group col-md-2" style="margin-top:30px;">
                                                <button class="btn btn-primary btn-sm"
                                                         onclick="fetchTrips('event');"
                                                >Fetch Trips</button>   
                                            </div>
                                            <div class="form-group col-md-12" style="color:red" id="fetchTripsError"></div>
                                        </div>
                                         <div class="form-row">
                                            <div class="col-md-12" style="overflow-x: auto;">
                                                <table class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="check" id="checkAll" onchange="calcSelectedTripFreight()"></th>
                                                            <th>Lr No./Date</th>
                                                            <th>Vehicle</th>
                                                            <th>Route/Product</th>
                                                            <th>Freight</th>
                                                            <th>Detention</th>
                                                            <th>Detention Days</th>
                                                            <th>Reporting DateTime</th>
                                                            <th>Unload DateTime</th>
                                                            <th>Trip Advanced</th>
                                                            <th>Payable Amount</th>
                                                            <th>Driver Shortage</th>
                                                            <th>Driver Shortage Amount</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody id="tableTripData"></tbody>   
                                                    <tfoot>
                                                        <tr>
                                                            <th style="text-align:right;">Total</th>
                                                            <th colspan="4" style="text-align:right;" id="total_freight_amt"></th>
                                                            <th id="total_detention"></th>
                                                            <th ></th>
                                                            <th ></th>
                                                            <th ></th>
                                                            <th ></th>
                                                            <th id="total_freight"></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                         </div>  
                                        <div class="form-row"> 
                                            <div class="col-md-12 mt-5" > 
                                                <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                                <a href="{{route('bill.list')}}"  class="btn btn-danger">CANCEL</a>
                                            </div>
                                        </div>

                                    </form>
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
</div>

<script type="text/javascript">
    $(document).ready(function(){
       getPartyTrips(); 
       calcSelectedTripFreight();
    });
    
    function fetchTrips(e){
        event.preventDefault();
        getPartyTrips();
    }

    function getPartyTrips(){
        var party_id = $("#party_id").val();
        var company_id = $("#company_id").val();
        var trip_from_date = $("#trip_from_date").val();
        var trip_to_date = $("#trip_to_date").val();
        var bill_id = $("#bill_id").val();
        if(company_id==''){
          $("#fetchTripsError").html('(*) field required for fetch trips');
          return false;
        }else{
          $("#fetchTripsError").html('');
        }


        $.ajax({
            type: "POST",
            dataType: "json",
            url: '{{ route('get.party.wise.trips') }}',
            data: {"_token": "{{ csrf_token() }}",
            'party_id': party_id,
            'company_id': company_id,
            'from_date':trip_from_date,
            'to_date':trip_to_date,
            'bill_id':bill_id,
            },
            dataType:"json",      
            success: function (response) {
                var html='';
                var totalFreight=0;
                var totalDetention=0;
                var totalFreightAmt=0;

                if(response.length>0){
                    $("#checkAll").attr("checked",false);

                    $.each(response,function(k,row){
                        var temp_total=(parseFloat(row['freight'])+parseFloat(row['detention']));
                        var detention_total=(parseFloat(row['detention']));
                        var freight_total=(parseFloat(row['freight']));

                        var unload_datetime=(row['unload_datetime']!=null)?row['unload_datetime']:'';
                        var reporting_datetime=(row['reporting_datetime']!=null)?row['reporting_datetime']:'';
                        
                        var payableamount = (temp_total - parseFloat(row['party_advance']));
                        var ischeckbox = row['bill_id'] > 0 ? 'checked' : '';
                        if (ischeckbox !== '') {
                            totalFreight += payableamount;
                            totalDetention += detention_total;
                            totalFreightAmt += freight_total;
                        }

                        html+='<tr>';
                        html+='<td>';
                        html+='<input type="checkbox" name="tp_id[]" class="check" id="tp_id-'+k+'" value="'+row['id']+'" '+ischeckbox+' trip_freight="'+payableamount+'" trip_detention="'+detention_total+'" trip_freight_amt="'+freight_total+'" onchange="calcSelectedTripFreight();"/>';
                        html+='</td>';

                        html+='<td>'+row['lr_no']+' - '+ymdtodmy(row['lr_date'])+'</td>';
                        html+='<td>'+row['vehicle_no']+'</td>';
                        html+='<td>'+row['route_name']+'</td>';
                        // html+='<td>'+row['freight']+'</td>';
                        html += '<td>';
                        html += '<input type="text" style="width:100px;" name="freight[]" id="freight-' + k + '" value="'+row['freight']+'" class="form-control decimal-only"  placeholder="Driver Shortage" onchange="calFrightTotal('+k+')" />';
                        html += '</td>';
                        // html+='<td>'+row['detention']+'</td>';
                        html += '<td>';
                        html += '<input type="text" style="width:100px;"  name="detention[]" id="detention-' + k + '" value="'+row['detention']+'" class="form-control decimal-only"  placeholder="Driver Shortage" onchange="calFrightTotal('+k+')"/>';
                        html += '</td>';
                        html+='<td>'+row['detention_days']+'</td>';
                        html+='<td>'+reporting_datetime+'</td>';
                        html+='<td>'+unload_datetime+'</td>';
                        html+='<td>'+row['party_advance']+'</td>';
                        html+='<td>'+payableamount+'</td>';
                        
                        html += '<td>';
                        html += '<input type="text" style="width:100px;" name="driver_shortage[]" id="driver_shortage-' + k + '" value="'+row['driver_shortage']+'" class="form-control decimal-only"  placeholder="Driver Shortage"/>';
                        html += '</td>';

                        html += '<td>';
                        html += '<input type="text" style="width:100px;" name="driver_shortage_amt[]" id="driver_shortage_amt-' + k + '" value="'+row['driver_shortage_amt']+'"class="form-control numbers-only"  placeholder="Driver Shortage Amount"/>';
                        html += '</td>';

                        html+='</tr>';

                    });
                }else{
                    html+='<tr>';
                    html+='<td colspan="13" align="center">';
                    html+='TRIPS NOT FOUND';
                    html+='</td>';
                    html+='</tr>';
                }
                
                $("#total_amount").val(totalFreight.toFixed(2));
                $("#total_freight").html(totalFreight.toFixed(2));
                $("#total_detention").html(totalDetention.toFixed(2));
                $("#total_freight_amt").html(totalFreightAmt.toFixed(2));
                $("#tableTripData").html(html);
            }
        });
    }

    function calcSelectedTripFreight(){
        var checkedTpAmts=[];
        var checkedTpDet=[];
        var checkedTpFreight=[];
          
        $("input[name='tp_id[]']:checked").each(function (){
            checkedTpAmts.push(parseFloat($(this).attr("trip_freight")));
            checkedTpDet.push(parseFloat($(this).attr("trip_detention")));
            checkedTpFreight.push(parseFloat($(this).attr("trip_freight_amt")));
        });

        var totalTPAmt=0;
        totalTPAmt=checkedTpAmts.reduce((a, b) => a + b, 0);
        totalTPdet=checkedTpDet.reduce((a, b) => a + b, 0);
        totalTpFreight=checkedTpFreight.reduce((a, b) => a + b, 0);

        $("#total_amount").val(totalTPAmt.toFixed(2));
        $("#total_freight").html(totalTPAmt.toFixed(2));
        $("#total_freight_amt").html(totalTpFreight.toFixed(2));
        $("#total_detention").html(totalTPdet.toFixed(2));

    }

    function calFrightTotal(index){
        var freight = ($("#freight-" + index).val() != '') ? parseFloat($("#freight-" + index).val()) : 0;
        var detention = ($("#detention-" + index).val() != '') ? parseFloat($("#detention-" + index).val()) : 0;
    
        
        // Update the freight and detention values for the specific row
        $('#tp_id-'+index).attr('trip_freight_amt', freight);
        $('#tp_id-'+index).attr('trip_detention', detention);

        // Recalculate the total freight and detention
        calcSelectedTripFreight();

        // Returning false prevents the default form submission, but it seems unnecessary here
        // return false;
    }

    $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      if(this.checked==true){
        $(".check").attr("checked",true);
      }else{
        $(".check").attr("checked",false);
      }

    });

</script>
@endsection