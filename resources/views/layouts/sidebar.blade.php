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
			
				{{-- @if($role == 1) --}}
				<li class="pcoded-hasmenu {{ Request::is('user*','product*','consignee*','party*','location*','route-list','route-add','route-edit*','driver*','vehicle*','ledger-type*','transaction-head*','tyres-brand*','tyres-service*') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Master</span>
					</a>
					
				</li>
				<li class="pcoded-hasmenu {{ Request::is('report-diesel','report-driver-trip') ? 'pcoded-trigger active ' : '' }}">
					<a href="javascript:void(0)" class="waves-effect waves-dark">
						<span class="pcoded-micon"><i class="feather icon-server"></i></span>
						<span class="pcoded-mtext">Reports</span>
					</a>
				
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