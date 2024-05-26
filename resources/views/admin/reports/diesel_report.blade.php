@extends('layouts.app') 
@section('title',$title)
@section('content')
<style type="text/css">
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
                                                <h4>Diesel Report</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{--
                                            <div class="page-header-breadcrumb">
                                                <a href="" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>Vehicle No</label>
                                            <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width:100%">
                                                <option value=""> CHOOSE VEHICLE </option>
                                                @if(!empty($vehiclesData)) @foreach($vehiclesData as $k =>$row)
                                                <option value="{{$row->id}}"> {{$row->registration_no}} </option>
                                                @endforeach @endif
                                            </select>
                                            <span class="error" id="vehicle_id_error"></span>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="from_dt">Trip From Date</label>
                                            <input type="date" class="form-control filter-input" id="from_dt" value="{{date('Y-m-01')}}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="to_dt">Trip To Date</label>
                                            <input type="date" class="form-control filter-input" id="to_dt" value="{{date('Y-m-t')}}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm Generate" 
                                                onclick="refreshData()"
                                            >
                                                Generate
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm Generate" 
                                               id="printData" value='Print'>
                                                Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block" id='printarea'>
                                    <table class="table table-striped table-bordered nowrap" width="100%">
                                        <tr>
                                            <td colspan="2" align="center" style="border:2px solid black;width:100%;" 
                                                id="td_vehicle_title">N/A</td>

                                        </tr>
                                        <tr>
                                            <td align="center" style="border-left:2px solid black; border-bottom:2px solid black;width:50%;">TRIP LTR</td>

                                            <td align="center" style="border-left:2px solid black; border-right:2px solid black;border-bottom:2px solid black;width:50%;">
                                                ACTUAL LTR
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-left:2px solid black; border-right:2px solid black;">
                                                <table width="100%" style="border:1px solid black;" id="tripBlockHtml">
                                                    <tr>
                                                        <td>DATE</td>
                                                        <td>FROM</td>
                                                        <td>TO</td>
                                                        <td>LOADING QTY.</td>
                                                        <td>KM</td>
                                                        <td>AVRAGE</td>
                                                        <td>DIESEL IN LTR</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td>01-04-2024</td>
                                                        <td>KANDLA</td>
                                                        <td>MUNDRA</td>
                                                        <td>530</td>
                                                        <td>3.2</td>
                                                        <td>165.625</td>
                                                    </tr> --}}
                                                </table>
                                            </td>
                                            <td style="border-left:2px solid black; border-right:2px solid black;">
                                                <table width="100%" style="border:1px solid black" 
                                                    id="tripVochrBlockHtml">
                                                    <tr>
                                                        <td>DIESEL IN LTR</td>
                                                        <td>NARATION(PUMP NAME)</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td>3.2</td>
                                                        <td>GAYATRI</td>
                                                    </tr> --}}
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" style="border-left:2px solid black; border-right:2px solid black;border-bottom:2px solid black;">
                                                Balance
                                            </td>
                                            <td id="balance_total" style="border-left:2px solid black; border-right:2px solid black;border-bottom:2px solid black;">0</td>
                                        </tr>
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

