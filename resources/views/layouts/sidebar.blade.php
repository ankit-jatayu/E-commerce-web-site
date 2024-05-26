<?php 
	$user_id = Auth::user()->id;
	$role = Auth::user()->role_id;

	$all_access_rights = \App\Models\UserProjectModules::where(['user_id' => $user_id])->get();
	
	$PartyModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==1;})->first();
	$PlaceModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==2;})->first();
	$RouteModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==3;})->first();
	$DriverModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==4;})->first();
	$TripsModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==5;})->first();
	$TripVchrModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==6;})->first();
	$BillsModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==7;})->first();
	$AccountBookModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==8;})->first();
	$ReportModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==9;})->first();
	$LedgerTypeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==10;})->first();
	$TransactionHeadModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==11;})->first();
	$UserModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==12;})->first();
	$BillPaymentReceiptModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==13;})->first();
	$VehicleModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==14;})->first();
	$SalaryVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==15;})->first();
	$AdvanceSalaryVchModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==16;})->first();
	$TyreModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==17;})->first();
	$TyreAsignsModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==18;})->first();
	$TyreServiceLogModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==19;})->first();
	$TyresBrandModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==20;})->first();
	$TyresServiceTypeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==21;})->first();
	$ConsigneeModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==22;})->first();
	$MaterialModuleRights = $all_access_rights->filter(function($item){ return $item->project_module_id==23;})->first();



?>

