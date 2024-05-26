@extends('layouts.app')

@section('title',(isset($driver_detail->id))?'Edit Driver':'Add Driver')
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
                                                            <h4>Driver : {{$driver_detail->name ?? '' }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="page-header-breadcrumb">
                                                            <a href="{{route('add.driver')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                </div>
                                <div class="card-block">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">
                                                    <ul class="nav nav-tabs  tabs">
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-edit' || Request::segment(1)=='driver-add')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('edit.driver',base64_encode($driver_detail->id)) : route('add.driver')}}" >Driver Detail</a>
                                                        </li>
                                                        @if(isset($driver_detail))
                                                        {{-- <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-personal-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.personal.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.personal.detail.tab')}}">Personal Detail</a>
                                                        </li> --}}
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-relative-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.relative.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.relative.detail.tab')}}">Relative Detail</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-duedoc-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.duedoc.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.duedoc.detail.tab')}}">Driver due doc track</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-doc-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.doc.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.doc.detail.tab')}}">Driver documents</a>
                                                        </li>
                                                        {{-- <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-guarantor-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.guarantor.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.guarantor.detail.tab')}}">Guarantors detail</a>
                                                        </li> --}}
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-bank-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.bank.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.bank.detail.tab')}}">Bank detail</a>
                                                        </li>
                                                        {{-- <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-vehicle-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.vehicle.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.vehicle.detail.tab')}}">Vehicle detail</a>
                                                        </li> --}}
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane {{(Request::segment(1)=='vehicle-edit' || Request::segment(1)=='vehicle-add')?'active':''}} " 
                                                            id="vehicle_detail_tab" role="tabpanel">
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                    <form id="main" method="post" action=" {{ (isset($driver_detail->id)) ? route('update.driver',base64_encode($driver_detail->id)) : route('store.driver')}}" 
                                        novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                              	<label for="name" class="required">Name</label>
                                              	<input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ $driver_detail->name ?? '' }}" required>
                                             </div>

                                            <div class="form-group col-md-3">
                                              	<label for="app_date" class="required">App Date</label>
                                              	<input type="date" name="app_date" class="form-control" id="app_date" placeholder="Name" value="{{ $driver_detail->app_date ?? date('Y-m-d') }}" required>
                                            </div>

                                            <div class="form-group col-md-3">
                                              	<label for="contact" class="required">Contact</label>
                                              	<input type="text" name="contact" class="form-control integers-only" 
                                                    id="contact" placeholder="contact" maxlength="10" 
                                                    value="{{ $driver_detail->contact ?? '' }}" required
                                                >
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                              	<label for="home_contact" class="required" >Home Contact</label>
                                              	<input type="text" name="home_contact" class="form-control integers-only" id="home_contact" placeholder="home contact" value="{{ $driver_detail->home_contact ?? '' }}" required maxlength="10" >
                                            </div>

                                            <div class="form-group col-md-6">
                                              	<label for="local_address">Local Address</label>
                                              	<textarea name="local_address" class="form-control" id="local_address" placeholder="Enter Local Address">{{(isset($driver_detail->local_address))?$driver_detail->local_address:''}}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                              	<label for="permanent_address">Permanent Address</label>
                                              	<textarea name="permanent_address" class="form-control" id="permanent_address" placeholder="Enter Permanent Address">{{(isset($driver_detail->permanent_address))?$driver_detail->permanent_address:''}}</textarea>
                                            </div>


                                                <div class="form-group col-md-3">
                                                    <label>Driver Pic</label>
                                                    <input type="file" name="driver_pic" class="driver_pic" >
                                                </div>
                                                @if(isset($driver_detail->driver_pic) && $driver_detail->driver_pic != '')
                                                    <div class="form-group col-md-1">
                                                        <img style="height:100px;width:75px;" src="{{ asset('uploads/driver_pics/'.$driver_detail->driver_pic) }}">
                                                    </div>
                                                @endif

                                        </div>
                                        {{-- personal detail block --}}
                                        @include('admin.drivers.tab_driver_personal_detail_add')
                                        {{-- personal detail block --}}

                                        {{-- guarantor detail block --}}
                                        @include('admin.drivers.tab_driver_guarantor_detail_add')
                                        {{-- guarantor detail block --}}
                                        
                                        <div class="form-row" style="margin-top:10px;">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                                <a href="{{route('list.driver')}}"  class="btn btn-danger">CANCEL</a>
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

</script>
@endsection