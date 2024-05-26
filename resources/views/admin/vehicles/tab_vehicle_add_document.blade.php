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
                                            <h5>Vehicle Documents</h5>
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
                                                        <div class="tab-pane {{(Request::segment(1)=='vehicle-add-documents-tab')?'active':''}} " id="documents" role="tabpanel">
                                                          @if(isset($vehicle_detail->id))
                                                           <div class="row">
                                                               <div class="col-md-12">
                                                                    <form id="main_doc" method="post" action="{{route('add.vehicle.doc')}}" novalidate="" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="vehicle_id" value="{{$vehicle_detail->id ?? ''}}">
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-4">
                                                                                <label for="document_name">Document</label>
                                                                                <input type="text" name="document_name" class="form-control" id="document_name" required>
                                                                            </div>
                                                                            
                                                                            <div class="form-group col-md-4">
                                                                                <label for="document_file">Select Document</label>
                                                                                <input type="file" name="document_file" class="form-control" id="document_file" required>
                                                                            </div>

                                                                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                                                                <button type="submit" class="btn btn-primary">SAVE</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                               </div>
                                                           </div>
                                                           <div class="row">
                                                              <div class="col-md-12">
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
                                                                            @if(isset($vehicle_doc))
                                                                            @foreach($vehicle_doc as $key => $value)
                                                                                <tr id="doc_row_{{$value->id}}">
                                                                                    <td width="10%">{{$key+1}}</td>
                                                                                    <td>{{$value->document_name}}</td>
                                                                                    <td>
                                                                                        <a 
                                                                                           href="{{Storage::disk('s3')->url('vehicle_doc/'.$value->document_file)}}"
                                                                                           target="_blank"
                                                                                           class="btn btn-success"
                                                                                        > <i class="fa fa-download"></i> 
                                                                                        </a>
                                                                                    </td>
                                                                                    <td width="5%">
                                                                                        <button type="button" class="dueedt btn btn-danger fa fa-trash" 
                                                                                                onclick="removeDoc({{$value->id}})">
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

<script type="text/javascript">
    $(document).ready(function() {
        $("#main_due").validate();
        $("#main_doc").validate();
        $('.select2').select2();
    });

    function removeDoc(id,e){
            //e.preventDefault();
        if(confirm("Are you sure you want to remove?")){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('remove.vehicle.doc') }}',
                data: {'id': id,"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    $("#doc_row_"+id).remove();

                }
            });
        }else{
            return false;
        }
    }
</script>
@endsection