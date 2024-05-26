@extends('layouts.app')
@section('title',(isset($editData))?'Edit Bill Payment Receipt':'Add Bill Payment Receipt')
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
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                                @if(isset($editData->id))
                                                    {{-- <a class="btn waves-effect waves-light btn-warning float-right ml-1" onclick="printLr({{$editData->id}})"><i class="feather icon-printer" style="color: white;"></i></a> --}}
                                                    <a href="{{route('bill.payment.receipt.add')}}" class="btn waves-effect waves-light btn-primary float-right "><i class="icofont icofont-plus"></i>Add New </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" 
                                        action=" {{ (isset($editData)) ? route('bill.payment.receipt.update',base64_encode($editData->id)) : route('bill.payment.receipt.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($editData) &&  !empty($editData))
                                          {{-- disabled fields inputfields edit valueds --}}  
                                         <input type="hidden" name="party_id" id="party_id" value="{{(isset($editData->party_id))?$editData->party_id:''}}">
                                         <input type="hidden" name="bill_id" id="bill_id" value="{{(isset($editData->bill_id))?$editData->bill_id:''}}">
                                          {{-- disabled fields inputfields edit valueds --}}  

                                        @endif
                                        

                                         <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label>Receipt No</label>
                                               <input type="text" name="receipt_no" class="form-control"  placeholder="Receipt No" id="receipt_no" value="{{(isset($receipt_no))?$receipt_no:$new_receipt_no}}" required readonly>
                                               <input type="hidden" name="receipt_no_suffix" value="{{(isset($new_suffix))?$new_suffix:''}}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Receipt Date</label>
                                                <input type="date" name="receipt_date" class="form-control" id="receipt_date" value="{{(isset($receipt_date))?$receipt_date:date('Y-m-d')}}" >
                                            </div>
                                            
                                            <div class="form-group col-md-2">
                                                <label>Party</label>
                                                <select name="party_id" id="party_id" class="form-control select2" style="width: 100%;"  required 
                                                onchange="getBills()" {{(!empty($editData))?'disabled':''}}>
                                                    <option value="">CHOOSE PARTY</option>
                                                    @if(!empty($parties))
                                                        @foreach($parties as $k =>$row)   
                                                            <option value="{{$row->id}}" 
                                                                    {{($row->id==(isset($party_id)?$party_id:0))?'selected':''}}
                                                            >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                             
                                            </div>
                                            <?php 
                                            $selectedBillRemainAmt=0;
                                            ?>
                                            <div class="form-group col-md-2">
                                                <label>Bill No</label>
                                                <select name="bill_id" id="bill_id" class="form-control filter-input-select select2" style="width:100%;" onchange="getBillAttr();validateReceivedAmt();" {{(!empty($editData))?'disabled':''}}>
                                                    <option value="" remain_amount="" >CHOOSE BILL NO</option>
                                                    @if(!empty($editSelectedPartyBills))
                                                        @foreach($editSelectedPartyBills as $k =>$row) 
                                                            <?php
                                                                if($row->id==(isset($bill_id)?$bill_id:0)){
                                                                    $selectedBillRemainAmt=$row->remain_amount;
                                                                }
                                                            ?>
                                                            <option 
                                                                value="{{$row->id}}"
                                                                {{($row->id==(isset($bill_id)?$bill_id:0))?'selected':''}}
                                                                remain_amount="{{$row->remain_amount}}"
                                                            >
                                                                {{$row->bill_no}}
                                                            </option>       
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </div>  
                                            <div class="form-group col-md-2">
                                               <label>Bill Remaining Amount</label>
                                               <input type="text" class="form-control" name="bill_remain_amount" 
                                                     placeholder="Bill Remaining Amount" id="bill_remain_amount" 
                                                     value="{{($selectedBillRemainAmt+((isset($received_amt))?$received_amt:0))}}"  readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                               <label>Received Amount</label>
                                               <input type="text" class="form-control" name="received_amt" placeholder="Received Amount" id="received_amt" 
                                                      value="{{(isset($received_amt))?$received_amt:''}}" onchange="validateReceivedAmt();">
                                               <span style="color:red;" id="received_amt_error"></span>
                                            </div>

                                             <div class="form-group col-md-2">
                                                <label>Transaction Type</label>
                                                <select name="transaction_type" id="transaction_type" class="form-control select2" style="width: 100%;"  required {{(isset($editData) && !empty($editData))?'disabled':''}}>
                                                        <option value="">CHOOSE TRANSACTION TYPE</option>
                                                        <option value="Cash" {{("Cash"==(isset($editrcptAccBookEntry->transaction_type)?$editrcptAccBookEntry->transaction_type:''))?'selected':''}}>Cash</option>
                                                        <option value="Bank" {{("Bank"==(isset($editrcptAccBookEntry->transaction_type)?$editrcptAccBookEntry->transaction_type:''))?'selected':''}}>Bank</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Account Type</label>
                                                <select name="account_type_id" id="account_type_id" class="form-control select2" style="width: 100%;"  required {{(isset($editData) && !empty($editData))?'disabled':''}}>
                                                    <option value="">CHOOSE ACCOUNT TYPE</option>
                                                    @if(!empty($AccountType))
                                                        @foreach($AccountType as $k =>$row)   
                                                            <option value="{{$row->id}}" {{($row->id==(isset($editrcptAccBookEntry->account_type_id)?$editrcptAccBookEntry->account_type_id:0))?'selected':''}} >
                                                                {{$row->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if(isset($editrcptAccBookEntry) && !empty($editrcptAccBookEntry))
                                                <input type="hidden" name="transaction_type" value="{{$editrcptAccBookEntry->transaction_type}}">
                                                <input type="hidden" name="account_type_id" value="{{$editrcptAccBookEntry->account_type_id}}">
                                            @endif

                                           {{--  <div class="form-group col-md-2">
                                                <label>Vehicle no</label>
                                                <select name="vehicle_id" id="vehicle_id" class="form-control filter-input-select select2" style="width:100%;">
                                                    <option value="">CHOOSE VEHICLE NO</option>       
                                                    @if(!empty($vehicles))
                                                    @foreach($vehicles as $k =>$row)   
                                                    <option value="{{$row->id}}">{{$row->name}}</option>       
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>   --}}
                                           {{--  <div class="form-group col-md-2">
                                                <label>MN From Date</label>
                                                <input type="date" class="form-control" id="mn_from_date" value="{{date('Y-m-01')}}" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>MN To Date</label>
                                                <input type="date" class="form-control" id="mn_to_date" value="{{date('Y-m-t')}}" >
                                            </div> --}}
                                           
                                        </div>
                                        {{--  <div class="form-row">
                                            <div class="col-md-12">
                                                <table class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="check" id="checkAll" onchange="calcSelectedTripFreight()"></th>
                                                            <th>MN No.</th>
                                                            <th>MN Date</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody id="tableMNData"></tbody>   
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" align="right">Total</th>
                                                            <th id="total_freight"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                         </div>    --}}
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('bill.payment.receipt.list')}}"  class="btn btn-danger">CANCEL</a>

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
    // $(document).ready(function(){
    //    getPartyTrips(); 
    // });
    
    function getBills(){
        var party_id = $("#party_id").val();
        if(party_id==''){
            return false;
        }

        $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{ route('bill.payment.receipt.get.party.wise.bills') }}',
                data: {
                        "_token": "{{ csrf_token() }}",
                        'party_id': party_id,
                },
                dataType:"json",      
                success: function (response) {
                    var options='<option value="" remain_amount="">CHOOSE BILL NO</option>';
                    $.each(response,function(k,row){
                        options+='<option value="'+row['id']+'" remain_amount="'+row['remain_amount']+'">'+row['bill_no']+'</option>';
                    });

                    $("#bill_id").html(options).select2();
                }
        });

    }//func close

    function getBillAttr(){
        var remain_amount = $('option:selected', "#bill_id").attr('remain_amount');
        remain_amount=(remain_amount!='')?parseFloat(remain_amount):0;
        // console.log(remain_amount);
        $("#bill_remain_amount").val(remain_amount);
    }

    function validateReceivedAmt(){
        var remain_amount = $('option:selected', "#bill_id").attr('remain_amount');
        remain_amount=(remain_amount!='')?parseFloat(remain_amount):0;

        var received_amt=($("#received_amt").val()!='')?parseFloat($("#received_amt").val()):0;
        if(received_amt>remain_amount){
            $("#received_amt_error").html('received amount should be <'+remain_amount);
            $("#saveData").attr('disabled',true);
        }else{
            $("#received_amt_error").html('');
            $("#saveData").attr('disabled',false);
        }

    }//func close
    

    // function calcSelectedTripFreight(){
    //     checkedTpAmts=[];
          
    //     $("input[name='mn_id[]']:checked").each(function (){
    //         checkedTpAmts.push(parseFloat($(this).attr("freight")));
    //     });

    //     var totalTPAmt=0;
    //     totalTPAmt=checkedTpAmts.reduce((a, b) => a + b, 0);
    //     $("#total_freight").html(totalTPAmt);
        
    //     $("#total_amount").val(totalTPAmt);

    // }

    // $("#checkAll").click(function(){
    //   $('input:checkbox').not(this).prop('checked', this.checked);
    //   if(this.checked==true){
    //     $(".check").attr("checked",true);
    //   }else{
    //     $(".check").attr("checked",false);
    //   }

    // });

</script>
@endsection