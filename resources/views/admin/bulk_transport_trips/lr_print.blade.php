<?php 
	

?>
<div id="print">
	<style type="text/css">
	/*.bordere{
		border:1px;border-style:solid;
	}
	#bg-text
	{
		opacity: 0.5;
		padding-left: 40px;
		padding-top: 50px;
	    color:black;
	    font-size:12px;
	    transform:rotate(319deg);
	    -webkit-transform:rotate(319deg);
	}
	.to_be_print {
		font-size: 10px;
	}
	table{
		border-collapse: collapse;
		font-size: 11px;
	}.left{
		border-left: 1px;
		border-left-style: solid;
	}.right{
		border-right: 1px;
		border-right-style: solid;
	}.top{
		border-top: 1px;
		border-top-style: solid;
	}.bottom{
		border-bottom: 1px;
		border-bottom-style: solid;
	}
	.container-fluid{
		font-size: 12px;
	}
	
	table.table > thead > tr > th{
	    border:1px solid black;
	}
	table.table > tbody > tr > td{
	    border:1px solid black;
	}*/

	td{
		font-size:15px;
	}
	</style>
	<div class="bordered" style="border:1px;border-style:solid;">
		<table width="100%" style="border-collapse: collapse;font-size: 11px;" >
			<tr>
				<td width="27%">
					PAN No. &nbsp: {{$getCompamyData->pan_no}}<br>
					GST No.: {{$getCompamyData->gst_no}}
				</td>
				<td width="27%"> 
					<center>"SHRI GANESHAY NAMAH"</center>
					<center><u>Subject to Gandhidham Jurisdiction</u></center>
				</td>
				<td width="27%" align="right">
					<div style="padding-right:10px;">
						Mob.: {{$getCompamyData->mobileno}}
					</div>
				</td>
			</tr>
		</table>
		<table width="100%" style="border-collapse: collapse;font-size: 11px;">
			<tr>
				<td width="20%" style="padding-left:120px;">
					{{-- <img src="{{ asset('admin/images/rl_logo_png.png') }}" style="width:150px;height:150px;"> --}}
					@if(isset($getCompamyData->logo) && $getCompamyData->logo!='')

					<img src="{{asset('uploads/company_logo/'.$getCompamyData->logo)}}" style="width:150px;height:150px;">

					@endif
				</td>
				<td width="80%">
					<h1 style="font-size:50px;margin-bottom:0px;margin-top:0px;">{{$getCompamyData->company_name}}</h1>
					<h3 style="margin-bottom:0px;margin-top:0px;">TRANSPORT CONTRACTOR & COMMISSION AGENT</h3>
					<h4 style="margin-bottom:0px;margin-top:0px;">
						{{$getCompamyData->address}}
					</h4>
				</td>
			</tr>
		</table>	
		<table width="100%" style="border-collapse: collapse;border:1px solid black;">
			<tr>
				<td width="20%" style="padding-left:5px;border-right:1px solid black;">
					GR No : 75
				</td>
				<td width="30%" style="padding-left:5px;border-right:1px solid black;">
					Truck No : GJ12AQ9700
				</td>
				<td width="30%" style="padding-left:5px;border-right:1px solid black;">
					Tax is payable on Reverse Charge<br>
					<span style="padding-left:40px;">CONSIGNEE'S [YES/NO]</span>
				</td>
				<td width="20%" style="padding-left:5px;">
					Date : 10/May/2023
				</td>
				
			</tr>
		</table>	
		<table width="100%" style="border-collapse: collapse;">
			<tr>
				<td width="80%" style="padding-left:5px;padding-top:10px;padding-bottom:10px">
					<span style="padding-top:5px;">
						GSTIN NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........................................................................................................................................
					</span>
					<br>
					<span style="padding-top:5px;">
						CONSIGNOR NAME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					........................................................................................................................................
					
					</span>
					<br>
					<span style="padding-top:5px;">
						ADDRESS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........................................................................................................................................

					</span>
				</td>
				<td width="20%" style="padding-left:5px;border-left:1px solid black;">
					FROM :  20/05/2023	<br>
					STATE : GUJARAT 	<br>
					STATE : GUJARAT 	<br>
				</td>
				
			</tr>
			<tr style="border-top:1px solid black;">
				<td width="80%" style="padding-left:5px;">
					<span style="padding-top:5px;">
						GSTIN NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........................................................................................................................................
					</span>
					<br>
					<span style="padding-top:5px;">
						CONSGINEE NAME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					........................................................................................................................................
					
					</span>
					<br>
					<span style="padding-top:5px;">
						ADDRESS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........................................................................................................................................

					</span>
				</td>
				<td width="20%" style="padding-left:5px;border-left:1px solid black;">
					TO   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;25/05/2023	<br><br>
					STATE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; GUJARAT 	<br><br>
					STATE CODE  &nbsp;&nbsp;&nbsp;&nbsp; 12<br>
				</td>
				
			</tr>
		</table>
		<table width="100%" style="border-collapse: collapse;border-top:1px solid black;">
			<tr>
				<td style="border-right:1px solid black;padding-left: 5px;" rowspan="2">NO OF PACKAGE</td>
				<td style="border-right:1px solid black;padding-left: 5px;" rowspan="2">DESCRIPTION OF GOODS(SAID TO CONTAINT)</td>
				<td style="border-right:1px solid black;padding-left: 5px;" rowspan="2">HSN/SAC</td>
				<td style="border-right:1px solid black;padding-left: 5px;" rowspan="2">WEIGHT</td>
				<td style="border-right:1px solid black;padding-left: 5px;" rowspan="2">RATE</td>
				<td>FREIGHT</td>
			</tr>
			<tr style="border-top:1px solid black;">
				<td>To Pay</td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;height:100px;"></td>
				<td style="border-right:1px solid black;height:100px;"></td>
				<td style="border-right:1px solid black;height:100px;" rowspan="5"></td>
				<td style="border-right:1px solid black;height:100px;"></td>
				<td style="border-right:1px solid black;height:100px;"></td>
				<td style="border-right:1px solid black;height:100px;"></td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;padding-left:20px;height:100px;" colspan="2" rowspan="4">
					Our Bank : HDFC Bank<br>
					A/c No. : 59200126012644<br>
					IFSC Code : HDFC 0000216
				</td>
				<td style="border-right:1px solid black;height:50px;" rowspan="2"><center>Bill No</center></td>
				<td style="border-right:1px solid black;padding-left:5px;height:30px;">IGST @ &nbsp;&nbsp;&nbsp;%</td>
				<td style="border-right:1px solid black;height:30px;"></td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;padding-left:5px;height:30px;">CGST @ &nbsp;&nbsp;&nbsp;%</td>
				<td style="border-right:1px solid black;height:30px;"></td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;height:50px;" rowspan="2"><center>Value</center></td>
				<td style="border-right:1px solid black;padding-left:5px;height:30px;">SGST @ &nbsp;&nbsp;&nbsp;%</td>
				<td style="border-right:1px solid black;height:30px;"></td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;padding-right:20px;height:30px;" align="right">Revenue Ch.</td>
				<td style="border-right:1px solid black;height:30px;"></td>
			</tr>
			<tr style="border-top:1px solid black">
				<td style="border-right:1px solid black;padding-left:20px;height:40px;" colspan="4">
					Delivery ...............................................................................................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Booking Clerk
				</td>
				<td style="border-right:1px solid black;height:40px;padding-right:10px"  align="right">Total</td>
				<td style="border-right:1px solid black;height:40px;"></td>
			</tr>
			
		</table>

	</div>
<script type="text/javascript">
	//window.print();
</script>