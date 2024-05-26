@extends('layouts.app') 
@section('title','Driver Trips Report ')
@section('content')

<style type="text/css">
    .error{
        color:red;
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
                                                <h4>Driver Trips Report</h4>
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
                                            <label>Driver</label>
                                            <select name="driver_id" id="driver_id" class="form-control select2" 
                                                    style="width:100%;"
                                            >
                                                <option value=""> CHOOSE DRIVER </option>
                                                @if(!empty(helperGetTripUsedDrivers()))
                                                    @foreach(helperGetTripUsedDrivers() as $k =>$row)
                                                        <option value="{{$row->id}}"> {{$row->name}} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="error" id="driver_id_error"></span>
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
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block" id='printarea'>
                                   <form id="driver_trip_report_form">
                                       <table class="table table-striped table-bordered" width="100%" style="border:1px solid black;" id="tripBlockHtml">
                                            <tr>
                                                <td>SR</td>
                                                <td>LR NO</td>
                                                <td>DATE</td>
                                                <td>T/L NO.</td>
                                                <td>PRODUCT</td>
                                                <td>N.W</td>
                                                <td>FROM</td>
                                                <td>TO</td>
                                                <td>SHORTAGE</td>
                                                <td>DED</td>
                                                <td>AMT</td>
                                            </tr>
                                            <tr>
                                                <td align="center" colspan="11">SELECT DRIVER TO GET REPORT</td>
                                            </tr>
                                        </table>
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

<script>
    $(document).ready(function () {
        // refreshData();

    });

    function refreshData() {
        var driver_id = $('#driver_id').val();
        var fromDate = $('#from_dt').val();
        var toDate = $('#to_dt').val();

        if(driver_id==''){
            $("#driver_id_error").html('field is required');
            return false;
        }

        var vehicle_no = $('option:selected','#driver_id').text();
        $("#td_vehicle_title").html(vehicle_no);
        
        $.ajax({
            type: 'POST',
            url: "{{route('report.driver.trip.data')}}", 
            data: {
             "_token": "{{ csrf_token() }}",
             driver_id: driver_id,
             from_date: fromDate,
             to_date: toDate,
         },
         success: function (data) {
            var data = JSON.parse(data);
           
            var tripHtml='';
            
            tripHtml+='<tr>';
            tripHtml+='<td>SR</td>';
            tripHtml+='<td>LR NO</td>';
            tripHtml+='<td>DATE</td>';
            tripHtml+='<td>T/L NO.</td>';
            tripHtml+='<td>PRODUCT</td>';
            tripHtml+='<td>N.W</td>';
            tripHtml+='<td>FROM</td>';
            tripHtml+='<td>TO</td>';
            tripHtml+='<td>SHORTAGE</td>';
            tripHtml+='<td>DED</td>';
            tripHtml+='<td>AMT</td>';
            tripHtml+='</tr>';

            if(data.length>0){
                var total_shortage=0;
                $.each(data,function(key,row){
                    var shortage_wgt=(row['shortage_weight']!='')?parseFloat(row['shortage_weight']):0;
                    total_shortage+=shortage_wgt;
                    // var vehicle_km=(row['km']!='')?parseFloat(row['km']):0;
                    // var vehicle_avg=(row['vehicle_avg']!='')?parseFloat(row['vehicle_avg']):0;
                    // var diesel_in_ltr=(vehicle_km>0 && vehicle_avg>0)?(vehicle_km/vehicle_avg).toFixed(2):0;
                    

                    // total_diesel_ltr+=parseFloat(diesel_in_ltr);
                    var lr_no=(row['lr_no']!=null)?row['lr_no']:'';

                    var driver_shortage = (row['driver_shortage']>0)?row['driver_shortage']:''; 
                    var driver_shortage_amt = (row['driver_shortage_amt']>0)?row['driver_shortage_amt']:''; 

                    tripHtml+='<tr>';
                    tripHtml+='<td>'+(key+1)+'</td>';
                    tripHtml+='<td>'+lr_no+'</td>';
                    tripHtml+='<td>'+ymdtodmy(row['lr_date'])+'</td>';
                    tripHtml+='<td>'+row['get_selected_vehicle']['vehicle_no']+'</td>';
                    tripHtml+='<td>'+row['get_selected_product']['product']+'</td>';
                    tripHtml+='<td>'+row['net_weight']+'</td>';
                    tripHtml+='<td>'+row['get_selected_from_station']['from_station']+'</td>';
                    tripHtml+='<td>'+row['get_selected_to_station']['to_station']+'</td>';
                    tripHtml+='<td>'+shortage_wgt+'</td>';
                    tripHtml+='<td>';
                    tripHtml+='<input type="hidden" name="tripData['+key+'][trip_id]" value="'+row['id']+'">';
                    tripHtml+='<input type="text" class="form-control" id="ded-'+key+'" placeholder="DED" name="tripData['+key+'][driver_shortage]" id="driver_shortage-'+key+'" value="'+driver_shortage+''+'">';
                    tripHtml+='</td>';
                    tripHtml+='<td><input type="text" class="form-control decimal-only" id="ded_amt-'+key+'" placeholder="AMT" name="tripData['+key+'][driver_shortage_amt]" id="driver_shortage_amt-'+key+'" value="'+driver_shortage_amt+'">';
                    tripHtml+='</td>';
                    tripHtml+='</tr>';
                });

                tripHtml+='<tr>';
                tripHtml+='<td colspan="11">';
                tripHtml+='<button class="btn btn-warning Generate" id="printData">Generate & Print </button>';
                tripHtml+='</td>';
                tripHtml+='</tr>';
                
                tripHtml+='<tr>';
                tripHtml+='<td colspan="8" align="center">SHORTAGE AMT</td>';
                tripHtml+='<td>'+total_shortage+'</td>';
                tripHtml+='<td></td>';
                tripHtml+='<td></td>';
                tripHtml+='</tr>';
                
                tripHtml+='<tr>';
                tripHtml+='<td colspan="8" align="center">26-1-24 JACK</td>';
                tripHtml+='<td>N/A</td>';
                tripHtml+='<td></td>';
                tripHtml+='<td></td>';
                
                tripHtml+='</tr>';
                
                tripHtml+='<tr>';
                tripHtml+='<td colspan="8" align="center">50 LTR DIESEL 29-3-24</td>';
                tripHtml+='<td>N/A</td>';
                tripHtml+='<td></td>';
                tripHtml+='<td></td>';
                
                tripHtml+='</tr>';
                
                tripHtml+='<tr>';
                tripHtml+='<td colspan="8" align="center">SALARY 14-10 TO 13-5-2024</td>';
                tripHtml+='<td>N/A</td>';
                tripHtml+='<td></td>';
                tripHtml+='<td></td>';
                
                tripHtml+='</tr>';

                tripHtml+='<tr>';
                tripHtml+='<td colspan="8" align="center">TOTAL</td>';
                tripHtml+='<td>N/A</td>';
                tripHtml+='<td></td>';
                tripHtml+='<td></td>';
                
                tripHtml+='</tr>';
            }else{
                tripHtml+='<tr>';
                tripHtml+='<td colspan="11" align="center">No Trips Found</td>';
                tripHtml+='</tr>';
            }
            
            $("#tripBlockHtml").html(tripHtml);

        },
        error: function (error) {
            console.log(error);
        }
    });
    }

    

    $('body').on('click',"#tripBlockHtml #printData",function(e){
        event.preventDefault();
        var driver_id = $('#driver_id').val();
        var fromDate = $('#from_dt').val();
        var toDate = $('#to_dt').val();

        $("#driver_id_error").html('');
        if(driver_id==''){
            $("#driver_id_error").html('field is required');
            return false;
        }

        var formData=$("#driver_trip_report_form").serialize();
        $.ajax({
            type: 'POST',
            url: "{{route('report.driver.trip.update')}}", 
            data: {
             "_token": "{{ csrf_token() }}",
             formData: formData,
            },
            success: function (response) {
                var url='{{route('report.driver.trip.print')}}?driver_id='+driver_id+'&fromDate='+fromDate+'&toDate='+toDate;
                // window.location.href=url;
                $("<iframe class='printpage'>").hide().attr("src",url).appendTo("body");
            },
         });
    });

</script>



@endsection