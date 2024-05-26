<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style>
		@page {
		  size: A4;
		}

		.txt-vertical-top-lft{
			vertical-align: top;
			text-align: left;
		}
		.txt-vertical-top-rgt{
			vertical-align: top;
			text-align: right;
		}

		.pl-5{
			padding-left:5px;
		}
		.pl-10{
			padding-left:10px;
		}
		.pr-5{
			padding-right:5px;
		}
		.pt-5{
			padding-top:5px;
		}
		.pt-20{
			padding-top:20px;
		}
		.pt-10{
			padding-top:10px;
		}
		.pt-30{
			padding-top:30px;
		}
		.fnt-bold{
			font-weight:bold;
		}
		.fnt-12{
			font-size:12px;
		}
		.fnt-20{
			font-size:20px;
		}

		.txt-algn-right{
			text-align:right;
		}
		.txt-algn-left{
			text-align:left;
		}
		.txt-algn-center{
			text-align:center;
		}

		.wd-per-50{
			width:50%;
		}
		.wd-per-25{
			width:25%;
		}
		.wd-per-100{
			width:100%;
		}
		.wd-per-10{
			width:10%;
		}
		.wd-per-20{
			width:20%;
		}
		.wd-per-11{
			width:11%;
		}
		.border-collapse{
			border-collapse:collapse;
		}

		.bdr-r-wht{
			border-right:1px solid white;
		}
		.bdr-t-wht{
			border-top:1px solid white;
		}
		.bdr-r-blck{
			border-right:1px solid black;
		}
		.bdr-t-blck{
			border-top:1px solid black;
		}

	</style>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td>
				<div class="txt-algn-right fnt-20 fnt-bold">
					{{$CompanyData->company_name ?? ''}}
				</div>
				<div class="txt-algn-right fnt-12">
					OFFICE NO 102, GOLDEN POINT, PLOT NO 31, SECTOR - 8, GANDHIDHAM - KUTCH - 370201
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="txt-algn-center fnt-bold pt-30">
					INVOICE
				</div>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%" border="1" class="border-collapse" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td colspan="2" rowspan="3" class="pl-5 wd-per-50 fnt-12 txt-vertical-top-lft">
				<div>To,</div>
				<div class="fnt-bold">{{($BillDetail->getSelectedParty)?$BillDetail->getSelectedParty->name:''}}</div>
				<div>
					{{($BillDetail->getSelectedParty)?$BillDetail->getSelectedParty->address_line_1:''}}
				</div>
				<div>
					<span class="fnt-bold">STATE</span> : 
					{{($BillDetail->getSelectedParty->state_name!=null)?$BillDetail->getSelectedParty->state_name:'N/A'}} (CODE :- {{($BillDetail->getSelectedParty->state_code!=null)?$BillDetail->getSelectedParty->state_code:0}})
				</div>
			</td>
			<td class="txt-vertical-top-lft pl-5 wd-per-25 fnt-12">
				<div class="fnt-bold">Invoice No. :</div>
				<div>{{$BillDetail->bill_no ?? 'N/A'}}</div>
			</td>
			<td class="txt-vertical-top-lft pl-5 wd-per-25 fnt-12">
				<div class="fnt-bold">Date :</div>
				<div>
					{{($BillDetail->bill_date!=null)?date('d/m/Y',strtotime($BillDetail->bill_date)):''}}
				</div>
			</td>
		</tr>
		<tr>
			<td class="fnt-12 pl-5">
				<div class="fnt-bold">Purchase Order No. :</div>
				<div style="height:10px;"></div>
			</td>
			<td class="fnt-12 pl-5">
				<div class="fnt-bold">Purchase Order Date. :</div>
				<div style="height:10px;"></div>
			</td>
		</tr>
		<tr>
			<td class="fnt-12 pl-5" colspan="2">
				<div class="" style="height:20px;"></div>
			</td>
		</tr>
		<tr>
			<td class="wd-per-25 fnt-12 pl-5">
				<div class="fnt-bold">GSTIN :</div>
				<div>03AABCP4708L1ZI</div>
			</td>
			<td class="wd-per-25 fnt-12 pl-5">
				<div class="fnt-bold">PAN No. :</div>
				<div>AABCP4708L</div>
			</td>
			<td class="wd-per-50 fnt-12 pl-5" colspan="2">
				<div class="fnt-bold">Invoice Type :</div>
				<div>REGULAR INVOICE</div>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="fnt-12 pl-5">
				<div>TRANSPORTATION CHARGES FOR BELOW MENTIONED TRIPS</div>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%" border="1" class="border-collapse bdr-t-wht" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td rowspan="2" class="fnt-12" style="width:3%;">
				<div class="fnt-bold txt-algn-center">Srl</div>
			</td>
			<td colspan="9" class="fnt-12" style="width:87%">
				<div class="txt-algn-center fnt-bold">
					Particular
				</div>
			</td>
			<td rowspan="2" class="fnt-12" style="width:10%;">
				<div class="fnt-bold txt-algn-center ">Amount</div>
			</td>
		</tr>
		<tr>
			<td class="fnt-12" style="width:5%;">
				<div class="fnt-bold txt-algn-center">Date</div>
			</td>
			<td class="fnt-12" style="width:6%;">
				<div class="fnt-bold txt-algn-center">LR No.</div>
			</td>
			<td class="fnt-12" style="width:13%;">
				<div class="fnt-bold txt-algn-center">Vehicle No.</div>
			</td>
			<td class="fnt-12" style="width:8%;">
				<div class="fnt-bold txt-algn-center">Type</div>
			</td>
			<td class="fnt-12" style="width:16%;">
				<div class="fnt-bold txt-algn-center">From Station</div>
			</td>
			<td class="fnt-12" style="width:16%;">
				<div class="fnt-bold txt-algn-center">To Station</div>
			</td>
			<td class="fnt-12" style="width:7%;">
				<div class="fnt-bold txt-algn-center">Loading Wt.</div>
			</td>
			<td class="fnt-12" style="width:8%;">
				<div class="fnt-bold txt-algn-center">Freight</div>
			</td>
			<td class="fnt-12" style="width:8%;">
				<div class="fnt-bold txt-algn-center">Rate</div>
			</td>
		</tr>
		@if(isset($tripData))
		@foreach($tripData as $k =>$row)
			<tr>
				<td class="pl-5 fnt-12" style="width:3%;"><div>{{($k+1)}}</div></td>
				<td class="pl-5 fnt-12" style="width:5%;">
					<div>{{($row->lr_date!=null)?date('d/m/Y',strtotime($row->lr_date)):''}}</div>
				</td>
				<td class="pl-5 fnt-12" style="width:6%;"><div>{{$row->lr_no ?? ''}}</div></td>
				<td class="pl-5 fnt-12" style="width:13%;">
					<div>{{($row->getSelectedVehicle->registration)?$row->getSelectedVehicle->registration:''}}</div>
				</td>
				<td class="pl-5 fnt-12" style="width:8%;"><div>{{$row->trip_type ?? ''}}</div></td>
				<td class="pl-5 fnt-12" style="width:16%;">
					<div>
						{{($row->getSelectedFromStation->name)?$row->getSelectedFromStation->name:''}}
					</div>
				</td>
				<td class="pl-5 fnt-12" style="width:16%;">
					<div>{{($row->getSelectedToStation->name)?$row->getSelectedToStation->name:''}}</div>
				</td>
				<td class="pl-5 fnt-12" style="width:7%;">
					<div>
						{{$row->net_weight ?? ''}}
					</div>
				</td>
				<td class="pl-5 fnt-12" style="width:8%;">
					<div>
						{{$row->freight_rate ?? ''}}
					</div>
				</td>
				<td class="pl-5 fnt-12" style="width:8%;">
					<div>N/A</div>
				</td>
				<td class="pl-5 fnt-12" style="width:10%;">
					<div>
						{{$row->freight_rate ?? ''}}
					</div>
				</td>
			</tr>
		@endforeach
		@endif
		
		<tr>
			<td style="height:300px;"></td>
			<td style="height:300px;"></td>
			<td style="height:300px;" colspan="5"></td>
			<td style="height:300px;"></td>
			<td style="height:300px;"></td>
			<td style="height:300px;"></td>
			<td style="height:300px;"></td>
		</tr>
		<tr>
			<td class="fnt-12" style="width:3%;">
				<div class="fnt-bold txt-algn-center">*</div>
			</td>
			<td class="fnt-12" style="width:5%;">
			</td>
			<td colspan="5" class="fnt-12 pr-5" style="width:59%">
				<div class="fnt-bold txt-algn-right">Sub Total...</div>
			</td>
			<td class="fnt-12 pl-5" style="width:7%;">
				<div class="fnt-bold">4000</div>
			</td>
			<td class="fnt-12 pl-5" style="width:8%;">
				<div class="fnt-bold">276000</div>
			</td>
			<td class="fnt-12 pl-5" style="width:8%;">
				<div class="fnt-bold">6.90</div>
			</td>
			<td class="fnt-12 pl-5" style="width:10%;">
				<div class="fnt-bold">276000</div>
			</td>
		</tr>
		<tr>
			<td colspan="11">
				<table width="100%" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td rowspan="2" class="pl-5 fnt-12 bdr-r-blck" style="width:10%;">
								<div class="fnt-bold txt-algn-center">SAC/HSN</div>
							</td>
							<td rowspan="2" class="pl-5 fnt-12 bdr-r-blck" colspan="2" style="width:30%;">
								<div class="fnt-bold txt-algn-center">Description of Tax Bifurcation </div>
							</td>
							<td rowspan="2" class="pl-5 fnt-12 bdr-r-blck" style="width:10%;">
								<div class="fnt-bold txt-algn-center">Taxable</div>
							</td>
							<td colspan="2" class="pl-5 fnt-12 bdr-r-blck" style="width:12%;">
								<div class="fnt-bold txt-algn-center">SGST</div>
							</td>
							<td colspan="2" class="pl-5 fnt-12 bdr-r-blck" style="width:12%;">
								<div class="fnt-bold txt-algn-center">CGST</div>
							</td>
							<td colspan="2" class="pl-5 fnt-12 bdr-r-blck" style="width:16%;">
								<div class="fnt-bold txt-algn-center">IGST</div>
							</td>
							<td rowspan="2" class="pl-5 fnt-12" style="width:10%;">
								<div class="fnt-bold txt-algn-center">Tax Total</div>
							</td>
						</tr>
						<tr>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:6%">
								<div class="fnt-bold txt-algn-center">Rate</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:6%">
								<div class="fnt-bold txt-algn-center">Amount</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:6%">
								<div class="fnt-bold txt-algn-center">Rate</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:6%">
								<div class="fnt-bold txt-algn-center">Amount</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:8%">
								<div class="fnt-bold txt-algn-center">Rate</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" style="width:8%">
								<div class="fnt-bold txt-algn-center">Amount</div>
							</td>
						</tr>	
						<tr>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pl-5">
								<div class="">996791</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pl-5" colspan="2">
								<div>GST @ 12.00</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12">
								<div class="txt-algn-center" >276000</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >12</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right" >33120</div>
							</td>
							<td class="bdr-t-blck fnt-12 pr-5">
								<div class="txt-algn-right" >33120</div>
							</td>
						</tr>
						<tr>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pl-5">
								<div class=""></div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5" colspan="2">
								<div class="txt-algn-right fnt-bold" >Total</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12" >
								<div class="txt-algn-center fnt-bold" >276000</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5" colspan="2">
								<div class="txt-algn-right fnt-bold" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5" colspan="2">
								<div class="txt-algn-right fnt-bold" >0</div>
							</td>
							<td class="bdr-t-blck bdr-r-blck fnt-12 pr-5" colspan="2">
								<div class="txt-algn-right fnt-bold" >33120</div>
							</td>
							<td class="bdr-t-blck fnt-12 pr-5">
								<div class="txt-algn-right fnt-bold" >33120</div>
							</td>
						</tr>
						<tr>
							<td colspan="8" class="bdr-t-blck bdr-r-blck fnt-12 pl-5">
								<div class="txt-algn-left fnt-bold">Total Amount (in words): &nbsp;</div>
								<div>RUPEES THREE LAKH NINE THOUSAND ONE HUNDRED TWENTY ONLY</div>
							</td>
							<td colspan="2" class="bdr-t-blck bdr-r-blck fnt-12 pr-5">
								<div class="txt-algn-right fnt-bold" >Grand Total</div>
							</td>
							<td class="bdr-t-blck fnt-12 pr-5">
								<div class="txt-algn-right fnt-bold">309120.00</div>
							</td>
						</tr>
						<tr>
							<td colspan="11" class="bdr-t-blck fnt-12 pl-5">
								<div class="txt-algn-left fnt-bold" >Remarks :</div>
								<div><br><br></div>
							</td>
						</tr>
						<tr>
							<td colspan="6" class="bdr-t-blck bdr-r-blck fnt-12 pl-5">
								<div class="fnt-bold">Terms & Condition</div>
								<div>WHETHER TAX PAYABLE BY REVERSE CHARGE : NO</div>
								<div>GST PAYABLE BY CONSIGNEE</div>
								<div>SUBJECT TO GANDHIDHAM JURISDICTION</div>
								<div>PAYMENT WITHIN 15 DAYS OTHERWISE 15 % INTREST WILL BE CHARGED.</div>
								<div class="fnt-bold pt-10">
									  I/We hereby declare that though our aggregate turnover in any preceding financial year from 2017-18 onwards is more than the aggregate turnover notified under sub-rule (4) of rule 48, we are not required to prepare an invoice in terms of the provisions of the said sub-rule (3) of rule 54.
								</div>
							</td>
							<td colspan="5" rowspan="3" class="bdr-t-blck fnt-12 pr-5 txt-vertical-top-rgt">
								<div class="pt-5">For, <span class="fnt-bold">{{$CompanyData->company_name ?? ''}}</span></div>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<div class="fnt-bold">Authorised Signatory</div>
							</td>
						</tr>
						<tr>
							<td colspan="6" class="bdr-r-blck">
								<table width="100%" class="border-collapse" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td colspan="3" class="bdr-t-blck bdr-r-blck fnt-12 pl-5" style="width:50%">
												<div class="fnt-bold pt-5">OUR GSTIN :</div>
												<div class="pt-5">24ANKPM8497J2Z7</div>
											</td>
											<td colspan="3" class="bdr-t-blck fnt-12 pl-5" style="width:50%">
												<div class="fnt-bold pt-5">OUR PAN NO. :</div>
												<div class="pt-5">ANKPM8497J</div>
											</td>
										</tr>
										<tr>
											<td colspan="3" class="bdr-t-blck bdr-r-blck fnt-12 pl-5" style="width:50%">
												<div class="fnt-bold pt-5">OUR BANK (IFSC) :</div>
												<div class="pt-5">ICICI BANK (ICIC0000259)</div>
											</td>
											<td colspan="3" class="bdr-t-blck fnt-12 pl-5" style="width:50%">
												<div class="fnt-bold pt-5">OUR BANK ACCOUNT NO. :</div>
												<div class="pt-5">025905001586</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td class="fnt-12 pl-10 pt-5" colspan="11">Subject to GANDHIDHAM Jurisdiction</td>
		</tr>
		<tr>
			<td class="fnt-12" colspan="11">
				<div class="txt-algn-center pt-5">Page 1/1</div>
			</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript" src="{{ asset('admin/theme/bower_components/jquery/js/jquery.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		window.print();
	});
</script>

</body>
</html>