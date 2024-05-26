@extends('layouts.app')

@section('title',(isset($editData->id))?'Edit Route':'Add Route')
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
                                                <h4>{{(isset($editData->id))?'Edit Route':'Add Route'}}</h4>
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
                                    <form id="main" method="post" action=" {{ (isset($editData->id)) ? route('update.route',base64_encode($editData->id)) : route('store.route')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>From</label>
                                                <select name="from_place" id="from_place" class="form-control select2" style="width:100% ;" required>
                                                <option value="" >Choose From Place</option>
                                                    @if(!empty($locations))
                                                        @foreach($locations as $k =>$singledata)   
                                                            <option value="{{$singledata->name}}" {{($singledata->name==(isset($editData->from_place)?$editData->from_place:''))?'selected':''}}>{{$singledata->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Destination 1</label>
                                                <select name="destination_1" id="destination_1" class="form-control select2" style="width:100% ;" required>
                                                <option value="" >Choose Destination 1</option>
                                                    @if(!empty($locations))
                                                        @foreach($locations as $k =>$singledata)   
                                                            <option value="{{$singledata->name}}"
                                                                {{($singledata->name==(isset($editData->destination_1)?$editData->destination_1:''))?'selected':''}}
                                                            >
                                                                {{$singledata->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Destination 2</label>
                                                <select name="destination_2" id="destination_2" class="form-control select2" style="width:100% ;">
                                                <option value="" >Choose Destination 2</option>
                                                    @if(!empty($locations))
                                                        @foreach($locations as $k =>$singledata)   
                                                           <option 
                                                                value="{{$singledata->name}}"
                                                       {{($singledata->name==(isset($editData->destination_2)?$editData->destination_2:''))?'selected':''}}
                                                            >
                                                            {{$singledata->name}}
                                                           </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                             <div class="form-group col-md-3">
                                                <label>Destination 3</label>
                                                <select name="destination_3" id="destination_3" class="form-control select2" style="width:100% ;">
                                                <option value="" >Choose Destination 3</option>
                                                    @if(!empty($locations))
                                                        @foreach($locations as $k =>$singledata)   
                                                            <option 
                                                                value="{{$singledata->name}}" 
                                                                {{($singledata->name==(isset($editData->destination_3)?$editData->destination_3:''))?'selected':''}}>
                                                                {{$singledata->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">SAVE</button>
                                        <a href="{{route('list.route')}}"  class="btn btn-danger">CANCEL</a>
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