<script>
    $(document).ready(function () {
        // refreshData();

    });

    function refreshData() {
        var vehicle_id = $('#vehicle_id').val();
        var fromDate = $('#from_dt').val();
        var toDate = $('#to_dt').val();

        if(vehicle_id==''){
            $("#vehicle_id_error").html('field is required');
            return false;
        }

        var vehicle_no = $('option:selected','#vehicle_id').text();
        $("#td_vehicle_title").html(vehicle_no);
        
        $.ajax({
            type: 'POST',
            url: "{{route('report.diesel.data')}}", 
            data: {
             "_token": "{{ csrf_token() }}",
             vehicle_id: vehicle_id,
             from_date: fromDate,
             to_date: toDate
         },
         success: function (data) {
            var data = JSON.parse(data);
            var tripHtml='';
            
            tripHtml+='<tr>';
            tripHtml+='<td>DATE</td>';
            tripHtml+='<td>FROM</td>';
            tripHtml+='<td>TO</td>';
            tripHtml+='<td>LOADING QTY.</td>';
            tripHtml+='<td>KM</td>';
            tripHtml+='<td>AVRAGE</td>';
            tripHtml+='<td>DIESEL IN LTR</td>';
            tripHtml+='</tr>';
            var total_diesel_ltr=0;
            $.each(data.tripData,function(key,row){

                var vehicle_km=(row['km']!='')?parseFloat(row['km']):0;
                var vehicle_avg=(row['vehicle_avg']!='')?parseFloat(row['vehicle_avg']):0;
                var diesel_in_ltr=(vehicle_km>0 && vehicle_avg>0)?(vehicle_km/vehicle_avg).toFixed(2):0;
                

                total_diesel_ltr+=parseFloat(diesel_in_ltr);

                tripHtml+='<tr>';
                tripHtml+='<td>'+ymdtodmy(row['lr_date'])+'</td>';
                tripHtml+='<td>'+row['from_station']+'</td>';
                tripHtml+='<td>'+row['to_station']+'</td>';
                tripHtml+='<td>'+row['net_weight']+'</td>';
                tripHtml+='<td>'+vehicle_km+'</td>';
                tripHtml+='<td>'+vehicle_avg+'</td>';
                tripHtml+='<td>'+diesel_in_ltr+'</td>';
                tripHtml+='</tr>';

            });

            tripHtml+='<tr>';
            tripHtml+='<td colspan="6">Total</td>';
            tripHtml+='<td>'+total_diesel_ltr+'</td>';
            tripHtml+='</tr>';

            $("#tripBlockHtml").html(tripHtml);

            var tripVoucherHtml='';
            
            tripVoucherHtml+='<tr>';
            tripVoucherHtml+='<td>DIESEL IN LTR</td>';
            tripVoucherHtml+='<td>NARATION(PUMP NAME)</td>';
            tripVoucherHtml+='</tr>';
            var totalFilledDiesel=0;
            $.each(data.tripVoucherData,function(key,row){
                var fuel_qty=(row['km']!='')?parseFloat(row['fuel_qty']):0;
                totalFilledDiesel+=(parseFloat(fuel_qty));

                var fuel_station=(row['fuel_station']!='null')?row['fuel_station']:'N/A';
                if(row['fuel_station']!==null){
                    tripVoucherHtml+='<tr>';
                    tripVoucherHtml+='<td align="right">'+fuel_qty+'</td>';
                    if(row['remarks']!=null){
                        tripVoucherHtml+='<td align="right">'+row['remarks']+' ('+fuel_station+')</td>';
                    }else{
                        tripVoucherHtml+='<td align="right">'+fuel_station+'</td>';
                    }
                    tripVoucherHtml+='</tr>';
                }
                
            });

            tripVoucherHtml+='<tr>';
            tripVoucherHtml+='<td align="right">'+totalFilledDiesel+'</td>';
            tripVoucherHtml+='<td>Total</td>';
            tripVoucherHtml+='</tr>';


            $("#tripVochrBlockHtml").html(tripVoucherHtml);

            var balance_total=(parseFloat(total_diesel_ltr)-parseFloat(totalFilledDiesel));
            $("#balance_total").html(balance_total);

        },
        error: function (error) {
            console.log(error);
        }
    });
    }

    $("#printData").click(function () {
        var vehicle_id = $('#vehicle_id').val();
        var fromDate = $('#from_dt').val();
        var toDate = $('#to_dt').val();

        $("#vehicle_id_error").html('');
        if(vehicle_id==''){
            $("#vehicle_id_error").html('field is required');
            return false;
        }

        var url='{{route('report.diesel.print')}}?vehicle_id='+vehicle_id+'&fromDate='+fromDate+'&toDate='+toDate;
        //window.location.href=url;
        $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
    });
</script>



@endsection