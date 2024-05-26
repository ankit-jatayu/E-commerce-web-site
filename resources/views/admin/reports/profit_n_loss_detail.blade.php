@extends('layouts.app')
@section('title','P&L')
@section('content')
<style type="text/css">
	.error{
		color: red;
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
                                                <h4>Profit & Loss</h4>
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
                                    <table class="table table-striped table-bordered nowrap" width="100%">
                                      <tr>
                                        <td colspan="4" align="center" style="border:2px solid black;">P&L</td>
                                      </tr>
                                      <tr>
                                        <td width="30%" style="border-left:2px solid black;border-bottom:2px solid black;">Particular Expense</td>
                                        <td width="20%" style="border-bottom:2px solid black;">Amount</td>
                                        <td width="30%" style="border-left:2px solid black;border-bottom:2px solid black;">Particular Income</td>
                                        <td width="20%" style="border-right:2px solid black;border-bottom:2px solid black;">Amount</td>

                                      </tr>
                                      
                                      @if($rowCount)
                                      <?php 
                                      $totalExpense=0;
                                      $totalIncome=0;
                                       
                                      ?>
                                      @for ($i = 0; $i < $rowCount; $i++)
                                      <?php 
                                         $expenseHeadName='';
                                         $expenseHeadAmt='';
                                         $incomeHeadName='';
                                         $incomeHeadAmt='';
                                         if(isset($expenseTransHead[$i])){
                                            $expenseHeadName=$expenseTransHead[$i]->name;
                                            $expenseHeadAmt= \App\Models\AccountBook::whereIN('head_type_id',explode(',',$expenseTransHead[$i]->trans_head_ids))->where('is_delete',0)->sum('amount');
                                            $totalExpense+=$expenseHeadAmt;
                                         }
                                         if(isset($incomeTransHead[$i])){
                                            $incomeHeadName=$incomeTransHead[$i]->name;
                                            $incomeHeadAmt= \App\Models\AccountBook::whereIN('head_type_id',explode(',',$incomeTransHead[$i]->trans_head_ids))->where('is_delete',0)->sum('amount');
                                            $totalIncome+=$incomeHeadAmt;


                                         }

                                      ?>
                                        <tr>
                                          <td width="30%" style="border-left:2px solid black;">
                                            {{$expenseHeadName}}
                                          </td>
                                          <td width="20%" >
                                            {{$expenseHeadAmt}}
                                          </td>
                                          <td width="30%" style="border-left:2px solid black;">
                                            {{$incomeHeadName}}
                                          </td>
                                          <td width="20%" style="border-right:2px solid black;">
                                            {{$incomeHeadAmt}}
                                          </td>
                                        </tr>
                                      @endfor
                                      @endif
                                        <tr>
                                          <td colspan="2" style="height:47.5px;border-left:2px solid black;border-right:2px solid black;"></td>
                                          <td colspan="2" style="height:47.5px;border-left:2px solid black;border-right:2px solid black;"></td>
                                        </tr>
                                        <tr>
                                          <td width="30%" style="border-left:2px solid black;">
                                            TOTAL EXPENSE
                                          </td>
                                          <td width="20%" style="background-color:#fb606f;">
                                            {{$totalExpense}}
                                          </td>
                                          <td width="30%" style="border-left:2px solid black;">
                                            TOTAL INCOME
                                          </td>
                                          <td width="20%" style="background-color: #2ed8b6;border-right:2px solid black">
                                            {{$totalIncome}}
                                          </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" style="height:47.5px;border-left:2px solid black;border-right:2px solid black;" ></td>
                                          <td colspan="2" style="height:47.5px;border-left:2px solid black;border-right:2px solid black;" ></td>
                                        </tr>
                                        <tr style="border-bottom:2px solid black;">
                                          <td width="30%" style="border-left:2px solid black;">
                                            LOSS
                                          </td>
                                          <td width="20%" style="background-color:#fb606f;">
                                            @if(($totalExpense-$totalIncome)>0)
                                              {{($totalExpense-$totalIncome)}}
                                            @else
                                            -
                                            @endif
                                          </td>
                                          <td width="30%" style="border-left:2px solid black;">
                                            PROFIT
                                          </td>
                                          <td width="20%" style="background-color: #2ed8b6;border-right:2px solid black;">
                                            @if(($totalIncome-$totalExpense)>0)
                                              {{($totalIncome-$totalExpense)}}
                                            @else
                                            -
                                            @endif
                                          </td>
                                        </tr>
                                    <table>
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
	$(document).ready(function() {
		$("#main").validate();
	});
    
</script>
@endsection