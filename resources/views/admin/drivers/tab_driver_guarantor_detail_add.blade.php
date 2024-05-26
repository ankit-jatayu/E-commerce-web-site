{{-- @extends('layouts.app')

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
                                                        @endif
                                                    </ul>
                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane active" id="active_tab" role="tabpanel">
                                                               <form id="main_guarantor" method="post" action="{{route('update.driver.guarantor')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="guarentor1">Guarantor 1</label>
                                                <input type="text" name="guarentor1" class="form-control" id="guarentor1" value="{{$driver_guarantors->guarentor1 ?? ''}}" required>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="guarentor2">Guarantor 2</label>
                                                <input type="text" name="guarentor2" class="form-control" id="guarentor2" value="{{$driver_guarantors->guarentor2 ?? ''}}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="guarentor1_phone_no">Contact no. 1</label>
                                                <input type="text" name="guarentor1_phone_no" class="form-control" id="guarentor1_phone_no" value="{{$driver_guarantors->guarentor1_phone_no ?? ''}}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="guarentor2_phone_no">Contact no. 2</label>
                                                <input type="text" name="guarentor2_phone_no" class="form-control" id="guarentor2_phone_no" value="{{$driver_guarantors->guarentor2_phone_no ?? ''}}" required>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="guarentor1_address">Address 1</label>
                                                <input type="text" name="guarentor1_address" class="form-control" id="guarentor1_address" value="{{$driver_guarantors->guarentor1_address ?? ''}}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="guarentor2_address">Address 2</label>
                                                <input type="text" name="guarentor2_address" class="form-control" id="guarentor2_address" value="{{$driver_guarantors->guarentor2_address ?? ''}}" required>
                                            </div>

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
@endsection --}}

<fieldset>
    <legend>Gaurantor Detail</legend>
    <div class="form-row">
        <div class="col-md-6">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="guarentor1">Guarantor 1</label>
                    <input type="text" name="guarentor1" class="form-control" id="guarentor1" value="{{$driver_guarantors->guarentor1 ?? ''}}" placeholder="Enter Guarantor Name">
                </div>
                <div class="form-group col-md-12">
                    <label for="guarentor1_phone_no">Contact no.</label>
                    <input type="text" name="guarentor1_phone_no" class="form-control integers-only" id="guarentor1_phone_no" value="{{$driver_guarantors->guarentor1_phone_no ?? ''}}"  maxlength="10"
                    placeholder="Enter Guarantor Contact No."
                    >
                </div>
                <div class="form-group col-md-12">
                    <label for="guarentor1_address">Address </label>
                    <input type="text" name="guarentor1_address" class="form-control" id="guarentor1_address" 
                           value="{{$driver_guarantors->guarentor1_address ?? ''}}" 
                           placeholder="Enter Guarantor Address"
                    >
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="guarentor2">Guarantor 2</label>
                    <input type="text" name="guarentor2" class="form-control" id="guarentor2" value="{{$driver_guarantors->guarentor2 ?? ''}}" placeholder="Enter Guarantor Name">
                </div>
                <div class="form-group col-md-12">
                    <label for="guarentor2_phone_no">Contact no.</label>
                    <input type="text" name="guarentor2_phone_no" class="form-control integers-only" id="guarentor2_phone_no" value="{{$driver_guarantors->guarentor2_phone_no ?? ''}}"  maxlength="10"
                    placeholder="Enter Guarantor Contact No."
                    >
                </div>
                <div class="form-group col-md-12">
                    <label for="guarentor2_address">Address</label>
                    <input type="text" name="guarentor2_address" class="form-control" id="guarentor2_address" value="{{$driver_guarantors->guarentor2_address ?? ''}}" placeholder="Enter Guarantor Address ">
                </div>
            </div>
        </div>

    </div>
</fieldset>