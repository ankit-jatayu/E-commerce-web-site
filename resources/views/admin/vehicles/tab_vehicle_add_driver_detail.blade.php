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
                                            <h5>Vehicle Driver Details</h5>
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
                                                        <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-add-driver-tab')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('vehicle.add.driver.detail.tab',['id'=> base64_encode($vehicle_detail->id)]) : route('vehicle.add.driver.detail.tab')}}">Driver Detail</a>
                                                        </li>
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        
                                                        <div class="tab-pane {{(Request::segment(1)=='vehicle-add-driver-tab')?'active':''}}" id="driver detail" role="tabpanel">
                                                           @if(isset($vehicle_detail->id))
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    @if(empty($driver_allocated))
                                                                        <form id="main_due" method="post" action="{{route('add.vehicle.driver')}}" novalidate="" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="hidden" name="vehicle_id" value="{{$vehicle_detail->id ?? ''}}">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-4">
                                                                                    <label for="driver_id">Driver</label>
                                                                                    <select id="driver_id" class="form-control select2" required name="driver_id">
                                                                                        @if(isset($driver_list))
                                                                                            @foreach($driver_list as $key => $value)
                                                                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                                                                            @endforeach
                                                                                        @endif
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
                                                                </div>
                                                            </div>
                                                             <div class="row" style="margin-top:50px">
                                                                <div class="col-md-12">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No.</th>
                                                                                <th>Driver</th>
                                                                                <th>From Date</th>
                                                                                <th>To Date</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if(isset($driver_allocated_list))
                                                                                @foreach($driver_allocated_list as $key => $value)
                                                                                    <tr>
                                                                                        <td>{{$key+1}}</td>
                                                                                        <td>{{$value->driver_name}}</td>
                                                                                        <td>{{($value->from_date!=null)?date('d/m/Y',strtotime($value->from_date)):''}}</td>
                                                                                        <td>{{($value->to_date!=null)?date('d/m/Y',strtotime($value->to_date)):''}}</td>
                                                                                        <td>
                                                                                            <button type="button" class="btn btn-primary btn-xs allocate" data-toggle="modal" data-target="#large-Modal" data-id="{{$value->allocated_id}}" data-from="{{$value->from_date}}" data-to="{{$value->to_date}}" data-driver="{{$value->driver_id}}"><span class="fa fa-edit"></span>
                                                                                            </button>
                                                                                        </td>
                                                                                        </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                           @else
                                                            access only whicle vehicle edit
                                                           @endif

                                                        </div> {{-- tab pane close --}}
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                           
                    </div> {{-- col close --}}
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
                <h4 class="modal-title">EDIT DRIVER</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <input type="hidden" id="allocated_id">
                    <input type="hidden" id="model_vehicle_id" value="{{$vehicle_detail->id ?? ''}}">
                    <label for="model" class="col-sm-2 col-form-label">Driver</label>
                    <div class="col-sm-6">
                        <select id="model_driver_id" class="form-control select2" required name="model_driver_id" style="width: 100%">
                            @if(isset($vehicle_detail->id))
                                @foreach($driver_list as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
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
    $(document).ready(function() {
        $("#main_due").validate();
        $("#main_doc").validate();
        $('.select2').select2();
    });

    $(".allocate").click(function() {
        $("#allocated_id").val($(this).data('id'));
        $("#model_driver_id").val($(this).data('driver'));
        $("#model_from_date").val($(this).data('from'));
        $("#model_to_date").val($(this).data('to'));
        $('#model_driver_id').trigger('change');
    });

    $('.saveModel').click(function(){
        var driver_id = $('#model_driver_id').val();
        var vehicle_id = $('#model_vehicle_id').val();
        var from_date = $('#model_from_date').val();
        var to_date = $('#model_to_date').val();
        var id = $('#allocated_id').val();
        
        if(model_driver_id == ''){
            $("#nameerror").html("Please select driver");
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