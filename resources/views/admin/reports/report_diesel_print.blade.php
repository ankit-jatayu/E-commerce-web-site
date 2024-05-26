<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/theme/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <style type="text/css">
        .table td,th {
            padding:5px 21px 5px 5px !important;
            font-size:14px;
        }
    </style>
</head>
<body>
<table class="table table-striped" width="100%" border="1" style="border-collapse: collapse;">
    <tbody>
        <tr>
            <th colspan="8" style="text-align:center; border-top: 1px solid black; border-bottom: 1px solid black;">
                {{$vehicleData->registration_no}}
            </th>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center; width:50%; border-top: 1px solid black; border-bottom: 1px solid black;">TRIP LTR</td>
            <td colspan="2" style="text-align:center; width:50%; border-top: 1px solid black; border-bottom: 1px solid black;">ACTUAL LTR</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center; width:60%;">
               <table  width="100%" style="width:100%;border-collapse:collapse; border-top: 1px solid black;" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">DATE</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">FROM</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">TO</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">LOADING QTY.</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">KM</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">AVRAGE</td>
                    <td style=" border-bottom: 1px solid black; ">DIESEL IN LTR</td>
                </tr>
                @if(isset($tripData))
                <?php 
                 $total_diesel_ltr=0;
                ?>
                @foreach($tripData as $k => $row)
                <?php 
                    $vehicle_km  = ($row->km!='')?$row->km:0;
                    $vehicle_avg = ($row->vehicle_avg!='')?$row->vehicle_avg:0;
                    $diesel_in_ltr=($vehicle_km>0 && $vehicle_avg>0)?round(($vehicle_km/$vehicle_avg),2):0;
                    $total_diesel_ltr+=$diesel_in_ltr;

                ?>
                <tr>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{helperConvertYmdTodmY($row->lr_date)}}</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$row->from_station}}</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$row->to_station}}</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{$row->net_weight}}
                    </td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$vehicle_km}}</td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$vehicle_avg}}</td>
                    <td style=" border-bottom: 1px solid black; ">{{$diesel_in_ltr}}</td>
                </tr>
                @endforeach
                <tr>
                    <td align="right" colspan="6" style="border-top: 1px solid black;border-right: 1px solid black;  ">Total</td>
                    <td style="border-top: 1px solid black; ">{{$total_diesel_ltr}}</td>
                </tr>
                @endif
            </table>
            </td>
            <td colspan="2" style="text-align:center; width:40%;">
              <table  width="100%" style="border-top: 1px solid black; width:100%;border-collapse:collapse; " cellspacing="0" cellpadding="0"  >
                <tr>
                    <td style="border-right: 1px solid black;border-bottom: 1px solid black; ">DIESEL IN LTR</td>
                    <td style="border-bottom: 1px solid black;">NARATION(PUMP NAME)</td>
                </tr>
                @if(isset($tripVoucherData))
                <?php 
                 $totalFilledDiesel=0;
                ?>
                @foreach($tripVoucherData as $k => $row)
                <?php 
                $totalFilledDiesel+=$row->fuel_qty;
                ?>
                <tr>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black;">{{$row->fuel_qty}}</td>
                    @if($row->remarks!=null)
                        <td style="border-bottom: 1px solid black;">{{$row->remarks}} ({{$row->fuel_station}})</td>
                    @else
                        <td style="border-bottom: 1px solid black;">{{$row->fuel_station}}</td>
                    @endif
                </tr>
                @endforeach
                <tr>
                    <td style="border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; ">{{$totalFilledDiesel}}</td>
                    <td align="left" style="border-top: 1px solid black; border-bottom: 1px solid black;  " >Total</td>
                </tr>
                @endif
            </table>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:right; width:560%; border-top: 1px solid black;">Balance</td>
            <td colspan="2" style="text-align:left; width:50%; border-top: 1px solid black;">
                <?php 
                    $balance_total=($total_diesel_ltr-$totalFilledDiesel);
                    echo $balance_total;
                ?>
            </td>
        </tr>  
    </tbody>
</table>

<script type="text/javascript" src="{{ asset('admin/theme/bower_components/jquery/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        window.print();
    });
</script>                                   
</body>
</html>