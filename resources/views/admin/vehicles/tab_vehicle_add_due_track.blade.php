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
                                            <h5>Vehicle Due Track</h5>
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
                                                      {{--   <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='vehicle-add-driver-tab')?'active':''}} " href="{{ (isset($vehicle_detail->id)) ? route('vehicle.add.driver.detail.tab',['id'=> base64_encode($vehicle_detail->id)]) : route('vehicle.add.driver.detail.tab')}}">Driver Detail</a>
                                                        </li> --}}
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                        <div class="tab-pane {{(Request::segment(1)=='vehicle-add-due-track-tab')?'active':''}} " id="due track" role="tabpanel">
                                                            @if(isset($vehicle_detail->id))
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <form id="main_due" method="post" action="{{route('add.vehicle.due')}}" novalidate="" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="vehicle_id" value="{{$vehicle_detail->id ?? ''}}">
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-4">
                                                                                <label for="due_id">Due Type</label>
                                                                                <select id="due_id" class="form-control select2" required name="due_id">
                                                                                    @foreach($dues as $key => $value)
                                                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                                                    @endforeach
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
                                                                </div>
                                                            </div>
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
                                                                            @if(isset($vehicle_dues))
                                                                            @foreach($vehicle_dues as $key => $value)
                                                                                <tr id="due_row_{{$value->id}}">
                                                                                    <td>{{$key+1}}</td>
                                                                                    <td>{{$value->due_type}}</td>
                                                                                    <td>{{date('d/m/Y',strtotime($value->validity))}}</td>
                                                                                    <td width="5%">
                                                                                        <button type="button" class="dueedt btn btn-danger fa fa-trash" 
                                                                                                onclick="removeExpiry({{$value->id}})">
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
                                                        </div>{{-- tab pane close --}}
                                                        
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

<script type="text/javascript">
    $(document).ready(function() {
        $("#main").validate();
        $("#main_due").validate();
        $("#main_doc").validate();
        $('.select2').select2();
    });

    function removeExpiry(id,e){
            //e.preventDefault();
        if(confirm("Are you sure you want to remove?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('remove.vehicle.due') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    if(data){
                        $("#due_row_"+id).remove();
                    }
                }
            });
        }else{
            return false;
        }
    }

</script>
@endsection