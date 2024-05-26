	<?php
	// if(isset($AllReportData) && $AllReportData != '')
	// 	extract($AllReportData);
	// extract($AllCompanyData);
	// extract($AllBankDetails[0]);

	// extract($AllReportData['upper'][0]);
	// 	// if($AllReportData['upper'][0]){

	// 	// }else{
	// 		// $AllReportData['upper'][0]="";
	// 	// }
	
	// 	// No of containers
	// $containers20 = (isset($vNocTwenty) && !empty($vNocTwenty))?$vNocTwenty.' x 20':null;
	// $containers40 =	(isset($vNocForty) && !empty($vNocForty))?$vNocForty.' x 40':NUll;
	// $containerscount = "$containers20  $containers40"; 
	// 	// print_r("<pre>");
	// 	// print_r($AllReportData);
	// 	// die();
	?>
	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Transport Billing Tax-Invoice Report</title>
		{{-- <link href="dist/css/report.css" rel="stylesheet" type="text/css"> --}}
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<style type="text/css">
			html, body, div, span, applet, object, iframe,
			h1, h2, h3, h4, h5, h6, p, blockquote, pre,
			a, abbr, acronym, address, big, cite, code,
			del, dfn, em, img, ins, kbd, q, s, samp,
			small, strike, strong, sub, sup, tt, var,
			b, u, i, center,
			dl, dt, dd, ol, ul, li,
			fieldset, form, label, legend,
			table, caption, tbody, tfoot, thead, tr, th, td,
			article, aside, canvas, details, embed,
			figure, figcaption, footer, header, hgroup,
			menu, nav, output, ruby, section, summary,
			time, mark, audio, video 
			{
				margin: 0;
				padding: 0;
				/*border: 0;*/
				font: inherit;
				font-size: 100%;
				vertical-align: baseline;
			}

			html 
			{
				line-height: 1;
			}

			ol, ul 
			{
				list-style: none;
			}

			table 
			{
				/*border-collapse: collapse;*/
				border-spacing: 0;
			}

			caption, th, td 
			{
				text-align: left;
				font-weight: normal;
				vertical-align: middle;
			}

			q, blockquote 
			{
				quotes: none;
			}

			q:before, q:after, blockquote:before, blockquote:after 
			{
				content: "";
				content: none;
			}

			a img 
			{
				border: none;
			}

			article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary 
			{
				display: block;
			}

			body 
			{
				font-family: 'Source Sans Pro', sans-serif;
				font-weight: 300;
				font-size: 10px;
				margin-left: 10px;
				padding: 0;
				//border:1px solid black;
			}

			body a 
			{
				text-decoration: none;
				color: inherit;
			}

			body a:hover 
			{
				color: inherit;
				opacity: 0.7;
			}

			body .container 
			{
				min-width: 500px;
				margin: 0 auto;
				padding: 0 5px;
			}

			body .clearfix:after 
			{
				content: "";
				display: table;
				clear: both;
			}

			body .left 
			{
				float: left;
			}

			body .right 
			{
				float: right;
			}

			body .helper 
			{
				display: inline-block;
				height: 100%;
				vertical-align: middle;
			}

			body .no-break 
			{
				page-break-inside: avoid;
			}

			header 
			{
				//margin-top: 20px;
				//margin-bottom: 15px;
				//border-bottom: 1px solid black;
				padding-top: 20px;
				padding-bottom: 15px;
				border-top: 1px solid black;
				border-bottom: 1px solid black;

			}

			header figure 
			{
				float: left;
				width: 120px;
				height: 60px;
				margin-right: 25px;
				margin-left: 25px;
				text-align: center;
			}

			header figure img 
			{
				margin-top: 0px;
			}

			header .company-address 
			{
				float: left;
				max-width: 550px;
				line-height: 2em;
				margin-bottom: 5;
			}

			header .company-address .title 
			{
				color: #3c8dbc;
				font-weight: 400;
				font-size: 1.5em;
				text-transform: uppercase;
			}

			header .company-contact 
			{
				float: right;
				height: 60px;
				padding: 0 10px;
				color: black;
			}

			header .company-contact span 
			{
				display: inline-block;
				vertical-align: middle;
			}

			header .company-contact .circle 
			{
				width: 20px;
				height: 20px;
				background-color: white;
				border-radius: 50%;
				text-align: center;
			}

			header .company-contact .circle img 
			{
				vertical-align: middle;
			}

			header .company-contact .phone 
			{
				height: 100%;
				margin-right: 20px;
			}

			header .company-contact .email 
			{
				height: 100%;
				min-width: 100px;
				text-align: right;
			}

			section .details 
			{
				margin-bottom: 10px;
				margin-top: 10px;
			}

			section .details .client {
				width: 50%;
				line-height: 20px;
			}

			section .details .client .name {
				color: #3c8dbc;
			}
			section .details .data {
				width: 50%;
				text-align: right;
			}
			section .details .title {
				margin-bottom: 15px;
				color: #3c8dbc;
				font-size: 3em;
				font-weight: 400;
				text-transform: uppercase;
			}
			section table {
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				font-size: 0.9166em;
			}
			section table .qty, section table .unit, section table .total {
				width: 5%;
			}
			section table .desc {
				width: 55%;
				padding:3px;
			}
			section table thead {
				display: table-header-group;
				vertical-align: middle;
				border-color: inherit;
			}
			section table thead th {
				padding: 5px 5px;
				background: #3c8dbc;
					/*border-bottom: 5px solid #FFFFFF;
					border-right: 4px solid #FFFFFF;*/
					text-align: right;
					color: black;
					font-weight: 700;
					text-transform: uppercase;
					border-right: 1px solid black;
					border-bottom: 1px solid black;
				}
				section table thead th:last-child {
					border-right: none;
				}
				section table thead .desc {
					text-align: left;
				}
				section table thead .qty {
					text-align: center;
				}
				section table tbody td {
					padding: 3px;
					background: #E8F3DB;
					color: #777777;
					text-align: right;
					border-bottom: 1px solid #000000;
					border-right: 1px solid #000000;
				}
				section table tbody td:last-child {
					border-right: none;
				}
				section table tbody h3 {
					margin-bottom: 5px;
					color: #3c8dbc;
					font-weight: 600;
				}
				section table tbody .desc {
					text-align: left;
				}
				section table tbody .qty {
					text-align: center;
				}
				section table.grand-total {
					margin-bottom: 15px;
					margin-top: 15px;
				}
				section table.grand-total td {
					padding: 5px 10px;
					border: none;
					color: #777777;
					text-align: right;
				}
				section table.grand-total .desc {
					background-color: transparent;
				}
				section table.grand-total tr:last-child td {
					font-weight: 600;
					color: #3c8dbc;
					font-size: 1.18181818181818em;
				}
				
				footer {
					margin-bottom: 10px;
				}
				footer .thanks {
					margin-bottom: 40px;
					color: #3c8dbc;
					font-size: 1.16666666666667em;
					font-weight: 600;
				}
				footer .notice {
					margin-bottom: 25px;
				}
				footer .end {
					padding-top: 5px;
					//	border-top: 2px solid #3c8dbc;
					text-align: center;
				}
				.total-center{
					text-align:center;
					padding:5px;
				}
				.bold-font{
					font-weight:700;
				}
				
				.footer
				{
					position: fixed;
					bottom: 0;
					//border-top: 1px solid black;
				}
				.comp-name
				{
					color: #3c8dbc;
					font-weight: 500;
					font-size: 2.7em;
					text-transform: uppercase;
				}
				
				.text-left
				{
					text-align: left;
				}
				.text-right
				{
					text-align: right;
				}

				.text-center
				{
					text-align: center;
				}
				@media print {
					.break {page-break-after: always;}
					.no-print, .no-print *
					{
						display: none !important;
					}
				}
				.total-text{
					font-weight:600;font-size:12px;
				}
				
				
				
			</style>
		</head>
		<body onload="window.print();">
			<div class="row no-print">
				<h3>
					<a href="{{route('bill.list')}}" class="no-print" style="font-weight:600; color:white;font-size:16px;margin-left:10px;border: 2px solid black;background-color:green;" title="Transport Billing">Transport Billing</a></h3>
			</div>
			
			<?php 
			$dGrossAmount = 0;
			$totalTPAdvance = 0;
			// $count = 1;
			// $colSpanTotal = 7;
			// $rowsPerPage = 13;
			// $rowsCount = count($AllReportData['transactions']);
			// $currentRow = 0;
			// $mainAdvance = 0;
			?>
			
			<div style="border:1px solid black">
				{{-- <?php foreach($AllReportData['transactions'] as $trans) 
				{
					if($rowsPerPage ==13) 
						{ ?> --}}
							<header class="clearfix">
								<div class="container">
									<figure>
										@if(isset($CompanyData->logo) && $CompanyData->logo!='')
										<img src="{{asset('uploads/company_logo/'.$CompanyData->logo)}}" style="width: 120px;">
										@endif
									</figure>
									<div class="company-address">
										<h2 class="comp-name">{{$CompanyData->company_name}}</h2>
										<p>{{$CompanyData->address}}</p>
										<p> 
											{{($CompanyData->cin_no!='')?'CIN NO : '.$CompanyData->cin_no.' | ':''}}
											{{($CompanyData->gst_no!='')?'GST NO : '.$CompanyData->gst_no.' | ':''}}
											{{($CompanyData->pan_no!='')?'PAN No : '.$CompanyData->pan_no.' | ':''}} 
											{{($CompanyData->msme_no!='')?'MSME No : '.$CompanyData->msme_no.' | ':''}}
										</p>
										{{-- <p style="border-top:1px solid black">
											<?php  echo $AllCompanyData[0]['vBranchName']." : ".$AllCompanyData[0]['tBranchAddress']; ?>
												
										</p> --}}
									</div>
								</div>
							</header>

							<section>
								<div class="container">
									<div class="details clearfix">
										<div class="client left">
											<p>INVOICE TO:</p>
											<p>To,<br>
												<span style="font-weight:600;font-size:13px">{{$billingPartyDetail->name??''}}</span><br>
												{{$billingPartyDetail->address_line_1??''}}
												{{($billingPartyDetail->address_line_2!='')?','.$billingPartyDetail->address_line_2:''}}
												{{($billingPartyDetail->state_name!='')?','.$billingPartyDetail->state_name:''}}
												{{($billingPartyDetail->city!='')?','.$billingPartyDetail->city:''}}
												{{($billingPartyDetail->pincode!='')?'-'.$billingPartyDetail->pincode:''}}
												<br>
											</p>
											<p><b>GST NO : {{$billingPartyDetail->gst_no??'N/A'}}	| PAN NO : {{$billingPartyDetail->pan_no??'N/A'}}</b></p>
										</div>

										<div class="data right">
											<div class="title" style="color:#000000">TEXT INVOICE</div>
											<div class="name" style="font-size: 13px;padding: 5px;color:#000000;font-weight:bold">HSN : N/A</div>
											<div class="name" style="font-size: 13px;padding: 5px;color:#000000;font-weight:bold">{{$BillDetail->bill_no??''}}</div>
											<div class="date">
												Date of Invoice: {{date('d/m/Y',strtotime($BillDetail->bill_date))??''}}
											</div>
										</div>
									</div>
									{{-- <table  style="margin-bottom: 5px;border: 1px solid black;">
										<thead>
											<tr>
								   <!--<th rowspan="6" colspan="1" class="total total-center" style="border-bottom:none;text-align:left">Consigner</th>
								   	<th colspan="1" class="total total-center" style="border-bottom:none;text-align:left">Consignee</th>-->
								   	<th colspan="1" class="total total-center" style="border-bottom:none;text-align:left">Details</th>
								   </tr>
								</thead>
								<tbody>
									<?php if($eJobType == "Export" OR $eJobType == "Import") { ?>
										<tr>
											<td style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">JOB NO :  </span><?php echo $vJobNo; ?></p>
											</td>
										</tr>
									<?php } ?>
									<?php if($eJobType=="Export") { ?>
										<tr>
											<td style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">
													<p><b  style="font-weight:600">BL No.: </b>
														<?php foreach($bls as $bl){
															if($bl['BLNo'] ==""){
										 						// $BLNo = "N/A";
																continue;
															}else{
																$BLNo = $bl['BLNo'];
															}
															echo $BLNo.", "; 
														} ?>
													</p>
												</span></p>
											</td>
										</tr>

										<tr>
											<td style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">
													<p><b style="font-weight:600">SB No.: </b>
														<?php foreach($bls as $bl){
															if($bl['SBNo'] == ""){
										 						// $SBNo = "N/A";
																continue;
															}else{
																$SBNo = $bl['SBNo'];
															}
															echo $SBNo.", "; 
														} ?>
													</p>
												</span></p>
											</td>
										</tr>

										<tr>
											<td style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">
													<p><b style="font-weight:600">Invoice No : </b>
														<?php foreach($invoices as $in){
															if($in['vInvoiceNo'] ==""){
										 						// $BLNo = "N/A";
																continue;
															}else{
																$inNo = $in['vInvoiceNo'];
																$inDt = $in['dtInvoiceDate'];
															}
															echo $inNo."(".$inDt.")".", "; 
														} ?>
													</p>
												</span></p>
											</td>
										</tr>
									<?php } else if ($eJobType=="Import") { ?>
										<tr>
											<td style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">
													<p><b style="font-weight:600">BE No.: </b>
														<?php echo $vBeNo; ?>
													</p>
												</span></p>
											</td>
										</tr>

										<tr>
											<td  style="text-align:left;border-bottom:none;">
												<p><span style="font-weight:600">
													<p><b style="font-weight:600">BL No.: </b>
														<?php echo $vBlNo; ?>
													</p>
												</span></p>
											</td>
										</tr>
									<?php } ?>

									<tr>
								   <!-- <td style="text-align:left;border-bottom:none;"><h3><?php echo $vTransConsignerName; ?></h3></td>
								   	<td style="text-align:left;border-bottom:none;"><h3><?php echo $vTransConsigneeName; ?></h3></td> -->
								   	<td style="text-align:left;border-bottom:none;">
								   		<p>
								   			<span style="font-weight:600">SAC Code :  </span>
								   			<?php echo $AllCompanyData[0]['vSacCode'];?>
								   		</p>
								   	</td>
								   </tr>

								   <tr>
								   <!-- <td rowspan="1" style="text-align:left;border-bottom:none;"><p><?php echo $tConsignerAddress; ?></p></td>
								   	<td rowspan="1" style="text-align:left;border-bottom:none;"><p><?php echo $tConsigneeAddress; ?></p></td> -->
								   	<td style="text-align:left;border-bottom:none;">
								   		<p><span style="font-weight:600">SERVICE TYPE :  </span>TRANSPORTATION</p>
								   	</td>
								   </tr>
								   <?php if($tRemarks!=''){ ?> 
								   	<tr>
								   		<td style="text-align:left;border-bottom:none;">
								   			<p><span style="font-weight:600">Remarks :  </span><?php echo $tRemarks; ?></p>
								   		</td>
								   	</tr>
								   <?php } ?>
								</tbody>
							</table>
 --}}
							<table style="margin-top: 10px;border: 1px solid black;padding:5px;"  cellspacing="0" cellpadding="0" >
								<thead>
									<tr>
										<th class="text-center">Sr</th>
										<th class="text-center">Date</th>
										<th class="text-center">Vehicle No</th>
										<th class="text-center">Container No</th>
										<th class="text-center">Destination</th>
										<th class="text-center">Rate</th>
										<th class="text-center">Party Advance</th>
										<th class="text-center">Payable Amount</th>
									</tr>
								</thead>
								<tbody>
								{{-- <?php } ?> --}}
								@if(isset($TripData))
								@foreach($TripData as $k => $row)
								<?php 
								$dGrossAmount+=$row->amount;
								$totalTPAdvance+=App\Models\TransportTrips::getPartyAdvanced($row->trip_id);
								?>
									<tr>	
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$k+1}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$row->lr_date}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$row->vehicle_no}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$row->container_no}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$row->route_from_place.'-'.$row->route_to_place}}{{($row->route_back_place!='')?'-'.$row->route_back_place:''}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{$row->amount}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{App\Models\TransportTrips::getPartyAdvanced($row->trip_id)}}
										</td>
										<td class="text-left" style="padding-left:1px;text-align: center;">
											{{($row->amount)-(App\Models\TransportTrips::getPartyAdvanced($row->trip_id))}}
										</td>
									 </tr>
									  @endforeach
						           	 @endif

							</tbody>
						</table>
						{{-- <?php if($currentRow == $rowsCount) { ?>
						<?php }else{ ?>
							<div class="break"></div>
						<?php } ?>
					<?php } ?> --}}

					 {{-- <?php if($currentRow == $rowsCount){ ?> --}}
					 <?php 
					 $colSpanTotal=7;

					 $SGSTAmount=0;
					 $CGSTAmount=0;
					 $IGSTAmount=0;

					 if($CompanyData->gst_no!='' && $BillDetail->with_gst=='Yes'){
					 	$GstNoFirst2Char=substr($CompanyData->gst_no, 0, 2);
					 	if($GstNoFirst2Char!='' && $GstNoFirst2Char=='24'){//sgst+cgst
					 		
					 		$SGSTAmount=(($dGrossAmount*6)/100);
					 		$CGSTAmount=(($dGrossAmount*6)/100);
					 	}else{ //igst
							$IGSTAmount=(($dGrossAmount*12)/100);
					 	}

					 }

						 $dBasicAmount=$dGrossAmount;
						 $dFinalAmount=0;
						 
									 // $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
									 // $str = $f->format($dTotalAmount).' rupees';
									 // $str1 = ucwords($str);
						 $TotalTax = floatval($SGSTAmount)+floatval($CGSTAmount)+floatval($IGSTAmount);
						 $dFinalAmount=(($dBasicAmount+$TotalTax)-$totalTPAdvance);
					 
					 ?>

						<table style="margin-top: 10px;border: 1px solid black;padding:5px;" >
							<tbody>
								<tr>
									<td colspan="<?=$colSpanTotal;?>" class="total-text" style="width:324px;">Gross Amount </td>
									<td colspan="1" class="total-text"><?php echo number_format((float)round($dBasicAmount), 2, '.', ','); ?></td>
								</tr>
							@if($BillDetail->with_gst=='Yes')
								<tr>
									<td colspan="<?=$colSpanTotal;?>"  class="total-text">SGST @ 6 %</td>
									<td colspan="1"  class="total-text"><?php echo number_format((float)round($SGSTAmount), 2, '.', ','); ?></td>
								</tr>

								<tr>
									<td colspan="<?=$colSpanTotal;?>"  class="total-text">CGST @ 6 %</td>
									<td colspan="1"  class="total-text"><?php echo number_format((float)round($CGSTAmount), 2, '.', ','); ?></td>
								</tr>
								{{-- <?php if($dIGstRate > 0 ) { ?> --}}
									<tr>
										<td colspan="<?=$colSpanTotal;?>"  class="total-text">IGST @ 12 %</td>
										<td colspan="1"  class="total-text"><?php echo number_format((float)round($IGSTAmount), 2, '.', ','); ?></td>
									</tr>
								{{-- <?php } ?>
								
								<?php if($mainAdvance > 0 ) { ?> --}}
								@endif
									<tr>
										<td colspan="<?=$colSpanTotal;?>"  class="total-text">Advance </td>
										<td colspan="1"  class="total-text"><?php echo number_format((float)round($totalTPAdvance), 2, '.', ','); ?></td>
									</tr>
								{{-- <?php } ?> --}}

								<tr>
									<td colspan="<?=$colSpanTotal;?>"  class="total-text">Net Amount </td>
									<td colspan="1"  class="total-text"><?php echo number_format((float)round($dFinalAmount), 2, '.', ','); ?></td>
								</tr>
							</tbody>
						</table>

							
								 
								 <div class="no-break">
								 	<table class="grand-total">
								 		<tbody>
								 			<tr>
								 				<td class="desc" rowspan="3" style="font-size: 11px;text-align:left;font-weight: 600;">
								 					<b>Total Amount (in words): RS. <?php echo getIndianCurrency($dFinalAmount).' Only '; ?></b>
								 				</td>
								 				<td class="unit">SUBTOTAL:</td>
								 				<td class="total total-text"><?php echo number_format((float)round($dBasicAmount), 2, '.', ',');?></td>
								 			</tr>
								 			<tr>
								 				<td class="unit">TAX :</td>
								 				<td class="total total-text"><?php echo number_format((float)round($TotalTax), 2, '.', ',');?></td>
								 			</tr>
								 			<tr>
								 				<td class="unit" colspan="1" style="width: 15%;">GRAND TOTAL:</td>
								 				<td class="total" style="font-size:13px"><?php echo number_format((float)round($dFinalAmount), 2, '.', ','); ?></td>
								 			</tr>
								 		</tbody>
								 	</table>
								 </div>
								{{-- <?php } ?> --}}
							</div>
						
					{{-- <?php } ?> --}}
					</section>
					<br><br><br>
					<?php //if($currentRow == $rowsCount){ ?>
					<section class="footer" style="width:99%">
							<div class="container" >
								<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;margin-top: 5px;border: 0.5px solid black;">
									<thead>
										<tr>
											<td>&nbsp;</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="desc">
												<b>TERMS:</b><br>
												{{($CompanyData->term_1!='')?'1. '.$CompanyData->term_1:''}}<br>
												{{($CompanyData->term_2!='')?'2. '.$CompanyData->term_2:''}}<br>
												{{($CompanyData->term_3!='')?'3. '.$CompanyData->term_3:''}}<br>
												{{($CompanyData->term_4!='')?'4. '.$CompanyData->term_4:''}}<br>
												{{($CompanyData->term_5!='')?'5. '.$CompanyData->term_5:''}}<br>
											</td>
										</tr>
									</tbody>
								</table>
								
								<table>
									<tbody>
										<tr>
											<td style="text-align:left;border:none">
												<div class="" style="display:inline-block;text-align:left">
													<b class="bold-font">Bank Details</b><br>
													<b class="bold-font">Account Holder Name :</b> {{$CompanyData->bank_account_holder_name??''}}<br>
													<b class="bold-font">Bank Name  :</b> {{$CompanyData->bank_name??''}}
													<b class="bold-font">  |  Bank Account No :</b>{{$CompanyData->account_no??''}}<br>
													<b class="bold-font">IFSC Code :</b>{{$CompanyData->ifsc_no??''}}
												</div>
											</td>
											<td style="border:none">
												<div class="" style="display:inline-block;text-align:right">
													<span>
														<div>For {{$CompanyData->stamp_name??''}}</div><br>
														<img src="{{asset('uploads/company_stamp_img/'.$CompanyData->stamp_img)}}" style="width: 85px;"><br><br><br>
														<span>
															<div class="notice" align="right">
																<div>Authorised Signatory</div>
															</div>
														</span>
													</span>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								
								<div class="end" style="border-top:1px solid black">
									<center>Invoice was created on a computer and is valid without the signature and seal.</center>
								</div>
							</div>
						</section>
						<?php //} ?>
					</div>
				</body>
				</html>
				<?php 
				function getIndianCurrency($number)
				{
					$decimal = round($number - ($no = floor($number)), 2) * 100;
					$hundred = null;
					$digits_length = strlen($no);
					$i = 0;
					$str = array();
					$words = array(0 => '', 1 => 'One', 2 => 'Two',
						3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
						7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
						10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
						13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
						16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
						19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
						40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
						70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
					$digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
					while( $i < $digits_length ) {
						$divider = ($i == 2) ? 10 : 100;
						$number = floor($no % $divider);
						$no = floor($no / $divider);
						$i += $divider == 10 ? 1 : 2;
						if ($number) {
							$plural = (($counter = count($str)) && $number > 9) ? '' : null;
							$hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
							$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
						} else $str[] = null;
					}
					$Rupees = implode('', array_reverse($str));
					$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
					return ($Rupees ? $Rupees . '' : '') . $paise;
				}
				?>
				