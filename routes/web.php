<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PartiesController;
use App\Http\Controllers\Admin\LocationsController;
use App\Http\Controllers\Admin\RoutesController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\VehiclesController;
use App\Http\Controllers\Admin\ReportsController;



use App\Http\Controllers\Admin\SalaryVouchersController;

use App\Http\Controllers\Admin\TransportTripsController;
use App\Http\Controllers\Admin\TransportBulkTripsController;
use App\Http\Controllers\Admin\TransportTripVouchersController;
use App\Http\Controllers\Admin\BillsController;

use App\Http\Controllers\Admin\LedgerTypesController;
use App\Http\Controllers\Admin\AccountBookController;
use App\Http\Controllers\Admin\CompanySettingsController;
use App\Http\Controllers\Admin\TransactionHeadsController;
use App\Http\Controllers\Admin\BillPaymentReceiptController;
use App\Http\Controllers\Admin\ImportCsvsController;
use App\Http\Controllers\Admin\TyresController;
use App\Http\Controllers\Admin\TyresBrandController;
use App\Http\Controllers\Admin\TyresAssignsController;
use App\Http\Controllers\Admin\TyresServiceTypeController;
use App\Http\Controllers\Admin\TyresServiceLogController;
use App\Http\Controllers\Admin\ConsigneeController;
use App\Http\Controllers\Admin\ProductController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');
    return "All Cache was cleared";
});

Route::get('/', function () {
    return redirect('/login');
    //return view('welcome');
});

Route::get('/update-end-time-ticket-assign-detail', [CronController::class, 'updateEndTimeTicketAssignDetail'])->name('update.end.time.ticket.assign.detail');

Route::get('/import-trip-data', [ImportCsvsController::class, 'importTrips']);
Route::get('/import-trip-voucher-data', [ImportCsvsController::class, 'importTripVoucher']);
Route::get('/import-account-book-data', [ImportCsvsController::class, 'importAccountBookData']);
Route::get('/import-tally-voucher-xml', [ImportCsvsController::class, 'tallyVoucherXml']);