<nav class="pcoded-navbar">
	<div class="nav-list">
		<div class="pcoded-inner-navbar main-menu">
			<div class="pcoded-navigation-label">Modules</div>
			<ul class="pcoded-item pcoded-left-item">
				<li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
					<a href="{{route('dashboard')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-home"></i>
						</span>
						<span class="pcoded-mtext">Dashboard</span>
					</a>
				</li>
				@if(isset($TripsModuleRights) && $TripsModuleRights->is_view==1)
				<li class="{{ Request::is('transport-trip-list','transport-trip-add','transport-trip-edit*') ? 'active ' : '' }}">
					<a href="{{route('transport.trip.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-codepen"></i>
						</span>
						<span class="pcoded-mtext">Trips</span>
					</a>
				</li>
				@endif
				
				@if(isset($TripVchrModuleRights) && $TripVchrModuleRights->is_view==1)
				<li class="{{ Request::is('transport-trip-voucher*') ? 'active' : '' }}">
					<a href="{{route('transport.trip.voucher.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-command"></i>
						</span>
						<span class="pcoded-mtext">Trip Vouchers</span>
					</a>
				</li>
				@endif

				@if(isset($BillsModuleRights) && $BillsModuleRights->is_view==1)
				<li class="{{ Request::is('bill*') ? 'active' : '' }}">
					<a href="{{route('bill.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-file-text"></i>
						</span>
						<span class="pcoded-mtext">Bills</span>
					</a>
				</li>
				@endif
				
				
				{{-- @if(isset($AccountBookModuleRights) && $AccountBookModuleRights->is_view==1)

				<li class="{{ Request::is('account-book*') ? 'active' : '' }}">
					<a href="{{route('account.book.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-book"></i>
						</span>
						<span class="pcoded-mtext">Account Book</span>
					</a>
				</li>
				@endif
				@if(isset($BillPaymentReceiptModuleRights) && $BillPaymentReceiptModuleRights->is_view==1)
					<li class="{{ Request::is('bill-payment-receipt*') ? 'active' : '' }}">
					<a href="{{route('bill.payment.receipt.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-file-text"></i>
						</span>
						<span class="pcoded-mtext">Bills Payment Receipts</span>
					</a>
				</li>
				@endif
				

				<li class="{{ Request::is('report-transporter-trips-statement-list') ? 'active' : '' }}">
					<a href="{{route('report.transporter.trips.statement.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-navigation"><polygon points="3 11 22 2 13 21 11 13 3 11"/> </svg>
						</span>

						<span class="pcoded-mtext">Transporter Statement</span>
					</a>
				</li>

				@if(isset($TripVchrModuleRights) && $TripVchrModuleRights->is_view==1)
				<li class="{{ Request::is('transport-trip-voucher*') ? 'active' : '' }}">
					<a href="{{route('transport.trip.voucher.list')}}" class="waves-effect waves-dark">
						<span class="pcoded-micon">
							<i class="feather icon-command"></i>
						</span>
						<span class="pcoded-mtext">Trip Vouchers</span>
					</a>
				</li>
				@endif
				

				<li class="pcoded-hasmenu {{ Request::is('salary*','advance-salary*') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Salary Voucher</span>
					</a>
					<ul class="pcoded-submenu">
				@if(isset($SalaryVchModuleRights) && $SalaryVchModuleRights->is_view==1)

						<li class="{{ Request::is('salary*') ? 'active ' : '' }}">
							<a href="{{route('salary.voucher.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Salary Vouchers</span>
							</a>
						</li>
				@endif
				@if(isset($AdvanceSalaryVchModuleRights) && $AdvanceSalaryVchModuleRights->is_view==1)
						<li class="{{ Request::is('advance-salary*') ? 'active ' : '' }}">
							<a href="{{route('advance.salary.voucher.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Advance Salary Vouchers</span>
							</a>
						</li>
				@endif

					</ul>
				</li> --}}

			{{-- 	<li class="pcoded-hasmenu {{ Request::is('tyres-list*','tyres-assigns*','tyres-service-log-list*') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-aperture"></i></span>
						<span class="pcoded-mtext">Tyres</span>
					</a>
					<ul class="pcoded-submenu">
					@if(isset($TyreModuleRights) && $TyreModuleRights->is_view==1)

						<li class="{{ Request::is('tyres-list*') ? 'active' : '' }}">
							<a href="{{route('tyres.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-micon">
									<i class="feather icon-aperture"></i>
								</span>
								<span class="pcoded-mtext">Tyre</span>
							</a>
						</li>
					@endif
					@if(isset($TyreAsignsModuleRights) && $TyreAsignsModuleRights->is_view==1)

						<li class="{{ Request::is('tyres-assigns*') ? 'active' : '' }}">
							<a href="{{route('tyres.assigns.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-micon">
									<i class="feather icon-alert-circle"></i>
								</span>
								<span class="pcoded-mtext">Tyre Assigns</span>
							</a>
						</li>
						@endif
						@if(isset($TyreServiceLogModuleRights) && $TyreServiceLogModuleRights->is_view==1)

						<li class="{{ Request::is('tyres-service-log-list*') ? 'active' : '' }}">
							<a href="{{route('tyre.service.log.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-micon">
									<i class="feather icon-disc"></i>
								</span>
								<span class="pcoded-mtext">Tyre Service Log</span>
							</a>
						</li>
						@endif
						
					</ul>
				</li>
 --}}
				{{-- @if($role == 1) --}}
				<li class="pcoded-hasmenu {{ Request::is('user*','product*','consignee*','party*','location*','route-list','route-add','route-edit*','driver*','vehicle*','ledger-type*','transaction-head*','tyres-brand*','tyres-service*') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Master</span>
					</a>
					<ul class="pcoded-submenu">
						@if(isset($UserModuleRights) && $UserModuleRights->is_view==1)
						<li class="{{ Request::is('user*') ? 'active ' : '' }}">
							<a href="{{route('list.user')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Users</span>
							</a>
						</li>
						@endif
						{{-- @if(isset($ConsigneeModuleRights) && $ConsigneeModuleRights->is_view==1)
						<li class="{{ Request::is('consignee*') ? 'active ' : '' }}">
							<a href="{{route('consignee.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Consignees</span>
							</a>
						</li>
						@endif --}}
						@if(isset($MaterialModuleRights) && $MaterialModuleRights->is_view==1)
						<li class="{{ Request::is('product*') ? 'active ' : '' }}">
							<a href="{{route('product.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Products</span>
							</a>
						</li>
						@endif
						@if(isset($PartyModuleRights) && $PartyModuleRights->is_view==1)
						<li class="{{ Request::is('party*') ? 'active ' : '' }}">
							<a href="{{route('party.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Parties</span>
							</a>
						</li>
						@endif
						@if(isset($PlaceModuleRights) && $PlaceModuleRights->is_view==1)
						<li class="{{ Request::is('location*') ? 'active ' : '' }}">
							<a href="{{route('list.location')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Places</span>
							</a>
						</li>
						@endif
						{{-- @if(isset($RouteModuleRights) && $RouteModuleRights->is_view==1)
						<li class="{{ Request::is('route*') ? 'active ' : '' }}">
							<a href="{{route('list.route')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Routes</span>
							</a>
						</li>
						@endif --}}
						@if(isset($DriverModuleRights) && $DriverModuleRights->is_view==1)

						<li class="{{ Request::is('driver*') ? 'active ' : '' }}">
							<a href="{{route('list.driver')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Drivers</span>
							</a>
						</li>
					
						@endif	
						@if(isset($VehicleModuleRights) && $VehicleModuleRights->is_view==1)

						<li class="{{ Request::is('vehicle*') ? 'active ' : '' }}">
							<a href="{{route('list.vehicle')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Vehicles</span>
							</a>
						</li>
						@endif

						{{-- @if(isset($TransactionHeadModuleRights) && $TransactionHeadModuleRights->is_view==1)

						<li class="{{ Request::is('transaction-head*') ? 'active ' : '' }}">
							<a href="{{route('transaction.head.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Transaction Heads</span>
							</a>
						</li>
						@endif --}}
						{{-- @if(isset($LedgerTypeModuleRights) && $LedgerTypeModuleRights->is_view==1)
						<li class="{{ Request::is('ledger-type*') ? 'active ' : '' }}">
							<a href="{{route('list.ledger.type')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Ledger Types</span>
							</a>
						</li>
						@endif --}}
						{{-- @if(isset($TyresBrandModuleRights) && $TyresBrandModuleRights->is_view==1)
						
						<li class="{{ Request::is('tyres-brand*') ? 'active' : '' }}">
							<a href="{{route('tyre.brand.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Tyres Brand</span>
							</a>
						</li>
						@endif --}}
						{{-- @if(isset($TyresServiceTypeModuleRights) && $TyresServiceTypeModuleRights->is_view==1)
						<li class="{{ Request::is('tyres-service*') ? 'active' : '' }}">
							<a href="{{route('tyre.service.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Tyres Service Type</span>
							</a>
						</li>
						@endif --}}
					</ul>
				</li>
				<li class="pcoded-hasmenu {{ Request::is('report-diesel','report-driver-trip') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Reports</span>
					</a>
					<ul class="pcoded-submenu">
						@if(isset($UserModuleRights) && $UserModuleRights->is_view==1)
						<li class="{{ Request::is('report-diesel') ? 'active ' : '' }}">
							<a href="{{route('report.diesel')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Diesel Report</span>
							</a>
						</li>
						<li class="{{ Request::is('report-driver-trip') ? 'active ' : '' }}">
							<a href="{{route('report.driver.trip')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Driver Trips Report</span>
							</a>
						</li>
						@endif
					</ul>
				</li>

				{{-- @endif  --}}
				{{-- @if(isset($ReportModuleRights) && $ReportModuleRights->is_view==1)
				<li class="pcoded-hasmenu {{ Request::is('report-account-transaction-detai*','report-warehouse-inventory-list*','report-trip-list*','report-profit-n-loss*') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Reports</span>
					</a>
					<ul class="pcoded-submenu">
						<li class="{{ Request::is('report-trip-list*') ? 'active ' : '' }}">
							<a href="{{route('report.trip.list')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">Trips</span>
							</a>
						</li>


						<li class="{{ Request::is('report-account-transaction-detail/MQ==') ? 'active ' : '' }}">
							<a href="{{route('report.account.transaction.detail',base64_encode('1'))}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">CASH</span>
							</a>
						</li>

						<li class="{{ Request::is('report-account-transaction-detail/Mg==') ? 'active ' : '' }}">
							<a href="{{route('report.account.transaction.detail',base64_encode('2'))}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">HDFC</span>
							</a>
						</li>

						<li class="{{ Request::is('report-account-transaction-detail/Mw==') ? 'active ' : '' }}">
							<a href="{{route('report.account.transaction.detail',base64_encode('3'))}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">AXIS</span>
							</a>
						</li>
						<li class="{{ Request::is('report-profit-n-loss-detail') ? 'active ' : '' }}">
							<a href="{{route('report.profit.n.loss.detail')}}" class="waves-effect waves-dark">
								<span class="pcoded-mtext">P&L</span>
							</a>
						</li>
					</ul>
				</li> 
				@endif--}}
			</ul>
	</div>
</div>
</nav>