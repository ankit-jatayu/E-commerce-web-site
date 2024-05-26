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
                                                <h4>Driver</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="page-header-breadcrumb">
                                                <a href="{{route('add.location')}}" class="btn waves-effect waves-light btn-primary float-right"><i class="icofont icofont-plus"></i>Add New </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($driver_detail->id)) ? route('update.driver',base64_encode($driver_detail->id)) : route('store.driver')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                              	<label for="name">Name</label>
                                              	<input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ $driver_detail->name ?? '' }}" required>
                                              	@error('name')
				                                    <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
				                                @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                              	<label for="app_date">App Date</label>
                                              	<input type="date" name="app_date" class="form-control" id="app_date" placeholder="Name" value="{{ $driver_detail->app_date ?? date('Y-m-d') }}" required>
                                            </div>

                                            <div class="form-group col-md-3">
                                              	<label for="contact">Contact</label>
                                              	<input type="text" name="contact" class="form-control" id="contact" placeholder="contact" value="{{ $driver_detail->contact ?? '' }}" required>
                                              	
                                            </div>

                                            <div class="form-group col-md-3">
                                              	<label for="home_contact">Home Contact</label>
                                              	<input type="text" name="home_contact" class="form-control" id="home_contact" placeholder="home contact" value="{{ $driver_detail->home_contact ?? '' }}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                              	<label for="local_address">Local Address</label>
                                              	<textarea name="local_address" class="form-control" id="local_address">{{(isset($driver_detail->local_address))?$driver_detail->local_address:''}}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                              	<label for="permanent_address">Permanent Address</label>
                                              	<textarea name="permanent_address" class="form-control" id="permanent_address">{{(isset($driver_detail->permanent_address))?$driver_detail->permanent_address:''}}</textarea>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">SAVE</button>
                                        <a href="{{route('list.driver')}}"  class="btn btn-danger">CANCEL</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(isset($driver_detail->id))
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>PERSONAL DETAILS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
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

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 >RELATIVES DETAILS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main_doc" method="post" action="{{route('add.driver.relative')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="relation">Relation</label>
                                                <input type="text" name="relation" class="form-control" id="relation" required>
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" id="name" required>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="phone_no">Phone no</label>
                                                <input type="text" name="phone_no" class="form-control" id="phone_no" required>
                                            </div>

                                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Relation</th>
                                                        <th>Name</th>
                                                        <th>Phone No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($driver_relative_list as $key => $value)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$value->relation}}</td>
                                                            <td>{{$value->name}}</td>
                                                            <td>{{$value->phone_no}}</td>
                                                            <td width="5%">
                                                              <button type="button" class="dueedt btn btn-danger fa fa-trash" onclick="removeRelative({{$value->id}})"></button>
                                                            </td>
                                                            </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>DRIVER DUE DOCUMENTS TRACKS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main_due" method="post" action="{{route('add.driver.due')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="due_name">Document Name</label>
                                                <select name="due_name" id="due_name" class="form-control select2">
                                                    <option value="License" >License</option>
                                                    <option value="Adani pass">Adani pass</option>
                                                    <option value="Bank passbook/cheque">Bank passbook/cheque</option>
                                                    <option value="Adhaar card">Adhaar card</option>
                                                    <option value="Pan Card">Pan Card</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="validity">Exipery Date</label>
                                                <input type="date" name="validity" class="form-control" id="validity" required>
                                            </div>
                                            
                                            <div class="form-group col-md-4" style="margin-top: 27px;">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Name</th>
                                                        <th>Expiry Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($driver_due as $key => $value)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$value->due_name}}</td>
                                                            <td>{{$value->validity}}</td>
                                                            <td width="5%">
                                                                <button type="button" class="dueedt btn btn-danger fa fa-trash" onclick="removeExpiry({{$value->id}})"></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>DRIVER DOCUMENTS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main_doc" method="post" action="{{route('add.driver.doc')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="file_name">Document Name</label>
                                                {{-- <input type="text" name="file_name" class="form-control" id="file_name" required> --}}
                                                <select name="file_name" id="file_name" class="form-control select2">
                                                    <option value="License" >License</option>
                                                    <option value="Adani pass">Adani pass</option>
                                                    <option value="Bank passbook/cheque">Bank passbook/cheque</option>
                                                    <option value="Adhaar card">Adhaar card</option>
                                                    <option value="Pan Card">Pan Card</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group col-md-4">
                                                <label for="file">Select Document</label>
                                                <input type="file" name="file" class="form-control" id="file" required>
                                            </div>

                                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <table class="table">
                                            <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Name</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                @foreach($driver_doc as $key => $value)
                                                    <tr>
                                                        <td width="10%">{{$key+1}}</td>
                                                        <td>{{$value->file_name}}</td>
                                                        <td>
                                                             <a 
                                                                href="{{Storage::disk('s3')->url('driver_doc/'.$value->file)}}" 
                                                                target="_blank" 
                                                                class="btn btn-success"
                                                             > <i class="fa fa-download"></i> 
                                                             </a>
                                                        </td>
                                                        <td width="5%">
                                                            <button type="button" class="dueedt btn btn-danger fa fa-trash" onclick="removeDoc({{$value->id}})"></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>GUARANTORS DETAILS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
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

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>BANK DETAILS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
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

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>VEHICLE DETAILS</h4>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                           <li><i class="feather icon-minus minimize-card" style="color: white"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    @if(empty($vehicle_allocated))

                                    <form id="main_due" method="post" action="{{route('add.vehicle.driver')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="driver_id" value="{{$driver_detail->id ?? ''}}">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="vehicle_id">Vehicle</label>
                                                <select id="vehicle_id" class="form-control select2" required name="vehicle_id">
                                                    @foreach($vehicle_list as $key => $value)
                                                        <option value="{{$value->id}}">{{$value->registration_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="from_date">From Date</label>
                                                <input type="date" name="from_date" class="form-control" id="from_date" required>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="to_date">To Date</label>
                                                <input type="date" name="to_date" class="form-control" id="to_date">
                                            </div>
                                            
                                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                    <div class="row">
                                        <hr>
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Vehicle</th>
                                                        <th>From Date</th>
                                                        <th>To Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vehicle_allocated_list as $key => $value)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$value->registration_no}}</td>
                                                            <td>{{$value->from_date}}</td>
                                                            <td>{{$value->to_date}}</td>
                                                            <td><button type="button" class="btn btn-primary btn-xs allocate" data-toggle="modal" data-target="#large-Modal" data-id="{{$value->allocated_id}}" data-from="{{$value->from_date}}" data-to="{{$value->to_date}}" data-vehicle="{{$value->vehicle_id}}"><span class="fa fa-edit"></span></button></td>
                                                            </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">EDIT VEHICLE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <input type="hidden" id="allocated_id">
                    <input type="hidden" id="model_driver_id" value="{{$driver_detail->id ?? ''}}">
                    <label for="model" class="col-sm-2 col-form-label">Vehicle</label>
                    <div class="col-sm-6">
                        <select id="model_vehicle_id" class="form-control select2" required name="model_vehicle_id" style="width: 100%">
                            @if(isset($driver_detail->id))
                                @foreach($vehicle_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->registration_no}}</option>
                                @endforeach
                            @endif
                        </select>
                        <span id="nameerror" class="error"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="from_date" class="col-sm-2 col-form-label">From Date</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="from_date" id="model_from_date">
                        <span id="from_date_error" class="error"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="to_date" class="col-sm-2 col-form-label">To Date</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="to_date" id="model_to_date">
                        <span id="to_date_error" class="error"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary waves-effect waves-light saveModel">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


    function removeRelative(id,e){
          //e.preventDefault();
        if(confirm("Are you sure you want to remove?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('remove.driver.relative') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    location.reload();
                }
            });
        }else{
            return false;
        }
    }

    function removeDoc(id,e){
          //e.preventDefault();
        if(confirm("Are you sure you want to remove?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('remove.driver.doc') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    location.reload();
                }
            });
        }else{
            return false;
        }
    }

    function removeExpiry(id,e){
            //e.preventDefault();
        if(confirm("Are you sure you want to remove?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('remove.driver.due') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    location.reload();
                }
            });
        }else{
            return false;
        }
    }

    $(".allocate").click(function() {
        $("#allocated_id").val($(this).data('id'));
        $("#model_vehicle_id").val($(this).data('vehicle'));
        $("#model_from_date").val($(this).data('from'));
        $("#model_to_date").val($(this).data('to'));
        $('#model_vehicle_id').trigger('change');
    });

    $('.saveModel').click(function(){
        var driver_id = $('#model_driver_id').val();
        var vehicle_id = $('#model_vehicle_id').val();
        var from_date = $('#model_from_date').val();
        var to_date = $('#model_to_date').val();
        var id = $('#allocated_id').val();
        
        if(model_vehicle_id == ''){
            $("#nameerror").html("Please select vehicle");
            return false;
        }
        
        $.ajax({
                url: '{{ route('update.vehicle.driver') }}',
                data: {'driver_id': driver_id,'vehicle_id':vehicle_id,'from_date':from_date,'to_date':to_date,'id':id,"_token": "{{ csrf_token() }}",},
                type: 'POST',
                dataType:'json',
                'success':function(data){
                    location.reload();
                     
                }
            });
    });
</script>
@endsection