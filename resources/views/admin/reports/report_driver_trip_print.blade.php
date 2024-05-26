<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/theme/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <style>
        @media print{@page {size: landscape}}
        .fnt-12{
            font-size:12px;
        }
        .fnt-bld{
            font-weight:bold;
        }
        .table td{
           padding: 0.40rem!important;
        }
    </style>
</head>
<body>
<table class="table table-striped" width="100%" border="1" style="border-collapse: collapse;">
    <tbody>
        <tr>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; ">SR</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >LR NO</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >DATE</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >T/L NO.</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >PRODUCT</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >N.W</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >FROM</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >TO</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >SHORTAGE</td>
            <td class="fnt-12 fnt-bld" style="border-right: 1px solid black; border-bottom: 1px solid black; " >DED</td>
            <td class="fnt-12 fnt-bld" style=" border-bottom: 1px solid black; ">AMT</td>    
          </tr>
            
            @if(isset($tripData) && count($tripData)>0)
                <?php 
                 $total_shortage_wgt=0;
                ?>
                @foreach($tripData as $k => $row)
                <?php 
                    $shortage_wgt  = ($row->shortage_weight!='')?$row->shortage_weight:0;
                    $total_shortage_wgt+=$shortage_wgt;
                ?>
                <tr>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$k+1}}</td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$row->lr_no}}</td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                            {{helperConvertYmdTodmY($row->lr_date)}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{($row->getSelectedVehicle)?$row->getSelectedVehicle->vehicle_no:''}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{($row->getSelectedProduct)?$row->getSelectedProduct->product:''}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{$row->net_weight}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{($row->getSelectedFromStation)?$row->getSelectedFromStation->from_station:''}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{($row->getSelectedToStation)?$row->getSelectedToStation->to_station:''}}
                    </td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">{{$shortage_wgt}}</td>
                    <td class="fnt-12" style="border-right: 1px solid black; border-bottom: 1px solid black; ">
                        {{$row->driver_shortage}}
                    </td>
                    <td class="fnt-12" style=" border-bottom: 1px solid black; ">
                             {{$row->driver_shortage_amt}}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="fnt-12" align="center" colspan="8" 
                        style="border-top: 1px solid black;border-right: 1px solid black;">
                        SHORTAGE AMT
                    </td>
                    <td class="fnt-12" style="border-top: 1px solid black; ">{{$total_shortage_wgt}}</td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                </tr>
                <tr>
                    <td class="fnt-12" align="center" colspan="8" 
                        style="border-top: 1px solid black;border-right: 1px solid black;">
                        26-1-24 JACK
                    </td>
                    <td class="fnt-12" style="border-top: 1px solid black; ">N/A</td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>

                </tr>
                <tr>
                    <td class="fnt-12" align="center" colspan="8" 
                        style="border-top: 1px solid black;border-right: 1px solid black;">
                        50 LTR DIESEL 29-3-24
                    </td>
                    <td class="fnt-12" style="border-top: 1px solid black; ">N/A</td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>

                </tr>
                <tr>
                    <td class="fnt-12" align="center" colspan="8" 
                        style="border-top: 1px solid black;border-right: 1px solid black;">
                        SALARY 14-10 TO 13-5-2024
                    </td>
                    <td class="fnt-12" style="border-top: 1px solid black; ">N/A</td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>

                </tr>
                <tr>
                    <td class="fnt-12" align="center" colspan="8" 
                        style="border-top: 1px solid black;border-right: 1px solid black;">
                        TOTAL
                    </td>
                    <td class="fnt-12" style="border-top: 1px solid black; ">N/A</td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                    <td class="fnt-12" style="border-top: 1px solid black; "></td>
                </tr>
            @else
            <tr>
                <td colspan="11" align="center">No Trips Found</td>
            </tr>
            @endif
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