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

		.fnt-12{
			font-size:12px;
		}
	</style>
</head>
<body>
	<table width="100%">
		<tbody>
			<tr>
				<td>
					@php 
						$headerImageUrl='';
						if(isset($getCompanyData)){
							$headerImageUrl=asset('uploads/company_header_images/'.$getCompanyData->header_image);
						}
					@endphp
					
					@if($headerImageUrl!='')
						<img src="{{$headerImageUrl}}" style="width:100%;height:160px;">
					@endif

				</td>
			</tr>
			<tr>
				<td style="padding-top:10px;">
					<span class="fnt-12" style="font-size:12px;font-weight:bold;padding-left:10px;">
						GSTIN : {{$getCompanyData->gst_no ?? '' }}
					</span>
				</td>
			</tr>
		</tbody>
	</table>
	<table width="100%" border="1" style="border-collapse:collapse;border:1px solid black;">
		<tbody>
			<tr>
				<td colspan="2">
					<div style="display:flex;">
						<div style="width:20%;">
							<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
									LR No. {{$tripData->lr_no??''}}
							</span>
						</div>
						<div style="width:60%;text-align:center;">
							<span class="fnt-12" style="font-weight:bold;">AT OWNERS RISK</span>
						</div>
						<div style="width:20%;text-align:right;padding-right:5px;">
							<span class="fnt-12" style="font-weight:bold;">
								Date. {{date('d/m/Y',strtotime($tripData->lr_date))}}
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="width:50%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						From : {{($tripData->getSelectedFromStation)?$tripData->getSelectedFromStation->name:''}}
					</span>
				</td>
				<td style="width:50%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						To : {{($tripData->getSelectedToStation)?$tripData->getSelectedToStation->name:''}}
					</span>
				</td>
			</tr>
			<tr>
				<td style="width:50%;">
					<div class="fnt-12" style="font-weight:bold;padding-left:5px;">
						Consignor Name & Address :</div>
					<div class="fnt-12" style="padding-left:5px;">
						{{($tripData->getSelectedConsignor)?$tripData->getSelectedConsignor->name:''}} 
						{{($tripData->getSelectedConsignor)?' - '.$tripData->getSelectedConsignor->address_line_1:''}} 

					</div>
				</td>
				<td style="width:50%;">
					<div class="fnt-12" style="font-weight:bold;padding-left:5px;">Consignee Name & Address :</div>
					<div class="fnt-12" style="padding-left:5px;">
						{{($tripData->getSelectedConsignee)?$tripData->getSelectedConsignee->name:''}} 
						{{($tripData->getSelectedConsignee)?' - '.$tripData->getSelectedConsignee->address_line_1:''}} 
					</div>
				</td>
			</tr>
			<tr>
				<td style="width:50%;" rowspan="2">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Consignment note is issuid on a/c of :</span><br>
					<span class="fnt-12" style="padding-left:5px;">
						
					</span>
				</td>
				<td style="width:50%;vertical-align: top;text-align: left;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						Goods Transport Agency : 
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%;border-collapse:collapse;" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td style="border-right:1px solid;width:40%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Tanker No. :</span>
								</td>
								<td>
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;width:60%;">
									{{($tripData->getSelectedVehicle)?$tripData->getSelectedVehicle->registration_no:''}}
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table width="100%" border="1" style="border-collapse:collapse;border:1px solid black;border-top:1px solid white;">
		<tbody>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						Invoice Qty : 
					</span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">{{$tripData->invoice_qty??''}}</span>
				</td>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Product :</span>
				</td>
				<td style="width:30%;" colspan="2">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						{{($tripData->getSelectedProduct)?$tripData->getSelectedProduct->name:''}}
					</span>
				</td>
			</tr>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Invoice No. :</span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">D. O. No. :</span>
				</td>
				<td style="width:30%;" colspan="2">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
			</tr>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Invoice Value. :</span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">0.00</span>
				</td>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Bill of Entry :</span>
				</td>
				<td style="width:30%;" colspan="2">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
			</tr>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">DCPI No : </span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Reporting No. :</span>
				</td>
				<td style="width:30%;" colspan="2">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
			</tr>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">TOL Qty.(Mt/Kl) :</span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">26.970</span>
				</td>
				<td style="border-right:1px solid;width:50%;" colspan="3">
					
				</td>
			</tr>
			<tr>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Seal No. :</span>
				</td>
				<td style="width:30%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
				</td>
				<td style="border-right:1px solid;width:15%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						Freight : {{$tripData->freight_rate ?? ''}} 
					</span>
				</td>
				<td style="border-right:1px solid;width:15%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">To Pay : </span>
				</td>
				<td style="border-right:1px solid;width:20%;">
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">To Be Billed : </span>
				</td>
			</tr>
			<tr>
				<td style="width:50%" colspan="2">
					<table width="100%" border="1" style="border-collapse:collapse;border:1px solid white;">
						<tbody>
							<tr>
								<td style="border-right:1px solid black;width:15%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Gross Weight. :</span>
								</td>
								<td style="border-right:1px solid black;width:15%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
										{{$tripData->gross_weight??0}}
									</span>
								</td>
								<td style="border-right:1px solid black;width:15%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Tare Weight. :</span>
								</td>
								<td style="border-right:1px solid black;width:15%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
										{{$tripData->tare_weight??0}}
									</span>
								</td>
								<td style="border-right:1px solid black;width:15%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Net Weight. :</span>
								</td>
								<td style="width:25%;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
										{{$tripData->net_weight??0}}
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td style="width:50%;vertical-align: top;text-align: left;" colspan="3" rowspan="3">
					<table width="100%" border="1" style="border-collapse:collapse;border:1px solid white;">
						<tbody>
							<tr>
							<td style="width:100%;vertical-align: top;text-align: left;">
								<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
									Remarks :
								</span><br>
								<span class="fnt-12" style="padding-left:5px;">
									{{$tripData->remarks ?? '' }}
								</span>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
						Received the above goods:
					</span>
				</td>
				<td>
					<span class="fnt-12" style="font-weight:bold;padding-left:5px;">Consignee stamp & signature </span>
				</td>
			</tr>
			<tr>
				<td style="width:50%;" colspan="2">
					<table width="100%" border="1" style="border-collapse:collapse;border:1px solid white;">
						<tbody>
							<tr>
								<td style="width:30%;border-right:1px solid black;">
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;"> Date </span><br>
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;"> Qty. </span><br>
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;"> Shortage </span><br>
								</td>
								<td style="width:35%;border-right:1px solid black;"></td>
								<td style="width:35%;"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%" border="1" style="border-collapse:collapse;border:1px solid white;">
						<tbody>
							<tr>
								<td>
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;">
										Not to be unloaded without receipt of modvate copy of invoice
									</span>
								</td>
								<td>
									<span class="fnt-12" style="font-weight:bold;padding-left:5px;"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table width="100%" border="1" style="border-collapse:collapse;border:1px solid black;">
		<tbody>
			<tr>
				<td style="width:100%;border-bottom:1px solid black;border-top:1px solid white;" colspan="3">
					<div style="text-decoration:underline;text-align:center;font-size:14px;"> TERMS & CONDITIONS</div>
					<div style="font-size:11px;padding-left:5px;padding-top:10px;">
						TRANSPORTER (hereinafter reffered to as "TRANSPORT CONTRACTORS") accept goods for carriage subject to the conditions set out below:
					</div>
					<div style="padding-left:20px;">
						<div class="fnt-12">
							1. No agent or employee of transport contractor is permitted to after or vary the terms condition any way.
						</div>
						<div class="fnt-12">
							2. Goods are accepted at owner's risk. We will not be responsible for theft, robbery, fire, riot, earthquake, accidential losses etc. during transit.
						</div>
						<div class="fnt-12">
							3. Owner i.e consignee will be responsible to insure goods necessarily against risks theft, robbery, fire, riot, earthquake, accidential losses etc. during transit.
						</div>
						<div class="fnt-12">
							4. We will not be responsible for any claim to yourself or anyone else authorised by you against theft, robbery, fire, riot, earthquake,accidential losses etc
						</div>
						<div class="fnt-12">
							5. Gandhidham ourt will be the Jurisdiction in case any dispute alse against this contract
						</div>
						<div class="fnt-12">
							6. Due to Goverment or Municipal Rule & Regulation if goods delievery is delayed we will not be responsible.
						</div>
						<div class="fnt-12">
							7. GST payable by transporter
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border:1px solid white;">
					<div style="font-size:12px;font-weight:bold;text-align:center;padding-bottom:20px;">
						This is system generated document and hence does not required signature
					</div>
				</td>
			</tr>
			<tr>
				<td style="width:35%;border:1px solid white;vertical-align: top;text-align: left;padding-right:10px;">
					<div style="font-size:12px;font-weight:bold;display:flex;">
						<div>Reporting Date :</div>
						<div>
							<div>
								{{($tripData->reporting_datetime!=null)?date('d/m/Y',strtotime($tripData->reporting_datetime)):''}}
							</div>
							<div>
							 	.....................................
							</div>
						</div>
					</div>
					<div style="font-size:12px;font-weight:bold;padding-top:10px;">
						<div style="display:flex">
							<div>Reporting Time :</div>
							<div>
								<div>{{($tripData->reporting_datetime!=null)?date('H:i A',strtotime($tripData->reporting_datetime)):''}}</div>
								<div>.....................................</div>
							</div>
						</div>
					</div>
					<div style="font-size:12px;font-weight:bold;padding-top:10px;">
						Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ......................................
					</div>
				</td>
				<td style="width:30%;border:1px solid white;vertical-align: top;text-align:left;padding-right:20px;">
					<div style="font-size:12px;font-weight:bold;display:flex;">
						<div >Shortage : &nbsp;&nbsp;</div>
						<div style="border:1px solid black;width:150px;height:40px;padding-left:5px;">
							{{$tripData->shortage_weight ?? 0}}
						</div>
					</div>
					<div style="font-size:12px;font-weight:bold;padding-top:5px;display:flex;">
						<div >Remarks : &nbsp;&nbsp;</div>
						<div style="border:1px solid black;width:150px;height:40px;padding-left:5px;"></div>
					</div>
				</td>
				<td style="width:35%;border:1px solid white;vertical-align: top;text-align: left;">
					<div style="font-size:12px;font-weight:bold;display:flex;">
						<div>Unloading Date :</div>
						<div>
							<div>
								{{($tripData->unload_datetime!=null)?date('d/m/Y',strtotime($tripData->unload_datetime)):''}}
							</div>
							<div>......................................</div>
						</div>
					</div>
					<div style="font-size:12px;font-weight:bold;padding-top:10px;display:flex;">
						<div>Unloading Time :</div>
						<div>
							<div>{{($tripData->unload_datetime!=null)?date('H:i A',strtotime($tripData->unload_datetime)):''}}</div>
							<div>.....................................</div>
						</div>
					</div>
					<div style="font-size:12px;font-weight:bold;padding-top:10px;">
						Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ......................................
					</div>
					<div style="text-align:right;padding-right:50px;">
						<img src="{{asset('admin/images/karan-sign-stamp-satyam.png')}}" style="width:70px;height:80px;">
					</div>
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