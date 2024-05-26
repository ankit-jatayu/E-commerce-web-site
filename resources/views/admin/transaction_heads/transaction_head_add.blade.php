@extends('layouts.app')
@section('title',(isset($editData))?'Edit  Tramsaction Head':'Add  Tramsaction Head')
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
                                                <a href="{{route('transaction.head.list')}}"><h4>{{$title}}</h4></a>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form id="main" method="post" action=" {{ (isset($editData)) ? route('transaction.head.update',base64_encode($editData->id)) : route('transaction.head.store')}}" novalidate="" enctype="multipart/form-data">
                                        @csrf
                                       
                                         <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>Transaction Type</label>
                                                <select name="transaction_type" id="transaction_type" class="form-control select2" style="width: 100%;"  required>
                                                        <option value="">CHOOSE TRANSACTION TYPE</option>
                                                        <option value="Cash" {{("Cash"==(isset($transaction_type)?$transaction_type:''))?'selected':''}}>Cash</option>
                                                        <option value="Bank" {{("Bank"==(isset($transaction_type)?$transaction_type:''))?'selected':''}}>Bank</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Particulars</label>
                                                <select name="type" id="type" class="form-control select2" style="width: 100%;"  required>
                                                        <option value="">CHOOSE PARTICULARS</option>
                                                        <option value="Income" {{("Income"==(isset($type)?$type:''))?'selected':''}}>Income</option>
                                                        <option value="Expense" {{("Expense"==(isset($type)?$type:''))?'selected':''}}>Expense</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group col-md-3">
                                               <label>Name</label>
                                               <input type="text" name="name" class="form-control"  placeholder="name" id="name" value="{{(isset($name))?$name:''}}" required>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary" id="saveData">SAVE</button>
                                        <a href="{{route('transaction.head.list')}}"  class="btn btn-danger">CANCEL</a>

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
    function calcPTPK(){
        var rate =($("#rate").val()!='')?parseFloat($("#rate").val()):0;
        var distance =($("#distance").val()!='')?parseFloat($("#distance").val()):0;
        var ptpk=(rate/distance);
        $("#ptpk").val(ptpk.toFixed(2));
    }
    
</script>
@endsection