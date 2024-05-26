@extends('layouts.app')
@section('title',(isset($editData))?'Edit Route Rate':'Add Route Rate')
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
                                                <a href="{{route('route.rate.list')}}"><h4>{{$title}}</h4></a>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('route.rate.update',base64_encode($editData->id)) : route('route.rate.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                       
                                         <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>Party</label>
                                                <select name="party_id" id="party_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE PARTY</option>
                                                    @if(!empty($parties))
                                                        @foreach($parties as $k =>$row)   
                                                            <option value="{{$row->id}}" 
                                                                    {{($row->id==(isset($party_id)?$party_id:0))?'selected':''}}
                                                            >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Route</label>
                                                <select name="route_id" id="route_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE ROUTE</option>
                                                    @if(!empty($routes))
                                                        @foreach($routes as $k =>$row)   
                                                            <option value="{{$row->id}}" {{($row->id==(isset($route_id)?$route_id:0))?'selected':''}}>
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
                                            <div class="form-group col-md-3">
                                                <label>Factory Rate</label>
                                               <input type="text" name="rate" class="form-control decimal-only"  placeholder="Factory Rate" id="rate" value="{{(isset($rate))?$rate:''}}" required onchange="calcPTPK()">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Distance In KM</label>
                                               <input type="text" name="distance" class="form-control decimal-only"  placeholder="Distance In KM" id="distance" value="{{(isset($distance))?$distance:''}}" required onchange="calcPTPK()">
                                            </div>
                                            <div class="form-group col-md-3">
                                               <label>PTPK</label>
                                               <input type="text" name="ptpk" class="form-control decimal-only"  placeholder="PTPK" id="ptpk" value="{{(isset($ptpk))?$ptpk:''}}" required readonly>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Applicable Date</label>
                                                <input type="date" name="applicable_date" class="form-control" id="applicable_date" value="{{(isset($applicable_date))?$applicable_date:date('Y-m-d')}}" >
                                            </div>
                                            
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('route.rate.list')}}"  class="btn btn-danger">CANCEL</a>

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
    function calcPTPK(){
        var rate =($("#rate").val()!='')?parseFloat($("#rate").val()):0;
        var distance =($("#distance").val()!='')?parseFloat($("#distance").val()):0;
        var ptpk=(rate/distance);
        $("#ptpk").val(ptpk.toFixed(2));
    }
    
</script>
@endsection