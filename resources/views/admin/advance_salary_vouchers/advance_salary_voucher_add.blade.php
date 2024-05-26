@extends('layouts.app')

@section('title',(isset($editData)) ?'Edit Adv. Salary Voucher':'Add Adv. Salary Voucher')
@section('content')

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
                                                <a href="{{route('salary.voucher.list')}}"><h4>{{$title}}</h4></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                               {{--  @if(isset($editData['id']))
                                                    <a class="btn waves-effect waves-light btn-warning float-right ml-1" onclick="printLr({{$editData['id']}})"><i class="feather icon-printer" style="color: white;"></i></a>
                                                    <a href="{{route('transport.trip.add')}}" class="btn waves-effect waves-light btn-primary float-right "><i class="icofont icofont-plus"></i>Add New </a>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('advance.salary.voucher.update',base64_encode($editData->id)) : route('advance.salary.voucher.store')}}" novalidate="" enctype="multipart/form-data">
                                        <?php
                                         $selectedDriverID=((isset($editData->driver_id))?$editData->driver_id:0);
                                         $selectedDriverName=((isset($editData->getDriverDetail))?$editData->getDriverDetail->name:'');
                                         $selectedDriverTotalCredit=((isset($editData->getDriverDetail))?$editData->getDriverDetail->total_credit:0);
                                         
                                         $selectedVehicleNo=(isset($editData->getVehicleDetail))?$editData->getVehicleDetail->registration_no:'';
                                        ?>
                                        @csrf
                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label>Branch</label>
                                                <select name="branch" id="branch" class="form-control select2" required>
                                                    <option value="Mundra" {{('Mundra'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Mundra</option>
                                                    <option value="Gandhidham" {{('Gandhidham'==(isset($editData->branch)?$editData->branch:''))?'selected':''}}>Gandhidham</option>
                                                </select>
                                            </div>
                                           {{--  <div class="form-group col-md-2">
                                                <label for="salary_voucher_from_date">From date</label>
                                                <input type="date" class="form-control" name="salary_voucher_from_date" id="salary_voucher_from_date" 
                                                       value="{{(isset($salary_voucher_from_date) && $salary_voucher_from_date!=null )?$salary_voucher_from_date:date('Y-m-01')}}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="salary_voucher_to_date">To date</label>
                                                <input type="date" class="form-control" name="salary_voucher_to_date" id="salary_voucher_to_date" 
                                                        value="{{(isset($salary_voucher_to_date) && $salary_voucher_to_date!=null )?$salary_voucher_to_date:date('Y-m-t')}}" onchange="getVehicleData()">
                                            </div> --}}

                                            <div class="form-group col-md-2">
                                                <label>Driver</label>
                                                {{-- <select name="vehicle_id" id="vehicle_id" class="form-control select2" required onchange="getVehicleData()">
                                                <option value="" transporter_party_id ="" transporter_party_name ="" driver_id ="" driver_name ="" vehicle_type='' >CHOOSE VEHICLE</option>
                                                     @if(!empty($Vehicles))
                                                     @foreach($Vehicles as $k =>$singledata)   
                                                     <option value="{{$singledata->id}}" 
                                                        {{($singledata->id==$selectedVehicleID)?'selected':''}}
                                                        transporter_party_id="{{$singledata->transporter_party_id}}"
                                                        transporter_party_name="{{$singledata->transporter_party_name}}"
                                                        driver_id="{{$singledata->driver_id}}"
                                                        driver_name="{{$singledata->driver_name}}"
                                                     >
                                                        {{$singledata->registration_no}}
                                                      </option>
                                                     @endforeach
                                                     @endif
                                                </select> --}}
                                                <select name="driver_id" id="driver_id" class="form-control select2" required onchange="getVehicleData()">
                                                <option value="" driver_name="" vehicle_id ="" vehicle_no ="" >CHOOSE DRIVER</option>
                                                     @if(!empty($drivers))
                                                     @foreach($drivers as $k =>$singledata)   
                                                     <option value="{{$singledata->id}}" 
                                                        {{($singledata->id==$selectedDriverID)?'selected':''}}
                                                        vehicle_id="{{(isset($singledata->getLastestVehicle->getVehicleDetail))?$singledata->getLastestVehicle->getVehicleDetail->id:''}}"
                                                        vehicle_no="{{(isset($singledata->getLastestVehicle->getVehicleDetail))?$singledata->getLastestVehicle->getVehicleDetail->registration_no:''}}"
                                                        driver_name="{{$singledata->name}}"
                                                        driver_total_credit="{{$singledata->total_credit}}"
                                                     >
                                                        {{$singledata->name}}
                                                      </option>
                                                     @endforeach
                                                     @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Vehicle No</label>
                                                <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{(isset($editData->vehicle_id))?$editData->vehicle_id:''}}">
                                                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control"  value="{{(isset($selectedVehicleNo))?$selectedVehicleNo:''}}" readonly placeholder="Vehicle No">
                                            </div>
                                             <div class="form-group col-md-2 owner_lr_block">
                                                <label>Driver Total Credit</label>
                                                <input type="text" name="driver_total_credit" id="driver_total_credit" class="form-control"  value="{{(isset($selectedDriverTotalCredit))?$selectedDriverTotalCredit:0}}" readonly >
                                            </div>
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Salary Amount</label>
                                                <input type="text" name="salary_amount" class="form-control decimal" id="salary_amount" value="{{(isset($editData->salary_amount))?$editData->salary_amount:''}}" placeholder="Salary Amount" required>
                                            </div>
                                            

                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Payment Type</label>
                                                <select name="payment_type" id="payment_type" class="form-control select2" required>
                                                    <option value="">Choose Payment Type</option>
                                                    <option value="Cash" {{'Cash'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Cash</option>
                                                    <option value="Card" {{'Card'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Card</option>
                                                    <option value="Bank" {{'Bank'==((isset($editData->payment_type))?$editData->payment_type:'')?'selected':''}}>Bank</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Salary Date</label>
                                                <input type="date" name="salary_voucher_date" class="form-control" id="salary_voucher_date" value="{{(isset($editData->salary_voucher_date))?$editData->salary_voucher_date:date('Y-m-d')}}" >
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control" value="{{(isset($editData->remarks))?$editData->remarks:''}}" placeholder="Remarks">{{(isset($editData->remarks))?$editData->remarks:''}}</textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('advance.salary.voucher.list')}}"  class="btn btn-danger">CANCEL</a>

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

<script type="text/javascript">

    $('.number').keypress(function(event) {
        if (event.which != 46 && (event.which < 47 || event.which > 59)){
            event.preventDefault();
            if((event.which == 46) && ($(this).indexOf('.') != -1)) {
                event.preventDefault();
            }
        }
    });

    $('.decimal').keypress(function(evt){
        return (/^[0-9]*\.?[0-9]*$/).test($(this).val()+evt.key);
    });

    function preventZero($idparam){
        dPurchaseQty=$('#'+$idparam).val();
        if(dPurchaseQty==0){
          $('#'+$idparam).val('');
          return false;
        }
    }


    function getVehicleData() {
        var vehicle_id= $('#driver_id option:selected').attr("vehicle_id");   
        var vehicle_no= $('#driver_id option:selected').attr("vehicle_no");   
        var driver_name= $('#driver_id option:selected').attr("driver_name");   
        var driver_total_credit= $('#driver_id option:selected').attr("driver_total_credit");   
        //console.log(driver_total_credit);
        $("#vehicle_id").val(vehicle_id);
        $("#vehicle_no").val(vehicle_no);
        $("#driver_total_credit").val(driver_total_credit);
        $(".driver_name").val(driver_name);
       
    }


   
</script>
@endsection