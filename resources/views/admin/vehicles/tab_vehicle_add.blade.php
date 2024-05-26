@extends('layouts.app')
@section('title', (isset($vehicle_detail->id))?'Edit Vehile  Detail':'Add Vehicle Detail')

@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="card">
                                        <div class="card-header">
                                            @if(Request::segment(1)=='vehicle-edit' || Request::segment(1)=='vehicle-add')
                                                <div class="row align-items-end">
                                                    <div class="col-lg-8">
                                                        <div class="page-header-title">
                                                            <h4>VEHICLE : {{ $vehicle_detail->registration_no ?? '' }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="page-header-breadcrumb">
                                                            <a href="{{route('add.vehicle')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                                        </div>
                                                    </div>
                                                </div>
                                           @endif
                                        </div>
                                        <div class="card-block">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">
                                                    <ul class="nav nav-tabs  tabs">
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-edit' || Request::segment(1)=='vehicle-add')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('edit.vehicle',base64_encode($vehicle_detail->id)) : route('add.vehicle')}}" >Vehicle Detail</a>
                                                        </li>
                                                        @if(isset($vehicle_detail))
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-add-due-track-tab')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('vehicle.add.due.track.tab',['id'=> base64_encode($vehicle_detail->id)]) : route('vehicle.add.due.track.tab')}}">Due Tracks</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-add-documents-tab')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('vehicle.add.documents.tab',['id'=> base64_encode($vehicle_detail->id)]) : route('vehicle.add.documents.tab')}}">Documents</a>
                                                        </li>
                                                       {{--  <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-add-driver-tab')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('vehicle.add.driver.detail.tab',['id'=> base64_encode($vehicle_detail->id)]) : route('vehicle.add.driver.detail.tab')}}">Driver Detail</a>
                                                        </li> --}}
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane {{(Request::segment(1)=='vehicle-edit' || Request::segment(1)=='vehicle-add')?'active':''}} " 
                                                            id="vehicle_detail_tab" role="tabpanel">
                                                             <form id="main" method="post" action=" {{ (isset($vehicle_detail->id)) ? route('update.vehicle',base64_encode($vehicle_detail->id)) : route('store.vehicle')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="registration_no">Registration No</label>
                                                <input type="text" name="registration_no" class="form-control" id="registration_no" placeholder="vehicle No" value="{{ $vehicle_detail->registration_no ?? '' }}" required style="text-transform:uppercase;">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Vehicle Owner</label>
                                                <select name="party_id" id="party_id" class="form-control select2" required>
                                                    <option value="">CHOOSE VEHICLE OWNER</option>       
                                                    @if(!empty($parties))
                                                        @foreach($parties as $k =>$row)   
                                                          <option value="{{$row['id']}}"
                                                                @selected(($row['id'] == (isset($vehicle_detail->party_id)?$vehicle_detail->party_id:0)))
                                                          >
                                                           {{$row['name']}}
                                                          </option>       
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="type">Type</label>
                                                <select name="type" id="type" class="form-control select2">
                                                    <option value="owner" {{('owner'==(isset($vehicle_detail->type)?$vehicle_detail->type:''))?'selected':''}}>Owner</option> 
                                                    <option value="group" {{('group'==(isset($vehicle_detail->type)?$vehicle_detail->type:''))?'selected':''}}>Group</option> 
                                                    <option value="market" {{('market'==(isset($vehicle_detail->type)?$vehicle_detail->type:''))?'selected':''}}>Market</option>  
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="model_code">Model Code</label>
                                                {{-- <input type="text" name="model_code" class="form-control" id="model_code" placeholder="Model code" value="{{ $vehicle_detail->model_code ?? '' }}" > --}}
                                                <select name="model_code" id="model_code" class="form-control select2"  style="width: 100%">
                                               <option value="">CHOOSE MODEL CODE</option>       

                                                    @if(!empty($VehicleModelCodes))
                                                        @foreach($VehicleModelCodes as $k =>$singledata)
                                                            <option 
                                                                value="{{$singledata->name}}"
                                                {{($singledata->name==(isset($vehicle_detail->model_code)?$vehicle_detail->model_code:''))?'selected':''}}            >{{$singledata->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="vehicle_alias">Vehicle alias</label>
                                                <input type="text" name="vehicle_alias" class="form-control" id="vehicle_alias" placeholder="vehicle alias" value="{{ $vehicle_detail->vehicle_alias ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="registration_date">Registration Date</label>
                                                <input type="date" name="registration_date" class="form-control" id="registration_date" value="{{ $vehicle_detail->registration_date ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="rto_auth">RTO auth.</label>
                                                <input type="text" name="rto_auth" class="form-control" id="rto_auth" placeholder="RTO" value="{{ $vehicle_detail->rto_auth ?? '' }}" >
                                            </div>
                                        
                                            <div class="form-group col-md-2">
                                                <label for="chassis_no">Chassis No.</label>
                                                <input type="text" name="chassis_no" class="form-control" id="chassis_no" placeholder="Chassis no" value="{{ $vehicle_detail->chassis_no ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="engine_no">Engine No</label>
                                                <input type="text" name="engine_no" class="form-control" id="engine_no" placeholder="Engine no" value="{{ $vehicle_detail->engine_no ?? '' }}" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="manufacture_year">Manufacture Year</label>
                                                <input type="text" name="manufacture_year" class="form-control" id="manufacture_year" placeholder="Manufacture year" value="{{ $vehicle_detail->manufacture_year ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="manufacture_month">Manufacture Month</label>
                                                <input type="text" name="manufacture_month" class="form-control" id="manufacture_month" placeholder="Manufacture month" value="{{ $vehicle_detail->manufacture_month ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="purchase_date">Purchase Date</label>
                                                <input type="date" name="purchase_date" class="form-control" id="purchase_date" value="{{ $vehicle_detail->purchase_date ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="purchase_amount">Purchase Amount</label>
                                                <input type="text" name="purchase_amount" class="form-control" id="purchase_amount" placeholder="Purchase amount" value="{{ $vehicle_detail->purchase_amount ?? '' }}" >
                                            </div>
                                        
                                            <div class="form-group col-md-2">
                                                <label for="sale_date">Sale Date</label>
                                                <input type="date" name="sale_date" class="form-control" id="sale_date" value="{{ $vehicle_detail->sale_date ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="sale_amount">Sale Amount</label>
                                                <input type="text" name="sale_amount" class="form-control" id="sale_amount" placeholder="Sale amount" value="{{ $vehicle_detail->sale_amount ?? '' }}" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="gvw_in_kg">GVW in kg/kl</label>
                                                <input type="text" name="gvw_in_kg" class="form-control" id="gvw_in_kg" placeholder="gvw in kg" value="{{ $vehicle_detail->gvw_in_kg ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="ulw_in_kg">ULW in kg/kl</label>
                                                <input type="text" name="ulw_in_kg" class="form-control" id="ulw_in_kg" placeholder="ulw in kg" value="{{ $vehicle_detail->ulw_in_kg ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="equipment_vehicle">Equipment Vehicle</label>
                                                <select name="equipment_vehicle" id="equipment_vehicle" class="form-control select2">
                                                    <option value="0" {{('0'==(isset($vehicle_detail->equipment_vehicle)?$vehicle_detail->equipment_vehicle:''))?'selected':''}}>No</option> 
                                                    <option value="1" {{('1'==(isset($vehicle_detail->equipment_vehicle)?$vehicle_detail->equipment_vehicle:''))?'selected':''}}>Yes</option>   
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="vehicle_type">Vehicle Type</label>
                                                {{-- <input type="text" name="vehicle_type" class="form-control" id="vehicle_type" placeholder="vehicle type" value="{{ $vehicle_detail->vehicle_type ?? '' }}" > --}}
                                                <select name="vehicle_type" id="vehicle_type" class="form-control select2 filter-input-select">
                                                   <option value="">CHOOSE VEHICLE TYPE</option>       
                                                   @if(!empty($VehicleTypes))
                                                   @foreach($VehicleTypes as $k =>$row)   
                                                   <option value="{{$row->name}}"
                                                {{($row->name==(isset($vehicle_detail->vehicle_type)?$vehicle_detail->vehicle_type:''))?'selected':''}}
                                                    >{{$row->name}}</option>       
                                                   @endforeach
                                                   @endif
                                               </select>
                                            </div>
                                        
                                            <div class="form-group col-md-2">
                                                <label for="stephanie">Stephanie</label>
                                                <select name="stephanie" id="stephanie" class="form-control select2">
                                                    <option value="No" {{('No'==(isset($vehicle_detail->stephanie)?$vehicle_detail->stephanie:''))?'selected':''}}>No</option> 
                                                    <option value="Yes" {{('Yes'==(isset($vehicle_detail->stephanie)?$vehicle_detail->stephanie:''))?'selected':''}}>Yes</option>   
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="fuel">Fuel</label>
                                                <select name="fuel" id="fuel" class="form-control select2">
                                                    <option value="petrol" {{('petrol'==(isset($vehicle_detail->fuel)?$vehicle_detail->fuel:''))?'selected':''}}>Petrol</option>   
                                                    <option value="diesel" {{('diesel'==(isset($vehicle_detail->fuel)?$vehicle_detail->fuel:''))?'selected':''}}>Diesel</option>   
                                                </select>
                                            </div>
                                            {{-- <div class="form-group col-md-2">
                                                <label for="vehicle_no">Vehicle Status</label>
                                                <input type="text" name="vehicle_no" class="form-control" id="vehicle_no" placeholder="vehicle No" value="{{ $vehicle_detail->vehicle_no ?? '' }}" >
                                            </div> --}}

                                            <div class="form-group col-md-2">
                                                <label for="f_t_type">Front Tyre Type</label>
                                                <input type="text" name="f_t_type" class="form-control" id="f_t_type" placeholder="" value="{{ $vehicle_detail->f_t_type ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="f_size">Front Tyre Size</label>
                                                <input type="text" name="f_size" class="form-control" id="f_size" placeholder="" value="{{ $vehicle_detail->f_size ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="f_total_tyre">Front Total Tyre</label>
                                                <input type="text" name="f_total_tyre" class="form-control" id="f_total_tyre" placeholder="" value="{{ $vehicle_detail->f_total_tyre ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="b_t_type">Back Tyre Type</label>
                                                <input type="text" name="b_t_type" class="form-control" id="b_t_type" placeholder="" value="{{ $vehicle_detail->b_t_type ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="b_size">Back Tyre Size</label>
                                                <input type="text" name="b_size" class="form-control" id="b_size" placeholder="" value="{{ $vehicle_detail->b_size ?? '' }}" >
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="b_total_tyre">Back Total Tyre</label>
                                                <input type="text" name="b_total_tyre" class="form-control" id="b_total_tyre" placeholder="" value="{{ $vehicle_detail->b_total_tyre ?? '' }}" >
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" name="remarks" class="form-control" id="remarks" placeholder="" value="{{ $vehicle_detail->remarks ?? '' }}" >
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary"><i class="icofont icofont-save"></i> SAVE</button>
                                        <a href="{{route('list.vehicle')}}"  class="btn btn-danger">CANCEL</a>
                                    </form>       
                                                        </div> {{-- tab pane close --}}
                                                        
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
    </div>
</div>
</div>
</div>

<script type="text/javascript">
 
    
</script>
@endsection