Route::middleware('auth')->group(function () {
    //Route::get('/home', 'HomeController@index')->name('home');
    //Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/test-fuel-api', [DashboardController::class, 'testFuelApi']);
    
    Route::controller(UsersController::class)->group(function () {
        Route::get('/user-add','addUser')->name('add.user');
        Route::post('/user-store','addUser')->name('store.user');
        Route::get('/user-edit/{id}','editUser')->name('edit.user');
        Route::post('/user-update/{id}','editUser')->name('update.user');
        Route::get('/user-list','allUserList')->name('list.user');
        Route::post('/user-data','allUserList')->name('data.user');
        Route::post('/user-status-update','updateUserStatus')->name('update.status.user');
        Route::get('/user-exports','exportUsers')->name('user.exports');
        Route::get('/edit-profile','viewProfile')->name('edit.profile');
        Route::post('/update-profile','viewProfile')->name('update.profile');
        Route::post('/get-parties-via-type','getParties')->name('get.parties.via.type');
    });

    Route::controller(CompanySettingsController::class)->group(function () {
        Route::get('/company-list','allCompanyList')->name('list.company');
        Route::post('/company-data','allCompanyList')->name('data.company');

        Route::get('/company-setting/{id}','viewCompanySettings')->name('company.setting');
        Route::get('/company-bank-setting/{id}','viewBankDetail')->name('company.bank.setting');
        Route::get('/company-terms-setting/{id}','viewTermsSelection')->name('company.terms.setting');

        Route::post('/update-company-setting/{id}','viewCompanySettings')->name('update.company.setting');

        Route::post('/update-company-bank-setting/{id}','viewBankDetail')->name('update.company.bank.setting');

        Route::post('/update-company-terms-setting/{id}','viewTermsSelection')->name('update.company.terms.setting');


    });

    //  Route::controller(PartiesController::class)->group(function () {
    //     Route::get('/party-list','index')->name('party.list');
    //     Route::post('/party-paginate','partyPaginate')->name('party.paginate');
    //     Route::get('/party-add','partyAdd')->name('party.add');
    //     Route::post('/party-store','partyAdd')->name('party.store');
    //     Route::get('/party-edit/{id}','partyEdit')->name('party.edit');
    //     Route::post('/party-update/{id}','partyEdit')->name('party.update');
    //     Route::get('/party-export','exportParty')->name('party.export');
    //     Route::post('/party-status-update','updateStatus')->name('update.status.party');
    //     Route::get('/party-delete','deleteParty')->name('delete.party');
    //     Route::post('/party-child-single-delete','deletePartyAdditionDetailSingle')->name('party.child.single.delete');

    // });

    Route::controller(PartiesController::class)->group(function () {
        Route::get('/party-list','index')->name('party.list');
        Route::post('/party-paginate','partyPaginate')->name('party.paginate');
        Route::get('/party-add','partyAdd')->name('party.add');
        Route::post('/party-store','partyAdd')->name('party.store');
        Route::get('/party-edit/{id}','partyEdit')->name('party.edit');
        Route::post('/party-update/{id}','partyEdit')->name('party.update');
        Route::get('/party-export','exportParty')->name('party.export');
        Route::post('/party-status-update','updateStatus')->name('update.status.party');
        Route::get('/party-delete','deleteParty')->name('delete.party');
        Route::post('/party-child-single-delete','deletePartyAdditionDetailSingle')->name('party.child.single.delete');
        Route::post('/party-child-doc-single-delete','deletePartyDocSingle')->name('party.child.doc.single.delete');

        Route::get('/report-party-transaction-detail/{id}','partyTransactionList')->name('report.party.transaction.detail');
        Route::post('/report-party-transaction-paginate','partyTransactionPaginate')->name('report.party.transaction.paginate');
        Route::get('/report-party-transaction-export','partyTransactionExport')->name('report.party.transaction.export');
        
    });

    Route::controller(RoutesController::class)->group(function () {
        Route::get('/route-add','addRoute')->name('add.route');
        Route::post('/route-store','addRoute')->name('store.route');
        Route::get('/route-edit/{id}','editRoute')->name('edit.route');
        Route::post('/route-update/{id}','editRoute')->name('update.route');
        Route::get('/route-list','allRouteList')->name('list.route');
        Route::get('/route-data','allRouteList')->name('data.route');
        Route::post('/route-status-update','updateRouteStatus')->name('update.status.route');
        Route::get('/export-route','exportRoute')->name('export.route');
        Route::get('/route-delete','deletePrimaryRecord')->name('route.delete');
        
    }); 

    Route::controller(LedgerTypesController::class)->group(function () {
        Route::get('/ledger-type-add','addLedgerType')->name('add.ledger.type');
        Route::post('/ledger-type-store','addLedgerType')->name('store.ledger.type');
        Route::get('/ledger-type-edit/{id}','editLedgerType')->name('edit.ledger.type');
        Route::post('/ledger-type-update/{id}','editLedgerType')->name('update.ledger.type');
        Route::get('/ledger-type-list','allLedgerTypeList')->name('list.ledger.type');
        Route::get('/ledger-type-data','allLedgerTypeList')->name('data.ledger.type');
        Route::get('/ledger-type-delete','deletePrimaryRecord')->name('ledger.type.delete');

    }); 

    Route::controller(LocationsController::class)->group(function () {
        Route::get('/location-add','addLocation')->name('add.location');
        Route::post('/location-store','addLocation')->name('store.location');
        Route::get('/location-edit/{id}','editLocation')->name('edit.location');
        Route::post('/location-update/{id}','editLocation')->name('update.location');
        Route::get('/location-list','allLocationList')->name('list.location');
        Route::get('/location-data','allLocationList')->name('data.location');
        Route::post('/location-status-update','updateLocationStatus')->name('update.status.location');
        Route::get('/export-location','exportLocation')->name('export.location');
    }); 
    
    // Route::controller(RoutesController::class)->group(function () {
    //     Route::get('/route-add','addRoute')->name('add.route');
    //     Route::post('/route-store','addRoute')->name('store.route');
    //     Route::get('/route-edit/{id}','editRoute')->name('edit.route');
    //     Route::post('/route-update/{id}','editRoute')->name('update.route');
    //     Route::get('/route-list','allRouteList')->name('list.route');
    //     Route::get('/route-data','allRouteList')->name('data.route');
    //     Route::post('/route-status-update','updateRouteStatus')->name('update.status.route');
    //     Route::get('/export-route','exportRoute')->name('export.route');
    // });

    Route::controller(DriversController::class)->group(function () {
        Route::get('/driver-add','addDriver')->name('add.driver');
        Route::post('/driver-store','addDriver')->name('store.driver');
        Route::get('/driver-edit/{id}','editDriver')->name('edit.driver');
        Route::post('/driver-update/{id}','editDriver')->name('update.driver');

        Route::get('/driver-add-personal-detail-tab/{id}','addDriverPersonalDetailTab')->name('driver.add.personal.detail.tab');
        Route::get('/driver-add-relative-detail-tab/{id}','addDriverRelativeDetailTab')->name('driver.add.relative.detail.tab');
        Route::get('/driver-add-duedoc-detail-tab/{id}','addDriverDueDocDetailTab')->name('driver.add.duedoc.detail.tab');
        Route::get('/driver-add-doc-detail-tab/{id}','addDriverDocDetailTab')->name('driver.add.doc.detail.tab');
        Route::get('/driver-add-guarantor-detail-tab/{id}','addDriverGuarantorDetailTab')->name('driver.add.guarantor.detail.tab');
        Route::get('/driver-add-bank-detail-tab/{id}','addDriverBankDetailTab')->name('driver.add.bank.detail.tab');
        Route::get('/driver-add-vehicle-detail-tab/{id}','addDriverVehicleDetailTab')->name('driver.add.vehicle.detail.tab');


        Route::get('/driver-list','allDriverList')->name('list.driver');
        Route::get('/driver-data','allDriverList')->name('data.driver');
        Route::get('/driver-export','exportDriver')->name('export.driver');
        Route::post('/driver-status-update','updateDriverStatus')->name('update.status.driver');

        Route::post('/driver-relative-add','addDriverRelative')->name('add.driver.relative');
        Route::post('/driver-relative-remove','removeDriverRelative')->name('remove.driver.relative');

        Route::post('/driver-personal-update','updateDriverPersonalDetail')->name('update.driver.personal');
        Route::post('/driver-bank-update','updateDriverBank')->name('update.driver.bank');
        Route::post('/driver-guarantor-update','updateDriverGuarantor')->name('update.driver.guarantor');

        Route::get('/driver-due-list','paginateDriverDue')->name('list.driver.due');
        Route::get('/export-driver-doc-dues','exportDriverDue')->name('export.driver.doc.dues');
        Route::post('/driver-due-add','addDriverDue')->name('add.driver.due');
        Route::post('/driver-due-remove','removeDriverDue')->name('remove.driver.due');

        Route::post('/driver-doc-add','addDriverDoc')->name('add.driver.doc');
        Route::post('/driver-doc-remove','removeDriverDoc')->name('remove.driver.doc');
    });

    Route::controller(VehiclesController::class)->group(function () {
        Route::get('/vehicle-add-due-track-tab','addVehicleDueTrackTab')->name('vehicle.add.due.track.tab');
        Route::get('/vehicle-add-documents-tab','addVehicleDocumentsTab')->name('vehicle.add.documents.tab');
        Route::get('/vehicle-add-driver-tab','addVehicleDriverDetailTab')->name('vehicle.add.driver.detail.tab');
        

        Route::get('/vehicle-add','addVehicle')->name('add.vehicle');
        Route::post('/vehicle-store','addVehicle')->name('store.vehicle');
        Route::get('/vehicle-edit/{id}','editVehicle')->name('edit.vehicle');
        Route::post('/vehicle-update/{id}','editVehicle')->name('update.vehicle');
        Route::get('/vehicle-list','allVehicleList')->name('list.vehicle');
        Route::post('/vehicle-data','allVehicleList')->name('data.vehicle');
        Route::get('/vehicle-export','exportVehicle')->name('export.vehicle');
        Route::post('/vehicle-status-update','updateVehicleStatus')->name('update.status.vehicle');
        Route::post('/model-add','addModel')->name('add.model');

        Route::get('/vehicle-list-by-status/{status}','allVehicleListByStatus')->name('list.vehicle.status');
        Route::get('/vehicle-data-by-status/{status}','allVehicleListByStatus')->name('data.vehicle.status');

        Route::get('/vehicle-list-by-cfs/{status}','allVehicleListByCFS')->name('list.vehicle.cfs');
        Route::get('/vehicle-data-by-cfs/{status}','allVehicleListByCFS')->name('data.vehicle.cfs');

        Route::get('/vehicle-list-by-market','allVehicleListByMarket')->name('list.vehicle.market');
        Route::get('/vehicle-data-by-market','allVehicleListByMarket')->name('data.vehicle.market');

        Route::post('/vehicle-status-type-update','updateVehicleTypeStatus')->name('update.vehicle.type.status');
        Route::post('/market-vehicle-status-type-update','updateMarketVehicleTypeStatus')->name('update.market.vehicle.type.status');
        Route::post('/vehicle-cfs-status-update','updateVehicleCFSStatus')->name('update.vehicle.cfs');

        Route::get('/vehicle-due-list','paginateVehicleDue')->name('list.vehicle.due');
        Route::get('/export-vehicle-doc-dues','exportVehicleDue')->name('export.vehicle.doc.dues');
        
        Route::post('/vehicle-due-add','addVehicleDue')->name('add.vehicle.due');
        Route::post('/vehicle-due-remove','removeVehicleDue')->name('remove.vehicle.due');

        Route::post('/vehicle-doc-add','addVehicleDoc')->name('add.vehicle.doc');
        Route::post('/vehicle-doc-remove','removeVehicleDoc')->name('remove.vehicle.doc');
        Route::post('/vehicle-driver-add','addVehicleDriver')->name('add.vehicle.driver');
        Route::post('/vehicle-driver-update','updateVehicleDriver')->name('update.vehicle.driver');
    });

    Route::controller(SalaryVouchersController::class)->group(function () {
        Route::get('/salary-voucher-list','index')->name('salary.voucher.list');
        Route::post('/salary-voucher-paginate','paginate')->name('salary.voucher.paginate');
        Route::get('/salary-voucher-add','addData')->name('salary.voucher.add');
        Route::post('/salary-voucher-store','addData')->name('salary.voucher.store');
        Route::get('/salary-voucher-edit/{id}','editData')->name('salary.voucher.edit');
        Route::post('/salary-voucher-update/{id}','editData')->name('salary.voucher.update');
        Route::get('/salary-voucher-export','exportSalaryVoucher')->name('salary.voucher.export');
        Route::get('/salary-voucher-delete','deleteRecord')->name('delete.salary.voucher');
        Route::get('/salary-voucher-print','printSalaryVoucher')->name('print.salary.voucher');
        Route::post('/slry-vchr-trans-trip-paginate','transportTripPaginate')->name('slry.vchr.trans.trip.paginate');
        Route::get('/slry-vchr-trans-trip-export','transportTripExport')->name('slry.vchr.trans.trip.export');
        Route::post('/slry-vchr-driver-allocated-vehicles-paginate','driverAllocatedVehiclePaginate')->name('slry.vchr.driver.allocated.vehicles.paginate');

        //advance
        Route::get('/advance-salary-voucher-list','indexAdvanceSalaryVoucher')->name('advance.salary.voucher.list');
        Route::post('/advance-salary-voucher-paginate','paginateAdvanceSalaryVoucher')->name('advance.salary.voucher.paginate');
        Route::get('/advance-salary-voucher-add','addDataAdvanceSalaryVoucher')->name('advance.salary.voucher.add');
        Route::post('/advance-salary-voucher-store','addDataAdvanceSalaryVoucher')->name('advance.salary.voucher.store');
        Route::get('/advance-salary-voucher-edit/{id}','editDataAdvanceSalaryVoucher')->name('advance.salary.voucher.edit');
        Route::post('/advance-salary-voucher-update/{id}','editDataAdvanceSalaryVoucher')->name('advance.salary.voucher.update');
        Route::get('/advance-salary-voucher-export','exportAdvanceSalaryVoucher')->name('advance.salary.voucher.export');
        Route::get('/advance-salary-voucher-delete','deleteRecordAdvanceSalaryVoucher')->name('delete.advance.salary.voucher');
        Route::get('/advance-salary-voucher-print','printAdvanceSalaryVoucher')->name('print.advance.salary.voucher');
    });

    Route::controller(TransportTripsController::class)->group(function () {
        Route::get('/transport-trip-list','index')->name('transport.trip.list');
        Route::post('/transport-trip-paginate','TransportTripPaginate')->name('transport.trip.paginate');
        Route::get('/transport-trip-add','TransportTripAdd')->name('transport.trip.add');
        Route::post('/transport-trip-store','TransportTripAdd')->name('transport.trip.store');
        Route::get('/transport-trip-edit/{id}','TransportTripEdit')->name('transport.trip.edit');
        Route::post('/transport-trip-update/{id}','TransportTripEdit')->name('transport.trip.update');
        Route::get('/transport-trip-export','exportTransportTrip')->name('transport.trip.export');
        Route::get('/transport-trip-delete','deleteTransportTrip')->name('delete.transport.trip');

        Route::get('/transport-trip-print','printLr')->name('transport.trip.print');



        //old
        Route::post('/get-last-allocated-driver-data','getLastAlloctedDriver')->name('get.last.allocated.driver.data');
        
        Route::post('/get-service-request-transport-data','getServiceReqTransData')->name('get.service.request.transport.data');
        Route::get('/transport-trip-detention-list','detention')->name('transport.trip.list.detention');
        Route::post('/transport-trip-detention-paginate','transportTripDetentionPaginate')->name('transport.trip.detention.paginate');
        Route::post('/update-transport-trip-detention','updateTransportTripDetention')->name('update.transport.trip.detention');


        Route::post('/update-transport-trip-drop-date','updateTransportTripDropDate')->name('update.transport.trip.drop.date');
        Route::post('/check-running-transport-trip','checkTransportRunningTrip')->name('check.transport.running.trip');
        Route::post('/check-market-running-transport-trip','checkMarketTransportRunningTrip')->name('check.market.transport.running.trip');
        
        Route::get('/list-authorisedby-market-trips','authorisedByMarketTripsPaginate')->name('list.authorisedby.market.trips'); 
        Route::post('/update-authorise-market-trips','updateAuthorisedByMarketTrips')->name('update.authorise.market.trips'); 
        Route::post('/check-container-no','checkContainerNo')->name('check.container.no'); 

        Route::post('/trip-add-from-location','addFromLocation')->name('trip.add.from.location');
        Route::post('/trip-add-product','addProduct')->name('trip.add.product');
        Route::post('/trip-add-driver','addDriver')->name('trip.add.driver');
        Route::post('/trip-add-consignor','addConsignorTrip')->name('trip.add.consignor');
        Route::post('/trip-add-vehicle','addVehicleTrip')->name('trip.add.vehicle');

    });

    Route::controller(TransportBulkTripsController::class)->group(function () {
        Route::get('/bulk-transport-trip-list','index')->name('bulk.transport.trip.list');
        Route::post('/bulk-transport-trip-paginate','TransportTripPaginate')->name('bulk.transport.trip.paginate');
        Route::get('/bulk-transport-trip-add','TransportTripAdd')->name('bulk.transport.trip.add');
        Route::post('/bulk-transport-trip-store','TransportTripAdd')->name('bulk.transport.trip.store');
        Route::get('/bulk-transport-trip-edit/{id}','TransportTripEdit')->name('bulk.transport.trip.edit');
        Route::post('/bulk-transport-trip-update/{id}','TransportTripEdit')->name('bulk.transport.trip.update');
        Route::get('/bulk-transport-trip-export','exportTransportTrip')->name('bulk.transport.trip.export');
        Route::get('/bulk-transport-trip-delete','deleteTransportTrip')->name('delete.bulk.transport.trip');


        Route::post('/get-last-allocated-driver-data','getLastAlloctedDriver')->name('get.last.allocated.driver.data');
        
        Route::post('/get-service-request-bulk-transport-data','getServiceReqTransData')->name('get.service.request.bulk.transport.data');
        Route::get('/bulk-transport-trip-detention-list','detention')->name('bulk.transport.trip.list.detention');
        Route::post('/bulk-transport-trip-detention-paginate','transportTripDetentionPaginate')->name('bulk.transport.trip.detention.paginate');
        Route::post('/update-bulk-transport-trip-detention','updateTransportTripDetention')->name('update.bulk.transport.trip.detention');


        Route::post('/update-bulk-transport-trip-drop-date','updateTransportTripDropDate')->name('update.bulk.transport.trip.drop.date');
        Route::post('/check-running-bulk-transport-trip','checkTransportRunningTrip')->name('check.bulk.transport.running.trip');
        Route::post('/check-market-running-bulk-transport-trip','checkMarketTransportRunningTrip')->name('check.market.bulk.transport.running.trip');
        Route::get('/bulk-transport-trip-print','printLr')->name('bulk.transport.trip.print');

        Route::get('/list-authorisedby-market-trips','authorisedByMarketTripsPaginate')->name('list.authorisedby.market.trips'); 
        Route::post('/update-authorise-market-trips','updateAuthorisedByMarketTrips')->name('update.authorise.market.trips');

         Route::post('/add-material','addMaterial')->name('add.material');
         Route::post('/add-consigness','addConsigness')->name('add.consigness');
         Route::post('/add-consigner','addConsigner')->name('add.consigner');

    });

    Route::controller(TransportTripVouchersController::class)->group(function () {
        Route::get('/transport-trip-voucher-list','index')->name('transport.trip.voucher.list');
        Route::post('/transport-trip-voucher-paginate','TransportTripVoucherPaginate')->name('transport.trip.voucher.paginate');
        Route::get('/transport-trip-voucher-add','TransportTripVoucherAdd')->name('transport.trip.voucher.add');
        Route::post('/transport-trip-voucher-store','TransportTripVoucherAdd')->name('transport.trip.voucher.store');
        Route::get('/transport-trip-voucher-edit/{id}','TransportTripVoucherEdit')->name('transport.trip.voucher.edit');
        Route::post('/transport-trip-voucher-update/{id}','TransportTripVoucherEdit')->name('transport.trip.voucher.update');
        Route::get('/transport-trip-voucher-export','exportTransportTripVoucher')->name('transport.trip.voucher.export');
        Route::get('/transport-trip-voucher-delete','deleteTransportTripVoucher')->name('delete.transport.trip.voucher');

        Route::get('/transport-trip-voucher-print','printTripVoucher')->name('transport.trip.voucher.print');
        Route::get('/transport-diesel-voucher-print','printDieselVoucher')->name('transport.diesel.voucher.print');

        Route::post('/get-transport-trip','getTransportTrip')->name('get.transport.trip');
        Route::post('/get-budgeted-trip','getBudgetedByTrip')->name('get.budgeted.trip');

        Route::get('/list-authorisedby-trip-vouchers','authorisedByTripVoucherPaginate')->name('list.authorisedby.trip.vouchers'); 
        Route::post('/update-authorise-trip-vouchers','updateAuthorisedByTripVoucher')->name('update.authorise.trip.vouchers'); 

    });

    Route::controller(BillsController::class)->group(function () {
        Route::get('/bill-list','index')->name('bill.list');
        Route::post('/bill-paginate','paginate')->name('bill.paginate');
        Route::get('/bill-add','addData')->name('bill.add');
        Route::post('/bill-store','addData')->name('bill.store');
        Route::get('/bill-edit/{id}','editData')->name('bill.edit');
        Route::post('/bill-update/{id}','editData')->name('bill.update');
        Route::get('/bill-export','exportPrimaryRecord')->name('bill.export');
        Route::get('/bill-delete','deletePrimaryRecord')->name('delete.bill');
        Route::post('/get-party-wise-trips','getPartywiseTrips')->name('get.party.wise.trips');
        Route::get('/bill-print','printBill')->name('bill.print');

    });

    Route::controller(ReportsController::class)->group(function () {
        Route::get('/report-diesel','dieselReport')->name('report.diesel');
        Route::post('/report-diesel-data','dieselReportData')->name('report.diesel.data');
        Route::get('/report-diesel-print','dieselReportPrint')->name('report.diesel.print');
        

        Route::get('/report-driver-trip','driverTripReport')->name('report.driver.trip');
        Route::post('/report-driver-trip-data','driverTripReportData')->name('report.driver.trip.data');
        Route::post('/report-driver-trip-update','driverTripReportUpdate')->name('report.driver.trip.update');
        Route::get('/report-driver-trip-print','driverTripReportPrint')->name('report.driver.trip.print');
        

        // Route::post('/report-transporter-trips-statement-paginate','transporterTripStatementPaginate')->name('report.transporter.trips.statement.paginate');
        // Route::get('/report-transporter-trips-statement-export','transporterTripStatementExport')->name('report.transporter.trips.statement.export');
        // Route::post('/update-transport-trip-bill-detail','transporterTripBillDetailUpdate')->name('update.transport.trip.bill.detail');
    });


    Route::controller(BillPaymentReceiptController::class)->group(function () {
        Route::get('/bill-payment-receipt-list','index')->name('bill.payment.receipt.list');
        Route::post('/bill-payment-receipt-paginate','paginate')->name('bill.payment.receipt.paginate');
        Route::get('/bill-payment-receipt-add','addData')->name('bill.payment.receipt.add');
        Route::post('/bill-payment-receipt-store','addData')->name('bill.payment.receipt.store');
        Route::get('/bill-payment-receipt-edit/{id}','editData')->name('bill.payment.receipt.edit');
        Route::post('/bill-payment-receipt-update/{id}','editData')->name('bill.payment.receipt.update');
        Route::get('/bill-payment-receipt-export','exportPrimaryRecord')->name('bill.payment.receipt.export');
        Route::get('/bill-payment-receipt-delete','deletePrimaryRecord')->name('delete.bill.payment.receipt');
        Route::post('/bill-payment-receipt-get-party-wise-bills','getPartywiseBills')->name('bill.payment.receipt.get.party.wise.bills');
    });

    Route::controller(TransactionHeadsController::class)->group(function () {
        Route::get('/transaction-head-list','index')->name('transaction.head.list');
        Route::post('/transaction-head-paginate','paginate')->name('transaction.head.paginate');
        Route::get('/transaction-head-add','addData')->name('transaction.head.add');
        Route::post('/transaction-head-store','addData')->name('transaction.head.store');
        Route::get('/transaction-head-edit/{id}','editData')->name('transaction.head.edit');
        Route::post('/transaction-head-update/{id}','editData')->name('transaction.head.update');
        Route::get('/transaction-head-export','exportPrimaryRecord')->name('transaction.head.export');
        Route::get('/transaction-head-delete','deletePrimaryRecord')->name('transaction.head.delete');
    });

    

    Route::controller(AccountBookController::class)->group(function () {
        Route::get('/account-book-list','index')->name('account.book.list');
        Route::post('/account-book-paginate','paginate')->name('account.book.paginate');
        Route::get('/account-book-add','addData')->name('account.book.add');
        Route::post('/account-book-store','addData')->name('account.book.store');
        Route::get('/account-book-edit/{id}','editData')->name('account.book.edit');
        Route::post('/account-book-update/{id}','editData')->name('account.book.update');
        Route::get('/account-book-export','exportPrimaryRecord')->name('account.book.export');
        Route::get('/account-book-delete','deletePrimaryRecord')->name('account.book.delete');
        Route::post('/account-book-get-transaction-head','getTransportHeads')->name('account.book.get.transaction.head');
    });
    

    Route::controller(TyresController::class)->group(function () {
        Route::get('/tyres-list','index')->name('tyres.list');
        Route::post('/tyres-paginate','tyresPaginate')->name('tyres.paginate');
        Route::get('/tyres-add','tyresAdd')->name('tyres.add');
        Route::post('/tyres-store','tyresAdd')->name('tyres.store');
        Route::get('/tyres-edit/{id}','tyresEdit')->name('tyres.edit');
        Route::post('/tyres-update/{id}','tyresEdit')->name('tyres.update');
        Route::get('/tyres-export','exportParty')->name('tyres.export');
        Route::post('/tyres-status-update','updateStatus')->name('update.status.tyres');
        Route::post('/tyres-delete','deletetyres')->name('delete.tyres');

        Route::post('/add-tyre-popup','addPop')->name('add.tyre.popup');


    });
    
    Route::controller(TyresBrandController::class)->group(function () {
        Route::post('/tyres-brand-delete','deleteTyresBrand')->name('delete.tyre.brand');
        Route::post('/tyres-brand-paginate','tyresBrandPaginate')->name('tyre.brand.paginate');
        Route::get('/tyres-brand-list','tyresBrandPaginate')->name('tyre.brand.list');
        Route::get('/tyres-brand-add','tyresBrandAdd')->name('tyre.brand.add');
        Route::post('/tyres-brand-store','tyresBrandAdd')->name('tyre.brand.store');
        Route::get('/tyres-brand-edit/{id}','tyresBrandEdit')->name('tyre.brand.edit');
        Route::post('/tyres-brand-update/{id}','tyresBrandEdit')->name('tyre.brand.update');
        
    });
    
    Route::controller(TyresAssignsController::class)->group(function () {
        Route::post('/tyres-assigns-delete','deleteTyresAssigns')->name('delete.tyres.assigns');
        Route::post('/tyres-assigns-paginate','tyresAssignsPaginate')->name('tyres.assigns.paginate');
        Route::get('/tyres-assigns-list','tyresAssignsPaginate')->name('tyres.assigns.list');
        Route::get('/tyres-assigns-add','tyresAssignsAdd')->name('tyres.assigns.add');
        Route::post('/tyres-assigns-store','tyresAssignsAdd')->name('tyres.assigns.store');
        Route::get('/tyres-assigns-edit/{id}','tyresAssignsEdit')->name('tyres.assigns.edit');
        Route::post('/tyres-assigns-update/{id}','tyresAssignsEdit')->name('tyres.assigns.update');
        
    });

    Route::controller(TyresServiceTypeController::class)->group(function () {
        Route::post('/tyres-service-delete','deleteTyresService')->name('delete.tyre.service');
        Route::post('/tyres-service-paginate','tyresServicePaginate')->name('tyre.service.paginate');
        Route::get('/tyres-service-list','tyresServicePaginate')->name('tyre.service.list');
        Route::get('/tyres-service-add','tyresServiceAdd')->name('tyre.service.add');
        Route::post('/tyres-service-store','tyresServiceAdd')->name('tyre.service.store');
        Route::get('/tyres-service-edit/{id}','tyresServiceEdit')->name('tyre.service.edit');
        Route::post('/tyres-service-update/{id}','tyresServiceEdit')->name('tyre.service.update');
        
    });

    Route::controller(TyresServiceLogController::class)->group(function () {
        Route::post('/tyres-service-log-delete','deleteTyresServiceLog')->name('delete.tyre.service.log');
        Route::post('/tyres-service-log-paginate','tyresServiceLogPaginate')->name('tyre.service.log.paginate');
        Route::get('/tyres-service-log-list','index')->name('tyre.service.log.list');
        Route::get('/tyres-service-log-add','tyresServiceLogAdd')->name('tyre.service.log.add');
        Route::post('/tyres-service-log-store','tyresServiceLogAdd')->name('tyre.service.log.store');
        Route::post('/getVehicleData','getVehicleData')->name('get.VehicleData');
        Route::get('/tyres-service-log-edit/{id}','tyresServiceLogEdit')->name('tyre.service.log.edit');
        Route::post('/tyres-service-log-update/{id}','tyresServiceLogEdit')->name('tyre.service.log.update');
        
    });

     Route::controller(ConsigneeController::class)->group(function () {
        Route::get('/consignee-list','index')->name('consignee.list');
        Route::post('/consignee-paginate','paginate')->name('consignee.paginate');
        Route::get('/consignee-add','addData')->name('consignee.add');
        Route::post('/consignee-store','addData')->name('consignee.store');
        Route::get('/consignee-edit/{id}','editData')->name('consignee.edit');
        Route::post('/consignee-update/{id}','editData')->name('consignee.update');
        Route::get('/consignee-export','exportPrimaryRecord')->name('consignee.export');
        Route::post('/consignee-delete','deleteRecord')->name('consignee.delete');

    });

     Route::controller(ProductController::class)->group(function () {
        Route::get('/product-list','index')->name('product.list');
        Route::post('/product-paginate','paginate')->name('product.paginate');
        Route::get('/product-add','addData')->name('product.add');
        Route::post('/product-store','addData')->name('product.store');
        Route::get('/product-edit/{id}','editData')->name('product.edit');
        Route::post('/product-update/{id}','editData')->name('product.update');
        Route::get('/product-export','exportPrimaryRecord')->name('product.export');
        Route::post('/product-delete','deleteRecord')->name('product.delete');
    });


});


require __DIR__.'/auth.php';

