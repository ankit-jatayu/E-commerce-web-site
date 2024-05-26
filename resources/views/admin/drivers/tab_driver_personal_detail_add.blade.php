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
                                               <form id="main_doc" method="post" action="{{route('update.driver.personal')}}" novalidate="" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="experience">Experience</label>
                                                        <input type="text" name="experience" class="form-control" id="experience" value="{{ $driver_detail->experience ?? '' }}">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="qualification">Qualification</label>
                                                        <input type="text" name="qualification" class="form-control" id="qualification" value="{{ $driver_detail->qualification ?? '' }}">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="blood_group">Blood Group</label>
                                                        <select name="blood_group" id="blood_group" class="form-control select2">
                                                            <option value="A-" {{('A-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>A-</option>
                                                            <option value="A+" {{('A+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>A+</option>
                                                            <option value="B-" {{('B-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>B-</option>
                                                            <option value="B+" {{('B+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>B+</option>
                                                            <option value="AB-" {{('AB-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>AB-</option>
                                                            <option value="AB+" {{('AB+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>AB+</option>
                                                            <option value="O-" {{('O-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>O-</option>
                                                            <option value="O+" {{('O+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>O+</option>
                                                        </select>
                                                    </div>    
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="driver_dob">Birth Date</label>
                                                        <input type="date" name="driver_dob" class="form-control" id="driver_dob" value="{{ $driver_detail->driver_dob ?? '' }}">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="Salary">Salary</label>
                                                        <input type="text" name="Salary" class="form-control" id="Salary" value="{{ $driver_detail->Salary ?? '' }}">
                                                    </div>    
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
@endsection --}}

<fieldset>
    <legend>Personal Detail</legend>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="experience">Experience</label>
            <input type="text" name="experience" class="form-control" id="experience" value="{{ $driver_detail->experience ?? '' }}" placeholder="Enter Experience">
        </div>

        <div class="form-group col-md-4">
            <label for="qualification">Qualification</label>
            <input type="text" name="qualification" class="form-control" id="qualification" value="{{ $driver_detail->qualification ?? '' }}" placeholder="Enter Qualification">
        </div>

        <div class="form-group col-md-4">
            <label for="blood_group">Blood Group</label>
            <select name="blood_group" id="blood_group" class="form-control select2"  style="width:100%">
                <option value="A-" {{('A-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>A-</option>
                <option value="A+" {{('A+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>A+</option>
                <option value="B-" {{('B-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>B-</option>
                <option value="B+" {{('B+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>B+</option>
                <option value="AB-" {{('AB-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>AB-</option>
                <option value="AB+" {{('AB+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>AB+</option>
                <option value="O-" {{('O-'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>O-</option>
                <option value="O+" {{('O+'==(isset($driver_detail->blood_group)?$driver_detail->blood_group:0))?'selected':''}}>O+</option>
            </select>
        </div>    
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="driver_dob">Birth Date</label>
            <input type="date" name="driver_dob" class="form-control" id="driver_dob" 
                   value="{{$driver_detail->driver_dob ??''}}"
            >
        </div>

        <div class="form-group col-md-4">
            <label for="Salary">Salary</label>
            <input type="text" name="Salary" class="form-control integers-only" id="Salary" 
                   placeholder="Enter Salary" value="{{ $driver_detail->Salary ?? '' }}">
        </div>    
    </div>
</fieldset>