@extends('layouts.app')

@section('title',(isset($user_detail->id))?'Edit User':'Add User')
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
                                                <h4>USER</h4>
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
                                    <form id="main" method="post" action=" {{ (isset($user_detail->id)) ? route('update.user',base64_encode($user_detail->id)) : route('store.user')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                             <label for="name">Name</label>
                                             <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ $user_detail->name ?? '' }}" required>
                                             @error('name')
                                             <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                             @enderror
                                         </div>

                                         <div class="form-group col-md-2">
                                            <label for="mobile_no">Contact No</label>
                                            <input type="text" name="mobile_no" class="form-control numbers-only" id="mobile_no" value="{{ $user_detail->mobile_no ?? '' }}" placeholder="Phone no" required maxlength="10">
                                            @error('email')
                                            <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-3">
                                         <label for="email">Email</label>
                                         <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ $user_detail->email ?? '' }}" required>
                                         @error('email')
                                         <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                         @enderror
                                     </div>

                                     <div class="form-group col-md-2">
                                         <label for="password">Password</label>
                                         <input type="password" name="password" class="form-control" id="password" value="" placeholder="Password" {{ (isset($user_detail->id)) ? '' : 'required' }} >
                                         @error('password')
                                         <label class="invalid-feedback" style="display: block;">{{ $message }}</label>
                                         @enderror
                                     </div>

                                     <div class="form-group col-md-2">
                                         <label for="password-confirm">Confirm Password </label>
                                         <input type="password" name="password_confirmation" class="form-control" id="password-confirm" value="" placeholder="Confirm Password" data-rule-equalTo="#password">

                                     </div>
                                 </div>

                                 <div class="form-row">
                                    <label for="password-confirm">Web Modules </label>
                                </div>

                                <table id="datatable" class="table table-bordered table-hover display nowrap">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>View</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>Export</th>

                                        </tr>
                                    </thead>
                                    <tbody id="addMoreTableRow">
                                        @foreach($web_modules as $k =>$singledata)   
                                        <?php 
                                        $projectModuleID=$singledata['id'];

                                        $isCheckedView='';
                                        $isCheckedAdd='';
                                        $isCheckedEdit='';
                                        $isCheckedDelete='';
                                        $isCheckedExport='';
                                        if(isset($selectedModuleIDs) && !empty($selectedModuleIDs)){

                                            if(in_array($projectModuleID, $selectedModuleIDs)==1){
                                                $selectedUserProjectModule=\App\Models\UserProjectModules::where('user_id',$user_detail->id)
                                                ->where('project_module_id',$projectModuleID)
                                                ->first();
                                                $isCheckedView=($selectedUserProjectModule->is_view==1)?'checked':'';
                                                $isCheckedAdd=($selectedUserProjectModule->is_create==1)?'checked':'';
                                                $isCheckedEdit=($selectedUserProjectModule->is_edit==1)?'checked':'';
                                                $isCheckedDelete=($selectedUserProjectModule->is_delete==1)?'checked':'';
                                                $isCheckedExport=($selectedUserProjectModule->is_export==1)?'checked':'';
                                                        } // if close
                                                    } //if close
                                                    

                                                    ?>

                                                    <tr>
                                                        <td>{{$singledata['name']}}</td>
                                                        <td>
                                                            <div class="checkbox-fade fade-in-primary">
                                                                <label>
                                                                    <input type="checkbox" id="view_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][view]" value="{{$singledata['id']}}" {{$isCheckedView}}>
                                                                    <span class="cr">
                                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                  </span>
                                                              </label>
                                                          </div> 
                                                      </td>

                                                      <td>
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input type="checkbox" id="add_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][add]" value="{{$singledata['id']}}" {{$isCheckedAdd}}>
                                                                <span class="cr">
                                                                  <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                              </span>
                                                          </label>
                                                      </div> 
                                                  </td>

                                                  <td>
                                                    <div class="checkbox-fade fade-in-primary">
                                                        <label>
                                                            <input type="checkbox" id="edit_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][edit]" value="{{$singledata['id']}}" {{$isCheckedEdit}}>
                                                            <span class="cr">
                                                              <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                          </span>
                                                      </label>
                                                  </div> 
                                              </td>

                                              <td>
                                                <div class="checkbox-fade fade-in-primary">
                                                    <label>
                                                        <input type="checkbox" id="delete_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][delete]" value="{{$singledata['id']}}" {{$isCheckedDelete}}>
                                                        <span class="cr">
                                                          <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                      </span>
                                                  </label>
                                              </div> 
                                          </td>

                                          <td>
                                            <div class="checkbox-fade fade-in-primary">
                                                <label>
                                                    <input type="checkbox" id="export_{{$singledata['id']}}" name="module[{{$singledata['id']}}][export]" value="{{$singledata['id']}}" {{$isCheckedExport}}>
                                                    <span class="cr">
                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                  </span>
                                              </label>
                                          </div> 
                                      </td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>

                         {{--  <div class="form-row">
                            <label style="font-weight:bold;font-size:15px;">App Modules</label>
                        </div>  --}}          

                      {{--   <table id="datatable" class="table table-bordered table-hover display nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>View</th>
                                                    <th>Add</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                    <th>Export</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="addMoreTableRow">
                                                @foreach($app_modules as $k =>$singledata)   
                                                <?php 
                                                $projectModuleID=$singledata['id'];

                                                $isCheckedView='';
                                                $isCheckedAdd='';
                                                $isCheckedEdit='';
                                                $isCheckedDelete='';
                                                $isCheckedExport='';
                                                if(isset($select_modules) && !empty($select_modules)){

                                                    if(in_array($projectModuleID, $select_modules)==1){
                                                        $selectedUserProjectModule=\App\Models\UserProjectModules::where('user_id',$user_detail->id)
                                                        ->where('project_module_id',$projectModuleID)
                                                        ->first();
                                                        $isCheckedView=($selectedUserProjectModule->is_view==1)?'checked':'';
                                                        $isCheckedAdd=($selectedUserProjectModule->is_create==1)?'checked':'';
                                                        $isCheckedEdit=($selectedUserProjectModule->is_edit==1)?'checked':'';
                                                        $isCheckedDelete=($selectedUserProjectModule->is_delete==1)?'checked':'';
                                                        $isCheckedExport=($selectedUserProjectModule->is_export==1)?'checked':'';
                                                        } // if close
                                                    } //if close
                                                    

                                                    ?>

                                                    <tr>
                                                        <td>{{$singledata['name']}}</td>
                                                        <td>
                                                            <div class="checkbox-fade fade-in-primary">
                                                                <label>
                                                                    <input type="checkbox" class="checkBoxClass" id="view_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][view]" value="{{$singledata['id']}}" {{$isCheckedView}}>
                                                                    <span class="cr">
                                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                  </span>
                                                              </label>
                                                          </div> 
                                                      </td>

                                                      <td>
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input type="checkbox"  class="checkBoxClass" id="add_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][add]" value="{{$singledata['id']}}" {{$isCheckedAdd}}>
                                                                <span class="cr">
                                                                  <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                              </span>
                                                          </label>
                                                      </div> 
                                                  </td>

                                                  <td>
                                                    <div class="checkbox-fade fade-in-primary">
                                                        <label>
                                                            <input type="checkbox" class="checkBoxClass" id="edit_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][edit]" value="{{$singledata['id']}}" {{$isCheckedEdit}}>
                                                            <span class="cr">
                                                              <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                          </span>
                                                      </label>
                                                  </div> 
                                              </td>

                                              <td>
                                                <div class="checkbox-fade fade-in-primary">
                                                    <label>
                                                        <input type="checkbox" class="checkBoxClass" id="delete_{{$singledata['id']}}"  name="module[{{$singledata['id']}}][delete]" value="{{$singledata['id']}}" {{$isCheckedDelete}}>
                                                        <span class="cr">
                                                          <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                      </span>
                                                  </label>
                                              </div> 
                                          </td>

                                          <td>
                                            <div class="checkbox-fade fade-in-primary">
                                                <label>
                                                    <input type="checkbox" class="checkBoxClass" id="export_{{$singledata['id']}}" name="module[{{$singledata['id']}}][export]" value="{{$singledata['id']}}" {{$isCheckedExport}}>
                                                    <span class="cr">
                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                  </span>
                                              </label>
                                          </div> 
                                      </td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table> --}}


                        <button type="submit" class="btn btn-primary">SAVE</button>
                        <a href="{{route('list.user')}}"  class="btn btn-danger">CANCEL</a>
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