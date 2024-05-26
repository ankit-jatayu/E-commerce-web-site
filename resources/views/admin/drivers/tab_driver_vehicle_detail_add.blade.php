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
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-vehicle-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.vehicle.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.vehicle.detail.tab')}}">Vehicle detail</a>
                                                        </li>
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane active" id="active_tab" role="tabpanel">
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
                data: {
                       'driver_id': driver_id,
                       'vehicle_id':vehicle_id,
                       'from_date':from_date,
                       'to_date':to_date,
                       'id':id,
                       "_token": "{{ csrf_token() }}",
                      },
                type: 'POST',
                dataType:'json',
                'success':function(data){
                    location.reload();
                     
                }
            });
    });
</script>
@endsection