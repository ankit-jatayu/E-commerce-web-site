@extends('layouts.app')
@section('title',(isset($editData))?'Edit Party':'Add Party')
@section('content')
    <?php 
        if(isset($editData)){
            extract($editData);
        } //if close

    ?>
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
                                                <h4>{{$title}}</h4>
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

                                    <form id="main" method="post" 
                                        action=" {{ (isset($editData)) ? route('party.update',base64_encode($editData['id'])) : route('party.store')}}"
                                        novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Party Name <span  style="color:red">*</span></label>
                                                <input type="text" name="name" class="form-control" id="name" 
                                                       value="{{(isset($name))?$name:''}}" placeholder="Party Name" 
                                                       required 
                                                >
                                            </div>
                                            
                                            <div class="form-group col-md-2">
                                              <label>Party Type <span  style="color:red">*</span></label>
                                              <select name="party_type_id[]" id="party_type_id" 
                                                    class="form-control select2" style="width: 100%;" 
                                                    required multiple data-placeholder="Choose Party Type"
                                              >
                                                    @if(!empty($partyTypes))
                                                    @foreach($partyTypes as $k =>$row)   
                                                    <option 
                                                        value="{{$row->id}}"
                                                        @selected(in_array($row->id,(isset($selectedPartyTypeIds))?$selectedPartyTypeIds:[]))
                                                    >
                                                        {{$row->name}}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                           
                                            <div class="form-group col-md-2">
                                                <label>Primary No.<span  style="color:red">*</span></label>
                                                <input type="text" name="phone_no" class="form-control integers-only" id="phone_no" value="{{(isset($phone_no))?$phone_no:''}}" required maxlength="10">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>GST No</label>
                                                <input type="text" name="gst_no" class="form-control" id="gst_no" value="{{(isset($gst_no))?$gst_no:''}}">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>PAN No <span  style="color:red">*</span></label>
                                                <input type="text" name="pan_no" class="form-control" id="pan_no" value="{{(isset($pan_no))?$pan_no:''}}" required>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" id="city" value="{{(isset($city))?$city:''}}">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control" id="pincode" value="{{(isset($pincode))?$pincode:''}}">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>State Name</label>
                                                <input type="text" name="state_name" class="form-control" id="state_name" value="{{(isset($state_name))?$state_name:''}}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>State Code</label>
                                                <input type="text" name="state_code" class="form-control" id="state_code" value="{{(isset($state_code))?$state_code:''}}">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>TDS %</label>
                                                <input type="text" name="tds_per" class="form-control" id="tds_per" value="{{(isset($tds_per))?$tds_per:''}}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Address Line 1 <span  style="color:red">*</span></label>
                                                <textarea name="address_line_1" class="form-control" id="address_line_1" required>{{(isset($address_line_1))?$address_line_1:''}}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Address Line 2</label>
                                                <textarea name="address_line_2" class="form-control" id="address_line_2">{{(isset($address_line_2))?$address_line_2:''}}</textarea>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <h4>Bank Details</h4>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control" id="bank_name" 
                                                       value="{{(isset($bank_name))?$bank_name:''}}" placeholder="Bank Name" 
                                                >
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>IFSC Code</label>
                                                <input type="text" name="ifsc_code" class="form-control" id="ifsc_code" 
                                                       value="{{(isset($ifsc_code))?$ifsc_code:''}}" placeholder="IFSC CODE" 
                                                >
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Branch Name</label>
                                                <input type="text" name="branch_name" class="form-control" id="branch_name" value="{{(isset($branch_name))?$branch_name:''}}" placeholder="Branch Name" 
                                                >
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Account No</label>
                                                <input type="text" name="account_no" class="form-control" id="account_no" value="{{(isset($account_no))?$account_no:''}}" placeholder="Account No" 
                                                >
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Beneficiary Name</label>
                                                <input type="text" name="beneficiary_name" class="form-control" id="beneficiary_name" value="{{(isset($beneficiary_name))?$beneficiary_name:''}}" placeholder="Beneficiary Name" 
                                                >
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                              <label>Account Type</label>
                                              <select name="account_type" id="account_type" class="form-control select2" style="width:100%">
                                                    <option value="" >CHOOSE ACCOUNT TYPE</option>
                                                    <option value="Saving" {{('Saving'==(isset($account_type)?$account_type:''))?'selected':''}}>
                                                        Saving
                                                    </option>
                                                    <option value="Current" {{('Current'==(isset($account_type)?$account_type:''))?'selected':''}}>Current
                                                    </option>
                                                    
                                              </select>
                                            </div>
                                        </div>



                                                    <div class="form-row">
                                                        <h4>Party Additional Details</h4>
                                                    
                                                    <table id="datatable" class="table table-bordered table-hover display nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Name <span style="color:red">*</span></th>
                                                                <th>Designation<span style="color:red">*</span></th>
                                                                <th>Phone no<span style="color:red">*</span></th>
                                                                <th>Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr id="row-block-0">
                                                               <td>
                                                                 <input type="text" class="form-control field-main" id="name-main"  placeholder="Name" >
                                                               </td>
                                                               <td>
                                                                 <input type="text" class="form-control field-main" id="designation-main"  placeholder="Designation" >
                                                               </td>
                                                               <td>
                                                                 <input type="text" class="form-control field-main number integers-only" id="phone_no-main"  placeholder="Phone No" maxlength="10">
                                                               </td>
                                                               <td>
                                                                 <input type="email" class="form-control field-main" id="email-main"  placeholder="Email" >
                                                               </td>
                                                            </tr>

                                                            <tr id="row-block-0">
                                                                <td colspan="3">
                                                                    <span id="main-error" style="color: red;"></span>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-sm" onclick="appendTableRow('event')" id="storeData">Add</button> 
                                                                    <button class="btn btn-warning btn-sm" onclick="updateTableRow('event')" id="updateData" disabled>Change</button>
                                                                    <input type="hidden" id="updateRow" value="0">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table id="datatable" class="table table-bordered table-hover display nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name <span style="color:red">*</span></th>
                                                                <th>Designation<span style="color:red">*</span></th>
                                                                <th>Phone no<span style="color:red">*</span></th>
                                                                <th>Email<span style="color:red">*</span></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="addMoreTableRow">
                                                            @if(isset($childDetail) && !empty($childDetail))
                                                            @foreach($childDetail as $key => $detail)
                                                            <tr id="row-block-<?=$key+1?>">
                                                                <td><?=($key+1)?></td>

                                                                <td>
                                                                    <input type="text" name="childDetail[{{$key+1}}][name]" id="name-{{$key+1}}" class="form-control" value="{{$detail->name}}" placeholder="Name"   readonly/>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="childDetail[{{$key+1}}][designation]" id="designation-{{$key+1}}" class="form-control" value="{{$detail->designation}}" placeholder="Designation"  readonly/>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="childDetail[{{$key+1}}][phone_no]" id="phone_no-{{$key+1}}" class="form-control" value="{{$detail->phone_no}}" placeholder="Phone No"  readonly/>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="childDetail[{{$key+1}}][email]" id="email-{{$key+1}}" class="form-control" value="{{$detail->email}}" placeholder="Email"  readonly/>
                                                                </td>
                                                            <td>
                                                              <button class="btn btn-primary btn-sm" onclick="editTableRow('event',{{$key+1}})">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                              </button>
                                                              <button class="btn btn-danger btn-sm" onclick="deleteRow('event',{{$key+1}},{{$detail->id}})">
                                                                    <i class="fa fa-trash"></i>
                                                              </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-row party_document_main" style="margin-top:20px">
                                            <div class="col-md-12">
                                                <h4>Party Documents</h4>
                                            </div>
                                             @if(isset($selectedPartyDocs) && !empty($selectedPartyDocs))
                                                @foreach($selectedPartyDocs as $key => $detail)
                                                <div class="col-md-12" id="old_party_doc_block_{{$detail->id}}">
                                                   <div class="form-row">
                                                       <div class="form-group col-md-3">
                                                          <label>Document Name</label>
                                                          <input type="text"  class="form-control doc_name" placeholder="Document name" value="{{$detail->name}}" readonly>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                          <label>Document Upload</label><br>
                                                          {{-- <input type="file"  class="doc_file" > --}}
                                                          @if(isset($detail->doc_file) && $detail->doc_file!='')
                                                                <a href="{{URL::to('public/uploads/party_docs/'.$detail->doc_file)}}" target="_blank">
                                                                    <img src ="{{asset('admin/images/Download-Icon.png')}}" class="img-40">
                                                                </a>
                                                          @endif
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <button class="btn btn-danger" style="margin-top: 30px;" onclick="deletePartyDoc('event',{{$detail->id}})" id="storeData">
                                                                <i class="feather icon-trash"></i></button> 
                                                        </div>
                                                   </div>     
                                                </div>
                                            @endforeach
                                            @endif

                                            <div class="col-md-12" id="party_doc_block_0">
                                               <div class="form-row">
                                                   <div class="form-group col-md-3">
                                                      <label>Document Name</label>
                                                      <input type="text" name="document_name[0]" class="form-control doc_name" value="" placeholder="Document name">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                      <label>Document Upload</label>
                                                      <input type="file" name="document_file[0]" class="doc_file" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <button class="btn btn-primary" style="margin-top: 30px;" onclick="addMoreDoc('event')" id="storeData">
                                                            <i class="feather icon-plus-circle"></i></button> 
                                                    </div>
                                               </div>     
                                            </div>
                                                    
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('party.list')}}"  class="btn btn-danger">CANCEL</a>
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
<?php 
    $ChildIndex=1;
    if((isset($editData) && !empty($editData))){
        $ChildIndex=count($childDetail) + 1;
    }

