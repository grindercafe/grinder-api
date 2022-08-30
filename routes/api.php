<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Models\Booking;
use App\Models\BookingTable;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/event', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'delete']);
Route::get('/events/{id}/bookings', [EventController::class, 'bookings_by_event']);

Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::post('/customer', [CustomerController::class, 'store']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'delete']);
Route::get('/customers/{id}/bookings', [CustomerController::class, 'bookings_by_customer']);

// Route::get('/status', [BookingStatusController::class, 'index']);
// Route::get('/status/{id}', [BookingStatusController::class, 'show']);
// Route::post('/status', [BookingStatusController::class, 'store']);
// Route::put('/status/{id}', [BookingStatusController::class, 'update']);
// Route::delete('/status/{id}', [BookingStatusController::class, 'delete']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/bookings/{booking:uuid}', [BookingController::class, 'show']);
Route::post('/booking', [BookingController::class, 'store']);
Route::post('/test_booking', [BookingController::class, 'testStore']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'delete']);
Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
Route::patch('/bookings/{id}/updateMessageStatus', [BookingController::class, 'messageStatus']);

Route::get('/tables', [TableController::class, 'index']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/payment', [PaymentController::class, 'createInvoice']);
Route::get('/result', [PaymentController::class, 'result']);
Route::get('/update_payment_status', [PaymentController::class, 'updatePaymentStatus']);

Route::get('/test',  function() {
    return Booking::whereHas('payment', function($q) {
        $q->where('status', 'timeout');
    })->count();
});

// Route::post('test_payment', [PaymentController::class, 'createTestInvoice']);
// Route::get('/test_result', [PaymentController::class, 'testResult']);

