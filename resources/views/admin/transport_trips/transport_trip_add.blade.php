@extends('layouts.app')
@section('title',(isset($editData))?'Edit Trip':'Add Trip')
@section('content')
<?php 
if(isset($editData) && !empty($editData)){
    extract($editData->toArray());
   
}

?>

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
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('transport.trip.update',base64_encode($editData->id)) : route('transport.trip.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="trip_id" id="trip_id" value="{{(isset($editData->id))?$editData->id:''}}">
                                         
                                         <div class="row">
                                              {{-- <div class="col-sm-3 col-lg-3">
                                                <label>Test Field</label>
                                                <div class="input-group">
                                                <select class="form-control select2" >
                                                    <option value="">Choose Test1</option>
                                                    <option value="">Choose Test2</option>
                                                </select>
                                                <span class="input-group-text addon-style">
                                                    <i class="fa fa-plus-circle plus-add"></i>
                                                </span>
                                                </div>
                                                </div> --}}
                                              <div class="form-group col-md-2">
                                                <div class="checkbox-fade fade-in-primary">
                                                    <label class="form-label" for="is_market_lr" style="cursor:pointer;">
                                                        <input type="checkbox" id="is_market_lr" name="is_market_lr" value="1" @checked((isset($is_market_lr) && $is_market_lr=='1')) onchange="showHideMarketLrBlock()">
                                                        <span class="cr">
                                                            <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                        </span>
                                                        <span>Market Vehicle</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                  <span class="messages"></span>
                                            </div>
                                         </div> 
                                           
                                         <div class="row">
                                            <div class="form-group col-md-2">
                                                <label class="required">Company</label>
                                                <select name="company_id" id="company_id"  class="form-control select2"        style="width:100%" required 
                                                >
                                                    <option value=""> CHOOSE COMPANY </option>
                                                    @if(!empty($companySettingsData))
                                                    @foreach($companySettingsData as $k =>$row)   
                                                        <option value="{{$row->id}}"
                                                          @selected(($row->id==(isset($company_id)?$company_id:0)))
                                                        >
                                                           {{$row->company_name}}
                                                        </option>       
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>LR No.</label>
                                                <input type="text" name="lr_no" class="form-control" id="lr_no" value="{{(isset($lr_no))?$lr_no:''}}" placeholder="LR No.">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>LR. Date</label>
                                                <input type="date" name="lr_date" class="form-control" id="lr_date" value="{{(isset($lr_date))?$lr_date:date('Y-m-d')}}" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Trip Type</label>
                                                <select name="trip_type" id="trip_type" 
                                                        class="form-control select2" style="width:100%">
                                                    <option value="Empty Trip" 
                                                        @selected('Empty Trip'==($editData->trip_type ?? ''))
                                                    >
                                                        Empty Trip
                                                    </option>
                                                    <option value="Loaded Trip" 
                                                        @selected('Loaded Trip'==($editData->trip_type ?? ''))
                                                    >
                                                        Loaded Trip
                                                    </option>
                                                    <option value="Loaded/Empty Trip" 
                                                        @selected('Loaded/Empty Trip'==($editData->trip_type ?? ''))
                                                    >
                                                            Loaded/Empty Trip
                                                    </option>
                                                </select>
                                            </div>
                                            </div>
                                            <div class="row">
                                             <div class="form-group col-md-2 owner_lr_block">
                                                <label class="required">Vehicle No.</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#vehicle-add-modal" data-backdrop="static" data-keyboard="false"></span> --}}
                                                <div class="input-group">
                                                <select name="vehicle_id" id="vehicle_id"
                                                        class="form-control owner_lr_block_input select2"
                                                        required onchange="getVehicleData()"
                                                        style="width:100%;" 
                                                >
                                                    <option value="" transporter_party_id ="" 
                                                            transporter_party_name ="" 
                                                            driver_id =""
                                                            driver_name =""
                                                    >
                                                        CHOOSE VEHICLE
                                                    </option>
                                                    @if(!empty($vehicles))
                                                    @foreach($vehicles as $k =>$row)   
                                                    <option value="{{$row->id}}" 
                                                    @selected(($row->id==(isset($vehicle_id)?$vehicle_id:0)))
                                                        transporter_party_id="{{$row->transporter_party_id}}"
                                                        transporter_party_name="{{$row->transporter_party_name}}"
                                                        driver_id="{{$row->driver_id}}"
                                                        driver_name="{{$row->driver_name}}"
                                                        >
                                                        {{$row->registration_no}}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="input-group-text addon-style" data-toggle="modal" data-target="#vehicle-add-modal" data-backdrop="static" data-keyboard="false">
                                                    <i class="fa fa-plus-circle plus-add"></i>
                                                </span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label class="required">Driver</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#driver-modal" data-backdrop="static" data-keyboard="false"></span> --}}
                                                <div class="input-group">
                                                   <select name="driver_id" id="driver_id" style="width:100%"
                                                           class="form-control select2 owner_lr_block_input" required>
                                                    <option value="" driver_mobile_no="">CHOOSE DRIVER</option>
                                                        @if(!empty($drivers))
                                                            @foreach($drivers as $k =>$row)   
                                                                <option value="{{$row->id}}" 
                                                                    @selected(($row->id==(isset($driver_id)?$driver_id:'')))
                                                                    driver_mobile_no="{{$row->contact}}"
                                                                 >
                                                                {{$row->name}}
                                                              </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" data-toggle="modal" data-target="#driver-modal" data-backdrop="static" data-keyboard="false">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2 owner_lr_block">
                                                <label>Driver Mobile No</label>
                                                <input type="text" name="driver_mobile_no" 
                                                       id="driver_mobile_no" class="form-control"
                                                       placeholder="Driver Mobile No." readonly
                                                >
                                            </div>

                                            <div class="form-group col-md-2 market_lr_block">
                                                <label>Transporter</label>
                                                <select name="market_transporter_id" id="market_transporter_id" class="form-control market_lr_block_input select2" style="width:100%">
                                                    <option value="" tds_per="">CHOOSE TRANSPORTER</option>
                                                    @if(!empty($transporterParties))
                                                    @foreach($transporterParties as $k =>$singledata)   
                                                    <option value="{{$singledata->id}}" 
                                                        tds_per="{{$singledata->tds_per}}"
                                                        {{($singledata->id==(isset($transporter_id)?$transporter_id:0))?'selected':''}}>
                                                        {{$singledata->name}}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <div class="form-group col-md-2 market_lr_block" style="display:none">
                                                <label>Vehicle No</label>
                                                <input type="text" name="market_vehicle_no" class="form-control market_lr_block_input" id="market_vehicle_no" placeholder="Vehicle No"  style="text-transform:uppercase;"  
                                                value="{{(isset($editData->getSelectedVehicle))?$editData->getSelectedVehicle->registration_no:''}}"
                                                >
                                                <label class="invalid-feedback" id="market_vehicle_running_err" style="display: block;"></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="product_id" class="required">Product</label>
                                                 {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"   data-toggle="modal" data-target="#product-modal" data-backdrop="static" data-keyboard="false"></span> --}}
                                                 <div class="input-group">
                                                    <select name="product_id" id="product_id" 
                                                            class="form-control select2" style="width: 100%;" required>
                                                        <option value="">CHOOSE PRODUCT</option>       
                                                        @if(!empty($products))
                                                        @foreach($products as $k =>$row)   
                                                            <option value="{{$row->id}}"
                                                                @selected(($row->id==(isset($product_id)?$product_id:0)))
                                                            >
                                                               {{$row->name}}
                                                            </option>       
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" data-toggle="modal" data-target="#product-modal" data-backdrop="static" data-keyboard="false">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="required">Consignor</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPartyModal('consignor_id')"></span> --}}
                                                <div class="input-group">
                                                    <select name="consignor_id" id="consignor_id" 
                                                       class="form-control select2" style="width:100%;" required 
                                                    >
                                                    <option value="" >CHOOSE CONSIGNOR</option>
                                                        @if(!empty($consignorParties))
                                                            @foreach($consignorParties as $k =>$row)   
                                                                <option value="{{$row->id}}" 
                                                            @selected(($row->id==(isset($consignor_id)?$consignor_id:'')))
                                                                 >
                                                                {{$row->name}}
                                                              </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                   <span class="input-group-text addon-style" onclick="openPartyModal('consignor_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div> 
                                            </div> 

                                            <div class="form-group col-md-2">
                                                <label class="required">Consignee</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPartyModal('consignee_id')"></span> --}}
                                                <div class="input-group">
                                                    <select name="consignee_id" id="consignee_id" 
                                                            class="form-control select2" style="width:100%;" required>
                                                    <option value="" >CHOOSE CONSIGNEE</option>
                                                        @if(!empty($consigneeParties))
                                                            @foreach($consigneeParties as $k =>$row)   
                                                                <option value="{{$row->id}}" 
                                                            @selected(($row->id==(isset($consignee_id)?$consignee_id:'')))
                                                                 >
                                                                {{$row->name}}
                                                              </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" onclick="openPartyModal('consignee_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="payable_by" class="required">Payable by</label>
                                                <select name="payable_by" id="payable_by" class="form-control select2" 
                                                        style="width:100%;" required onchange="getPayableBy()" required>
                                                    <option value="">CHOOSE PAYABLE BY</option>       
                                                    <option value="CONSIGNOR" @selected(('CONSIGNOR'==(isset($payable_by)?$payable_by:''))) > CONSIGNOR </option>
                                                    <option value="CONSIGNEE" @selected(('CONSIGNEE'==(isset($payable_by)?$payable_by:''))) > CONSIGNEE </option> 
                                                    <option value="OTHER" @selected(('OTHER'==(isset($payable_by)?$payable_by:''))) > OTHER </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="required">Payable Party</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPartyModal('payable_party_id')"></span> --}}
                                                <div class="input-group">
                                                    <select id="payable_party_id_disp" 
                                                        class="form-control select2" style="width:100%;" required >
                                                        <option value="" >CHOOSE PAYABLE PARTY</option>
                                                        @if(!empty($payableParties))
                                                            @foreach($payableParties as $k =>$row)   
                                                                <option value="{{$row->id}}" 
                                                            @selected(($row->id==(isset($payable_party_id)?$payable_party_id:'')))
                                                                 >
                                                                {{$row->name}}
                                                              </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" onclick="openPartyModal('payable_party_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="payable_party_id" id="payable_party_id"
                                                    value="{{$editData->payable_party_id ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="from_station_id" class="required">From Station</label>
                                                {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPlaceLocationModal('from_station_id')"></span> --}}
                                                <div class="input-group">
                                                    <select name="from_station_id" id="from_station_id" 
                                                            class="form-control select2" style="width:100%;" required>
                                                        <option value="">CHOOSE FROM STATION</option>       
                                                        @if(!empty($locations))
                                                        @foreach($locations as $k =>$row)   
                                                            <option value="{{$row->id}}"
                                                                    @selected(($row->id==(isset($from_station_id)?$from_station_id:0)))
                                                            >
                                                               {{$row->name}}
                                                            </option>       
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" onclick="openPlaceLocationModal('from_station_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="to_station_id" class="required">To Station</label>
                                                 {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPlaceLocationModal('to_station_id')"></span> --}}
                                                <div class="input-group">
                                                    <select name="to_station_id" id="to_station_id" 
                                                            class="form-control select2" style="width: 100%;" required>
                                                        <option value="">CHOOSE TO STATION</option>       
                                                        @if(!empty($locations))
                                                        @foreach($locations as $k =>$row)   
                                                            <option value="{{$row->id}}"
                                                              @selected(($row->id==(isset($to_station_id)?$to_station_id:0)))
                                                            >
                                                               {{$row->name}}
                                                            </option>       
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" onclick="openPlaceLocationModal('to_station_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="back_to_station_id">Back To Station</label>
                                                 {{-- <span class="fa fa-plus" style="margin-left: 5px; color:#0E86D4;"  onclick="openPlaceLocationModal('back_to_station_id')"></span> --}}
                                                <div class="input-group">
                                                    <select name="back_to_station_id" id="back_to_station_id" 
                                                            class="form-control select2" style="width: 100%;">
                                                        <option value="">CHOOSE BACK TO STATION</option>       
                                                        @if(!empty($locations))
                                                        @foreach($locations as $k =>$row)   
                                                            <option value="{{$row->id}}"
                                                              @selected(($row->id==(isset($back_to_station_id)?$back_to_station_id:0)))
                                                            >
                                                               {{$row->name}}
                                                            </option>       
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group-text addon-style" onclick="openPlaceLocationModal('back_to_station_id')">
                                                        <i class="fa fa-plus-circle plus-add"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="form-group col-md-2">
                                                <label class="required">Vehicle AVG.</label>
                                                <input type="text" name="vehicle_avg" 
                                                       id="vehicle_avg" class="form-control decimal-only prevent-zero"
                                                       placeholder="Vehicle AVG."
                                                       value="{{$editData->vehicle_avg ?? ''}}"
                                                       required 
                                                >
                                            </div>
                                             <div class="form-group col-md-2">
                                                <label class="required">Vehicle KM</label>
                                                <input type="text" name="km" 
                                                       id="km" class="form-control decimal-only prevent-zero"
                                                       placeholder="Vehicle KM"
                                                       value="{{$editData->km ?? ''}}"
                                                       required
                                                >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label>Freight Rate</label>
                                                <input type="text" name="freight_rate" id="freight_rate"
                                                   class="form-control decimal-only" placeholder="Freight Rate"
                                                   value="{{$editData->freight_rate ?? ''}}" 
                                                >
                                            </div>
                                            <div class="form-group col-md-2 market_lr_block" style="display:none">
                                                <label>Market Freight</label>
                                                <input type="text" name="market_freight" class="form-control market_lr_block_input decimal-only" id="market_freight" value="{{(isset($market_freight))?$market_freight:''}}" placeholder="Market Freight">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Invoice Qty</label>
                                                <input type="text" name="invoice_qty" 
                                                       id="invoice_qty" class="form-control decimal-only"
                                                       placeholder="Invoice Qty."
                                                       value="{{$editData->invoice_qty ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label >Gross Wgt.</label>
                                                <input type="text" name="gross_weight" id="gross_weight" 
                                                    class="form-control decimal-only" placeholder="Gross Weight"
                                                    value="{{$editData->gross_weight ?? ''}}"  onchange="getNetWgt()" 
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Tare Wgt.</label>
                                                <input type="text" name="tare_weight" id="tare_weight" class="form-control decimal-only" placeholder="Tare Weight" value="{{$editData->tare_weight ?? ''}}" onchange="getNetWgt()">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Net Wgt.</label>
                                                <input type="text" name="net_weight" id="net_weight" class="form-control decimal-only" placeholder="Net Weight" value="{{$editData->net_weight ?? ''}}" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label>Reporting Date&Time</label>
                                                <input type="datetime-local" name="reporting_datetime" 
                                                       id="reporting_datetime" class="form-control"
                                                       value="{{$editData->reporting_datetime ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Unload Date&Time</label>
                                                <input type="datetime-local" name="unload_datetime" 
                                                       id="unload_datetime" class="form-control"
                                                       value="{{$editData->unload_datetime ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Unload Wt.</label>
                                                <input type="text" name="unload_weight" 
                                                       id="unload_weight" class="form-control decimal-only"
                                                       placeholder="Unload Wt." 
                                                       value="{{$editData->unload_weight ?? ''}}" onchange="getShortageQty()"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Shortage Qty</label>
                                                <input type="text" name="shortage_weight" 
                                                       id="shortage_weight" class="form-control decimal-only"
                                                       placeholder="Shortage Wt."
                                                       value="{{$editData->shortage_weight ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Detention Days</label>
                                                <input type="text" name="detention_days" 
                                                       id="detention_days" class="form-control integers-only"
                                                       placeholder="Detention Days"
                                                       value="{{$editData->detention_days ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Detention Amt.</label>
                                                <input type="text" name="detention" 
                                                       id="detention" class="form-control decimal-only"
                                                       placeholder="Detention Amt."
                                                       value="{{$editData->detention ?? ''}}"
                                                >
                                            </div>
                                            <div class="form-group col-md-4 lr_scan-block">
                                                <label>Lr Copy</label><br>
                                                <input type="file" name="lr_scan" id="lr_scan" >
                                                 {{-- @if(isset($editData['lr_scan']) && $editData['lr_scan']!='')
                                                    <a href="{{URL::to('public/uploads/lr_scan/'.$editData['lr_scan'])}}" target="_blank">
                                                        <img src ="{{asset('admin/images/Download-Icon.png')}}" class="img-40">
                                                    </a>
                                                 @endif  --}}
                                            </div>
                                         </div>
                                         <div class="row">
                                               <div class="form-group col-md-12">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remarks" id="remarks" 
                                                    placeholder="Remarks" >{{$editData->remarks ?? ''}}</textarea>
                                            </div>
                                         </div>
                                        <div class="row" style="margin-top:5px">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                                <a href="{{route('transport.trip.list')}}"  class="btn btn-danger">CANCEL</a>
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


@include('admin.common_components.add_place_via_modal') {{-- add location --}}
@include('admin.common_components.add_product_via_modal')
@include('admin.common_components.add_driver_via_modal')
@include('admin.common_components.add_vehicle_via_modal')
{{-- @include('admin.common_components.add_consigner_via_modal') --}}
@include('admin.common_components.add_party_via_modal')


<script type="text/javascript">

    $(document).ready(function(){
        showHideMarketLrBlock();
        
        @if(isset($editData) && $editData->id!='')
        $("#payable_by").change();
        $("#main #driver_id").change();
        @endif
    });

    
    function showHideMarketLrBlock(){
        checkBox = document.getElementById('is_market_lr');
       
        if(checkBox.checked) {
            $(".market_lr_block").show();
            $(".owner_lr_block").hide();

            $(".market_lr_block_input").attr("required","required");
            $(".owner_lr_block_input").removeAttr("required");
            $("#is_market_lr").val(1);

        }else{
            $(".market_lr_block").hide();
            $(".owner_lr_block").show();

            $(".owner_lr_block_input").attr("required","required");
            $(".market_lr_block_input").removeAttr("required");
            
            $("#is_market_lr").val(0);
        }
    }

    function getPayableBy(){
        var payable_by = $("#payable_by").val();
        // console.log(payable_by);

        $('#main #payable_party_id_disp').attr('disabled',false);
        if(payable_by!=''){
            if(payable_by=='CONSIGNOR'){
                var consignor_id = $('#main #consignor_id').val();
                if(consignor_id!=''){
                    $('#main #payable_party_id_disp').val(consignor_id).select2();  
                    $('#main #payable_party_id').val(consignor_id);
                }else{
                    $('#main #payable_party_id_disp').val('').select2();  
                    $('#main #payable_party_id').val('');  
                }
                $('#main #payable_party_id_disp').attr('disabled','disabled');
            }else if(payable_by=='CONSIGNEE'){
                var consignee_id = $('#main #consignee_id').val();
                if(consignee_id!=''){
                    $('#main #payable_party_id_disp').val(consignee_id).select2();  
                    $('#main #payable_party_id').val(consignee_id);  
                }else{
                    $('#main #payable_party_id_disp').val('').select2();  
                    $('#main #payable_party_id').val('');  
                }
                $('#main #payable_party_id_disp').attr('disabled','disabled');
            }else if(payable_by=='OTHER'){
                var payable_party_id='{{$editData->payable_party_id ?? ''}}';
                $('#main #payable_party_id_disp').val(payable_party_id).select2(); 
                $('#main #payable_party_id').val(payable_party_id); 
            }
        }
    }

    $('body').on('change', '#main #payable_party_id_disp', function() {
        var payable_party_id = $('option:selected','#main #payable_party_id_disp').val();
        $("#main #payable_party_id").val(payable_party_id);
    });

    $('body').on('change', '#main #driver_id', function() {
        var driver_mobile_no = $('option:selected','#main #driver_id').attr('driver_mobile_no');
        $("#main #driver_mobile_no").val(driver_mobile_no);
        // console.log(driver_mobile_no);
    });

    function openPlaceLocationModal(crnt_dropdown_id){
        $('#place-modal').modal({backdrop: 'static', keyboard: false});
        
        if(crnt_dropdown_id=='from_station_id'){
            $('#place-modal #placeModalTitle').html('Add From Station');
        }else if(crnt_dropdown_id=='to_station_id'){
            $('#place-modal #placeModalTitle').html('Add To Station');
        }else if(crnt_dropdown_id=='back_to_station_id'){
            $('#place-modal #placeModalTitle').html('Add Back To Station');
        }


        $('#place-modal').modal('show');
        $('#place-modal #crnt_dropdown_id').val(crnt_dropdown_id);

    }

    function addFromLocationPopup() {
        var name = $('#place-modal #name').val();
        var place_type = $('#place-modal #place_type').val();
        $('#place-modal .error').html(' ');
        if(name==''){
            $('#place-modal #name_error').html('field is required');
            return false
        }
        if(place_type==''){
            $('#place-modal #place_type_error').html('field is required');
            return false
        }

        var crnt_dropdown_id= $('#place-modal #crnt_dropdown_id').val();
        
        $.ajax({
            url: "{{ route('trip.add.from.location') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "name": name,
                "place_type": place_type,
            },
            success: function (place_id) {
                if(place_id==0){
                    $('#place-modal #name_error').html('place already exist in '+place_type);
                    return false;
                }else{
                    var html = '<option value="'+place_id+'" >'+$('#place-modal #name').val()+'</option>';
                    $("#"+crnt_dropdown_id).append(html);
                    $("#"+crnt_dropdown_id).val(place_id).select2();

                    $('#place-modal').modal('hide');
                    $('#place-modal .modal-input').val('');
                    $('#place-modal .modal-input-select').val(null).trigger('change');
                }
            }
        });
    }
    
    function openPartyModal(curnt_dropdown_id){
        $('#party-modal').modal({backdrop: 'static', keyboard: false});
        
        if(curnt_dropdown_id=='consignor_id'){
            $('#party-modal #partyModalTitle').html('Add Consignor');
        }else if(curnt_dropdown_id=='consignee_id'){
            $('#party-modal #partyModalTitle').html('Add Consignee');
        }else if(curnt_dropdown_id=='payable_party_id'){
            $('#party-modal #partyModalTitle').html('Add Payable Party');
        }

        $('#party-modal').modal('show');
        $('#party-modal #curnt_dropdown_id').val(curnt_dropdown_id);

    }

    function addPartyViaPopup() {
        var name = $('#party-modal #name').val();
        var party_type_id = $('#party-modal #party_type_id').val();
       
        $('#party-modal .error').html(' ');
        if(name==''){
            $('#party-modal #name_error').html('field is required');
            return false
        }
        if(party_type_id.length==0){
            $('#party-modal #party_type_error').html('field is required');
            return false;
        }

        $.ajax({
            url: "{{ route('trip.add.consignor') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "name": name,
                "party_type_id": party_type_id,
            },
            success: function (party_id) {
                var curnt_dropdown_id= $('#party-modal #curnt_dropdown_id').val();

                if(party_id==0){
                    $('#party-modal #name_error').html('party already exist !!');
                    return false;
                }else{
                    var html = '<option value="'+party_id+'" >'+$('#party-modal #name').val()+'</option>';
                    
                    if(curnt_dropdown_id!='payable_party_id'){
                      $("#"+curnt_dropdown_id).append(html);
                      $("#"+curnt_dropdown_id).val(party_id).select2();

                    }else if(curnt_dropdown_id=='payable_party_id'){
                        $("#payable_party_id_disp").append(html);
                        $("#payable_party_id_disp").val(party_id).select2();
                        $("#payable_party_id").val(party_id);
                    }

                    $('#party-modal').modal('hide');
                    $('#party-modal .modal-input').val('');
                    $('#party-modal .modal-input-select').val(null).trigger('change');
                }
                
            }

        });
    }

    function addProductPopup() {
        var product_name = $('#product-modal #product_name').val();
        $('#product-modal .error').html(' ');
        if(product_name==''){
            $('#product-modal #product_name_error').html('field is required');
            return false;
        }

        $.ajax({
            url: "{{ route('trip.add.product') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "product_name": product_name,
            },
            success: function (product_id) {
                if(product_id==0){
                    $('#product-modal #product_name_error').html('product already exist');
                    return false;
                }else{
                    var html = '<option value="'+product_id+'" >'+product_name+'</option>';
                    $("#product_id").append(html);
                    $("#product_id").val(product_id).select2();

                    $('#product-modal').modal('hide');
                    $('#product-modal .modal-input').val('');
                }
            }
        }); //ajax close

    } //func close

    function addVehiclePopup(e){
        event.preventDefault();
        $("#vehicle-add-modal form").submit();
    }

    $("#vehicle-add-modal form").on("submit", function (event) {//add/update modal form form
        event.preventDefault(); // Prevent the default form submission
        
        // Perform validation check
        if($(this).valid()) {
            var formData = new FormData();
            // var file = $('#modal-add_project #add_project_form #profile_image')[0].files[0];
            formData.append('_token',"{{ csrf_token() }}" );
            // formData.append('profile_image', file);
            formData.append('formdata',$('#vehicle-add-modal #addVehicleFormViaModal').serialize() );

            $.ajax({
                url: '{{ route('trip.add.vehicle') }}',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if(response.length==0){
                        $('#vehicle-add-modal #registration_no_error').html('vehicle already exist');
                        return false;
                    }else{
                        var html = '<option value="'+response['id']+'" transporter_party_id="" transporter_party_name="" driver_id="" driver_name="">'+response['registration_no']+'</option>';
                        $("#vehicle_id").append(html);
                        $("#vehicle_id").val(response['id']).select2();

                        $('#vehicle-add-modal').modal('hide');
                        $('#vehicle-add-modal .modal-input').val('');
                        $('#vehicle-add-modal .select2').val(null).select2();
                    }
                }
           });
        }
    });

    function addDriverPopup() {
        var driver_name = $('#driver-modal #driver_name').val();
        $('#driver-modal .error').html(' ');
        if(driver_name==''){
            $('#driver-modal #driver_name_error').html('field is required');
            return false
        }
        var contact = $('#driver-modal #contact').val();
        $('#driver-modal .error').html(' ');
        if(contact==''){
            $('#driver-modal #contact_error').html('field is required');
            return false
        }
        var home_contact = $('#driver-modal #home_contact').val();
        $('#driver-modal .error').html(' ');
        if(home_contact==''){
            $('#driver-modal #home_contact_error').html('field is required');
            return false
        }
        var local_address = $('#driver-modal #local_address').val();
        $('#driver-modal .error').html(' ');
        if(local_address==''){
            $('#driver-modal #local_address_error').html('field is required');
            return false
        }
        var permanent_address = $('#driver-modal #permanent_address').val();
        $('#driver-modal .error').html(' ');
        if(permanent_address==''){
            $('#driver-modal #permanent_address_error').html('field is required');
            return false
        }
        
        $.ajax({
            url: "{{ route('trip.add.driver') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "driver_name": driver_name,
                "contact": contact,
                "home_contact": home_contact,
                "local_address": local_address,
                "permanent_address": permanent_address,
                
            },
            success: function (driver_id) {
                if(driver_id==0){
                    $('#driver-modal #contact_error').html('contact no already exist !!');
                    return false;
                }else{
                    var html = '<option value="'+driver_id+'" driver_mobile_no="'+contact+'">'+driver_name+'</option>';
                    $("#driver_id").append(html);
                    $("#driver_id").val(driver_id).select2();

                    $('#driver-modal').modal('hide');
                    $('#driver-modal .modal-input').val('');
                }
            }
        });

    } //func close

    function getNetWgt(){
        var gross_weight = ($('#gross_weight').val()!='')?parseFloat($('#gross_weight').val()):0;
        var tare_weight = ($('#tare_weight').val()!='')?parseFloat($('#tare_weight').val()):0;

        var net_weight =(gross_weight-tare_weight);
        $("#net_weight").val(net_weight);
        getShortageQty();
    }


    function getShortageQty(){
        var net_weight = ($('#net_weight').val()!='')?parseFloat($('#net_weight').val()):0;
        var unload_weight = ($('#unload_weight').val()!='')?parseFloat($('#unload_weight').val()):0;

        var shortage_weight =(net_weight-unload_weight);
        $("#shortage_weight").val(shortage_weight);
    }

    function getVehicleData() {
        console.log('inn');
        return false;
        // var driver_id= $('#vehicle_id option:selected').attr("driver_id");   
        // var driver_name= $('#vehicle_id option:selected').attr("driver_name");   
        // var transporter_party_id= $('#vehicle_id option:selected').attr("transporter_party_id");   
        // var transporter_party_name= $('#vehicle_id option:selected').attr("transporter_party_name");
        
        // $("#driver_id").val(driver_id);
        // $("#driver_name").val(driver_name);
        // $("#transporter_party_id").val(transporter_party_id);
        // $("#transporter_party_name") .val(transporter_party_name);
        
    }

    ///old
   

   
    // function calcFigures(){
    //     var tds_per = $('option:selected', "#market_transporter_id").attr('tds_per');
    //     tds_per=(tds_per!='' && tds_per!='null')?parseFloat(tds_per):0;
      
    //     var deduct_amt_from_market_freight = (damage_amount+shortage_amount+tds_amount);
        
    //     var final_market_freight =(base_market_freight - deduct_amt_from_market_freight );
        
    //     $("#tds_amount").val(tds_amount.toFixed(2));
    //     $("#freight").val(final_market_freight);

    // }

    // $("#is_trip").on('change', function(){
    //     var is_trip = $("#is_trip").val();
    //     //console.log(is_trip)
    //     if(is_trip=="Old"){
    //        $(".old_trip_block").show();
    //        $("#old_trip_no").attr("required","required") 
    //     }else{
    //        $(".old_trip_block").hide();
    //        $("#old_trip_no").removeAttr("required") 
    //     }
    // })
    
   

    // $(".check-container").on("keyup change", function(e) {
    //     var container_no=$(this).val();

    //     $.ajax({
    //         type: "POST",
    //         dataType: "json",
    //         url: '{{ route('check.container.no') }}',
    //         data: {'container_no': container_no,"_token": "{{ csrf_token() }}",},
    //         success: function (data) {
    //             if(data ==1){
    //                 $("#container_1_err").html('');
    //                 $("#saveData").prop("disabled",false);
    //             }else{
    //                 $("#container_1_err").html('Wrong Container No, Please check !');
    //                 $("#saveData").prop("disabled",true);
    //             }
    //         }
    //     }); 
    // })
    
    
</script>
@endsection