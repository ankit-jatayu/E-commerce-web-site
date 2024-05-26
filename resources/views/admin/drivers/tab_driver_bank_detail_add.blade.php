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
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-personal-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.personal.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.personal.detail.tab')}}">Personal Detail</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-relative-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.relative.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.relative.detail.tab')}}">Relative Detail</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-duedoc-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.duedoc.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.duedoc.detail.tab')}}">Driver due doc track</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-doc-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.doc.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.doc.detail.tab')}}">Driver documents</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-guarantor-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.guarantor.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.guarantor.detail.tab')}}">Guarantors detail</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-bank-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.bank.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.bank.detail.tab')}}">Bank detail</a>
                                                        </li>
                                                      {{--   <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-vehicle-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.vehicle.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.vehicle.detail.tab')}}">Vehicle detail</a>
                                                        </li> --}}
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane active" id="active_tab" role="tabpanel">
                                                             <form id="main_bank" method="post" action="{{route('update.driver.bank')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="bank_name">Name of Bank</label>
                                                <input type="text" name="bank_name" class="form-control" id="bank_name" value="{{$driver_bank->bank_name ?? ''}}" required>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="ac_no">Account No.</label>
                                                <input type="text" name="ac_no" class="form-control" id="ac_no" value="{{$driver_bank->ac_no ?? ''}}" required>
                                            </div>

                                            {{-- <div class="form-group col-md-6">
                                                <label for="state">State Name</label>
                                                <input type="text" name="state" class="form-control" id="state" value="{{$driver_bank->state ?? ''}}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="place">Place Name</label>
                                                <input type="text" name="place" class="form-control" id="place" value="{{$driver_bank->place ?? ''}}" required>
                                            </div> --}}
                                            
                                            <div class="form-group col-md-6">
                                                <label for="ifsc">IFSC code</label>
                                                <input type="text" name="ifsc" class="form-control" id="ifsc" value="{{$driver_bank->ifsc ?? ''}}" required>
                                            </div>

                                            {{-- <div class="form-group col-md-6">
                                                <label for="micr">MICR code</label>
                                                <input type="text" name="micr" class="form-control" id="micr" value="{{$driver_bank->micr ?? ''}}" required>
                                            </div> --}}
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
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
</div>
</div>
</div>


<script type="text/javascript">

</script>
@endsection