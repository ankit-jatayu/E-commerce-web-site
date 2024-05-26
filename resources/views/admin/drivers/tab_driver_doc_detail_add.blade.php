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
                                                     {{--    <li class="nav-item">
                                                            <a class="nav-link {{(Request::segment(1)=='driver-add-vehicle-detail-tab')?'active':''}} " href="{{ (isset($driver_detail->id)) ? route('driver.add.vehicle.detail.tab',['id'=> base64_encode($driver_detail->id)]) : route('driver.add.vehicle.detail.tab')}}">Vehicle detail</a>
                                                        </li> --}}
                                                        @endif
                                                    </ul>

                                                    <div class="tab-content tabs card-block">
                                                       <div class="tab-pane active" id="active_tab" role="tabpanel">
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

</script>
@endsection