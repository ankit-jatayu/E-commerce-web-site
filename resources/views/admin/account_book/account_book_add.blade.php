@extends('layouts.app')
@section('title',(isset($editData))?'Edit Account Book':'Add Account Book')
@section('content')
<?php 
if(isset($editData) && !empty($editData)){
    extract($editData->toArray());
   
}

?>
<style type="text/css">
    input[type=checkbox] {
    zoom: 1.5;
}
</style>
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
                                        
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('account.book.update',base64_encode($editData->id)) : route('account.book.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                       
                                         <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>Payment Date</label>
                                               <input type="date" name="entry_date" class="form-control decimal-only"  placeholder="Payment Date" id="entry_date" value="{{(isset($entry_date))?$entry_date:date('Y-m-d')}}" required >
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Party</label>
                                                <select name="party_id" id="party_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE PARTY</option>
                                                    @if(!empty($Parties))
                                                        @foreach($Parties as $k =>$row)   
                                                            <option value="{{$row->id}}" {{($row->id==(isset($party_id)?$party_id:0))?'selected':''}} >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                                <label>Transaction Type</label>
                                                <select name="transaction_type" id="transaction_type" class="form-control select2" style="width: 100%;"  required onchange="getTransationHeads()">
                                                        <option value="">CHOOSE TRANSACTION TYPE</option>
                                                        <option value="Cash" {{("Cash"==(isset($transaction_type)?$transaction_type:''))?'selected':''}}>Cash</option>
                                                        <option value="Bank" {{("Bank"==(isset($transaction_type)?$transaction_type:''))?'selected':''}}>Bank</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Account Type</label>
                                                <select name="account_type_id" id="account_type_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE ACCOUNT TYPE</option>
                                                    @if(!empty($AccountType))
                                                        @foreach($AccountType as $k =>$row)   
                                                            <option value="{{$row->id}}" {{($row->id==(isset($account_type_id)?$account_type_id:0))?'selected':''}} >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Particulars</label>
                                                <select name="type" id="type" class="form-control select2" style="width: 100%;"  required onchange="getTransationHeads()">
                                                        <option value="">CHOOSE PARTICULARS</option>
                                                        <option value="Income" {{("Income"==(isset($type)?$type:''))?'selected':''}}>Income</option>
                                                        <option value="Expense" {{("Expense"==(isset($type)?$type:''))?'selected':''}}>Expense</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Transaction Head</label>
                                                <select name="head_type_id" id="head_type_id" class="form-control select2" style="width: 100%;"  required >
                                                    <option value="">CHOOSE TRANSACTION HEAD</option>
                                                    @if(!empty($selectedTransHeads))
                                                        @foreach($selectedTransHeads as $k =>$row)   
                                                            <option value="{{$row->id}}" {{($row->id==(isset($head_type_id)?$head_type_id:0))?'selected':''}} >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Amount</label>
                                               <input type="text" name="amount" class="form-control decimal-only"  placeholder="Amount" id="amount" value="{{(isset($amount))?$amount:''}}" required >
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Branch</label>
                                                <select name="branch" id="branch" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">CHOOSE BRANCH</option>
                                                    <option value="Jodhpur" {{("Jodhpur"==(isset($branch)?$branch:''))?'selected':''}}>Jodhpur</option>
                                                    <option value="Rajsamand" {{("Rajsamand"==(isset($branch)?$branch:''))?'selected':''}}>Rajsamand</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Narration</label>
                                                <textarea type="text" name="narration" class="form-control"  placeholder="Narration" id="narration" value="{{(isset($narration))?$narration:''}}"  >{{(isset($narration))?$narration:''}}</textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Remarks</label>
                                                <textarea type="text" name="remarks" class="form-control"  placeholder="Remarks" id="remarks" value="{{(isset($remarks))?$remarks:''}}"  >{{(isset($remarks))?$remarks:''}}</textarea>
                                            </div>
                                        </div>    
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('account.book.list')}}"  class="btn btn-danger">CANCEL</a>

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
    // function calcPTPK(){
    //     var rate =($("#rate").val()!='')?parseFloat($("#rate").val()):0;
    //     var distance =($("#distance").val()!='')?parseFloat($("#distance").val()):0;
    //     var ptpk=(rate/distance);
    //     $("#ptpk").val(ptpk.toFixed(2));
    // }
    function getTransationHeads(){
        var transaction_type= $('#transaction_type').val();   
        var type= $('#type').val();   
        // $(".transaction_type_error").html('');
        // if(transporter_type == ''){
        //    $(".transaction_type_error").html('field is required*');
        //     return false;
        // }

        if(transaction_type!='' && type!=''){
            $.ajax({
                type: "POST",
                url: '{{ route('account.book.get.transaction.head') }}',
                data: {
                        "_token": "{{ csrf_token() }}",
                        'transaction_type': transaction_type,
                        'type': type,
                },
                dataType: "json",
                success: function (data) {
                    var options = '<option value="">CHOOSE TRANSACTION HEAD</option>';
                    if(data.length>0){
                        $(data).each((index, row) => {
                            options += '<option value="'+row.id+'">'+row.name+'</option>';
                        });
                    } //if close

                    $('#head_type_id').html(options).select2();
                }
             });    
        } 
        
    }
</script>
@endsection