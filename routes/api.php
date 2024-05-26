<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MobileapiController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('authenticate-user', [AuthController::class, 'signin']);
//Route::post('register', [AuthController::class, 'signup']);
 
 // Route::controller(TestController::class)->group(function () {
 //        Route::get('/get-salary-voucher','getSalaryVoucher')->name('get.salary.voucher');
 //    });

Route::middleware('auth:sanctum')->group( function () {
    
    Route::controller(MobileapiController::class)->group(function () {
        //Route::post('/authenticate-user','authenticateUser')->name('authenticate.user');
        Route::post('/get-trips-by-status','getTripListByStatus')->name('get.trips.by.status');
        Route::post('/add-trip','addTrip')->name('add.trip');
        Route::post('/add-trip-voucher','addTripVoucher')->name('add.trip.voucher');
        
        Route::post('/get-companies','getCompanies')->name('get.companies');
        Route::post('/get-trip-types','getTripTypes')->name('get.trip.types');
        Route::post('/get-vehicles','getVehicles')->name('get.vehicles');
        Route::post('/get-drivers','getDrivers')->name('get.drivers');
        Route::post('/get-products','getProducts')->name('get.products');
        Route::post('/get-locations','getLocations')->name('get.locations');
        Route::post('/get-consignors','getConsignors')->name('get.consignors');
        Route::post('/get-consignees','getConsignees')->name('get.consignees');
        Route::post('/get-payable-by','getPayableBy')->name('get.payable.by');
        Route::post('/get-payable-parties','getPayableParties')->name('get.payable.parties');
        Route::post('/get-transporters','getTransporters')->name('get.transporters');

        Route::post('/get-branches','getBranches')->name('get.branches');
        Route::post('/get-payment-modes','getPaymentModes')->name('get.payment.modes');
        Route::post('/get-trips-by-vehicle','getTripsByVehicle')->name('get.trips.by.vehicle');
        Route::post('/get-payment-types','getPaymentTypes')->name('get.payment.types');
        Route::post('/get-fuel-stations','getFuelStations')->name('get.fuel.stations');
        
        Route::post('/get-trip-vouchers','getTripVouchers')->name('get.trip.vouchers');
    
    });

});

//ye set karna hai 