?>
<input type="hidden" id="ChildIndex" value="<?=$ChildIndex?>" >

<script type="text/javascript">
	$(document).ready(function() {
		getPaymentType();
	});

  function getVehicleData() {
     var trip_id= $('#vehicle_id option:selected').attr("trip_id");   
     var driver_id= $('#vehicle_id option:selected').attr("driver_id");   
     var driver_name= $('#vehicle_id option:selected').attr("driver_name");   
     var budgeted_advance= $('#vehicle_id option:selected').attr("budgeted_advance");   
     var budgeted_diesel= $('#vehicle_id option:selected').attr("budgeted_diesel");   
     $("#trip_id").val(trip_id);
     $("#driver_id").val(driver_id);
     $("#driver_name").val(driver_name);
     $("#budgeted_advance").val(budgeted_advance);
     $("#budgeted_diesel").val(budgeted_diesel);
     
     $("#budgeted_advance_label").text(budgeted_advance);
     $("#budgeted_diesel_label").text(budgeted_diesel);
  }

  function getPaymentType(){
       var paymenttype=$("#payment_type_id option:selected").text();
       if(paymenttype=='Driver Advance'){
            $("#total_amount").val(0); 
            $('.cash_block').show();
            $('.qty_block').hide();
            $(".budget_advance_label_block").show();
            $(".budget_diesel_label_block").hide();
       }else if(paymenttype=='Diesel'){
            $("#total_amount").val(0); 
            $('.qty_block').show();
            $('.cash_block').hide();
            $('.additional_block').hide();
            $(".budget_diesel_label_block").show();
            $(".budget_advance_label_block").hide();
       }else{
            $('.qty_block').hide();
            $('.cash_block').hide();
            $('.card_block').hide();
            $('.additional_block').show();
            $(".budget_diesel_label_block").hide();
            $(".budget_advance_label_block").hide();
       }
       
       preventEnterOverBudgetAdvance();
       preventEnterOverBudgetDiesel();
       showIsAuthorisedBlock();
  }

  function showIsAuthorisedBlock(){
    var additional_cash_amount = ($("#additional_cash_amount").val()!='')?parseFloat($("#additional_cash_amount").val()):0;
    var additional_card_amount = ($("#additional_card_amount").val()!='')?parseFloat($("#additional_card_amount").val()):0;
    var additional_qty = ($("#additional_qty").val()!='')?parseFloat($("#additional_qty").val()):0;
    var paymenttype=$("#payment_type_id option:selected").text();
    if(paymenttype=='Driver Advance' || paymenttype=='Diesel'){
      if(additional_cash_amount>0 || additional_card_amount>0 || additional_qty>0){
          $(".authorised_by_block").show();
      }else{
          $(".authorised_by_block").hide();
      }
    }
    
  }//func close

  function calcTotalAmt(){
    var cash_amount = ($("#cash_amount").val()!='')?parseFloat($("#cash_amount").val()):0;
    var additional_cash_amount = ($("#additional_cash_amount").val()!='')?parseFloat($("#additional_cash_amount").val()):0;
    var card_amount = ($("#card_amount").val()!='')?parseFloat($("#card_amount").val()):0;
    var additional_card_amount = ($("#additional_card_amount").val()!='')?parseFloat($("#additional_card_amount").val()):0;
    var total=(cash_amount+additional_cash_amount+card_amount+additional_card_amount);
     $("#total_amount").val(total.toFixed(2)); 
  }

  function calcTotalQty(){
    var qty = ($("#qty").val()!='')?parseFloat($("#qty").val()):0;
    var additional_qty = ($("#additional_qty").val()!='')?parseFloat($("#additional_qty").val()):0;
    var total=(qty+additional_qty);
     $("#total_qty").val(total.toFixed(2)); 
  }

  function preventEnterOverBudgetAdvance(){
    var cash_amount = ($("#cash_amount").val()!='')?parseFloat($("#cash_amount").val()):0;
    var card_amount = ($("#card_amount").val()!='')?parseFloat($("#card_amount").val()):0;
    var total=(cash_amount+card_amount);
    var budgeted_advance =($("#budgeted_advance").val()!='')?parseFloat($("#budgeted_advance").val()):0;
    if(total>budgeted_advance){
        $("#saveData").prop("disabled",true);
        alert('invalid amount');
        $("#cash_amount").val('');
        $("#card_amount").val('');
        $("#cash_amount").focus();
    }else{
        $("#saveData").prop("disabled",false);
    }
  }
   
  function preventEnterOverBudgetDiesel(){
    var qty = ($("#qty").val()!='')?parseFloat($("#qty").val()):0;
    var budgeted_diesel =($("#budgeted_diesel").val()!='')?parseFloat($("#budgeted_diesel").val()):0;
    if(qty>budgeted_diesel){
        $("#saveData").prop("disabled",true);
        alert('invalid quantity');
        $("#qty").val('');
        $("#qty").focus();
    }else{
        $("#saveData").prop("disabled",false);
    }
  }




   function appendTableRow(e){
        event.preventDefault();
        var index=((parseInt($("#ChildIndex").val()))>0)?(parseFloat($("#ChildIndex").val())):0;
        
        var name = ($('#name-main').val()!='')?$('#name-main').val():'';
        var designation = ($('#designation-main').val()!='')?$('#designation-main').val():'';
        var phone_no = ($('#phone_no-main').val()!='')?$('#phone_no-main').val():'';
        var email = ($('#email-main').val()!='')?$('#email-main').val():'';
        
        $("#main-error").html('');
        if(name!='' && designation !='' && phone_no !=''){

        }else{
           $("#main-error").html('* fields are required');
           return false;
        }

        var html='';

        html+='<tr id="row-block-'+index+'">';
        html+=' <td>';
        html+=(index);
        html+='</td>';
        
        html+='<td>';
        html+='<input type="text" name="childDetail['+index+'][name]" id="name-'+index+'" class="form-control" value="'+name+'" placeholder="name"   readonly/>';
        html+='</td>';
        
        html+='<td>';
        html+='<input type="text" name="childDetail['+index+'][designation]" id="designation-'+index+'" class="form-control" value="'+designation+'" placeholder="designation"   readonly/>';
        html+='</td>';
        
        html+='<td>';
        html+='<input type="text" name="childDetail['+index+'][phone_no]" id="phone_no-'+index+'" class="form-control" value="'+phone_no+'" placeholder="phone_no"   readonly/>';
        html+='</td>';

        html+='<td>';
        html+='<input type="text" name="childDetail['+index+'][email]" id="email-'+index+'" class="form-control" value="'+email+'" placeholder="email"   readonly/>';
        html+='</td>';

        html+=' <td>';
        html+='<button class="btn btn-primary btn-sm" onclick="editTableRow(event,'+index+')"><i class="fa fa-pencil-square-o"></i></button>&nbsp;&nbsp;';
        html+='<button class="btn btn-danger btn-sm" onclick="removeRow(event,'+index+')"><i class="fa fa-trash"></i></button>';
        html+='</td>';

        html+='</tr>';
        $("#addMoreTableRow").append(html);
        $(".select2").select2();

        index++;

        $("#ChildIndex").val(index);

        $('.field-main-select').val('').select2();
        $('.field-main').val('');
        //finalCalcAmount();
    }

    function editTableRow(e,num){
      event.preventDefault();

      var name = $('#name-'+num).val();
      var designation = $('#designation-'+num).val();
      var phone_no = $('#phone_no-'+num).val();
      var email = $('#email-'+num).val();
     
      //$('#item_id-main').val(item_id).select2();
      $('#name-main').val(name);
      $('#designation-main').val(designation);
      $('#phone_no-main').val(phone_no);
      $('#email-main').val(email);
      
      $('#updateRow').val(num);

      $('#storeData').prop('disabled', true);
      $('#updateData').prop('disabled', false);
  }

  function updateTableRow(e){
      event.preventDefault();

      var num = $('#updateRow').val();

      var name = ($('#name-main').val()!='')?$('#name-main').val():'';
      var designation = ($('#designation-main').val()!='')?$('#designation-main').val():'';
      var phone_no = ($('#phone_no-main').val()!='')?$('#phone_no-main').val():'';
      var email = ($('#email-main').val()!='')?$('#email-main').val():'';

      $("#main-error").html('');

      if(name!='' && designation !='' && phone_no !=''){

      }else{
         $("#main-error").html('* fields are required');
         return false;
     }
   
      $('#name-'+num).val(name);
      $('#designation-'+num).val(designation);
      $('#phone_no-'+num).val(phone_no);
      $('#email-'+num).val(email);
      
      $('#storeData').prop('disabled', false);
      $('#updateData').prop('disabled', true);
      
      $('.field-main-select').val('').select2();
      $('.field-main').val('');
   
  }

  function removeRow(e,num){
    event.preventDefault();
    $("#row-block-"+num).remove();
  }

function deleteRow(e,num,id){
    event.preventDefault();
    if(confirm('are you sure to delete this ?')){
        $.ajax({
            url: "{{ route('party.child.single.delete') }}",
            type: 'POST',
            data:{'id':id,"_token": "{{ csrf_token() }}"},
            success: function(output_string){
                $("#row-block-"+num).remove();
            }
        });
        
    }
}


var docIndex=1;
function addMoreDoc(e){
    event.preventDefault();

    var html='';
    html+='<div class="col-md-12" id="party_doc_block_'+docIndex+'">';
    html+='<div class="form-row">';
    html+='<div class="form-group col-md-3">';
    html+='<label>Document Name</label>';
    html+='<input type="text" name="document_name['+docIndex+']" class="form-control doc_name" value="" placeholder="Document name">';
    html+=' </div>';
    html+='<div class="form-group col-md-3">';
    html+='<label>Document Upload</label>';
    html+=' <input type="file" name="document_file['+docIndex+']" class="doc_file" >';
    html+='</div>';
    html+='<div class="form-group col-md-3">';
    html+='<button class="btn btn-primary" style="margin-top: 30px;" onclick="addMoreDoc(event)" id="storeData">';
    html+='<i class="feather icon-plus-circle"></i>';
    html+='</button>';
    html+='&nbsp;&nbsp;<button class="btn btn-danger" style="margin-top: 30px;" onclick="removeDoc(event,'+docIndex+')" id="storeData">';
    html+='<i class="feather icon-x-circle"></i>';
    html+='</button>';
    html+='</div>';
    html+='</div>';
    html+='</div>';
    
    $(".party_document_main").append(html);
    
    docIndex++;

} //func close
    
function removeDoc(e,index){
    event.preventDefault();
    $('#party_doc_block_'+index).remove();

} //func close

function deletePartyDoc(e,id){
    event.preventDefault();
    if(confirm('are you sure to delete this ?')){
        $.ajax({
            url: "{{ route('party.child.doc.single.delete') }}",
            type: 'POST',
            data:{'id':id,"_token": "{{ csrf_token() }}"},
            success: function(output_string){
                $("#old_party_doc_block_"+id).remove();
            }
        });
        
    }
}
</script>
@endsection