<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;

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

Route::group(['middleware' => 'verify_api_key'], function () {
    // { PUBLIC ROUTES } //
    // visible events only

    Route::get('/visible_events/{id}', [EventController::class, 'visible_event']);
    Route::get('/visible_events', [EventController::class, 'visible_events']);

    // get single booking
    Route::get('/bookings/{booking:uuid}', [BookingController::class, 'show']);
    // create a booking
    Route::post('/booking', [BookingController::class, 'store']);

    Route::get('/tables', [TableController::class, 'index']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/payment', [PaymentController::class, 'createInvoice']);
    Route::get('/result', [PaymentController::class, 'result']);

    // { PRIVATE ROUTES } //

    Route::group(['middleware' => 'auth:sanctum'], function () {
        // paginated events
        Route::get('/events', [EventController::class, 'index']); //auth
        // all events orderd by date
        Route::get('/allEvents', [EventController::class, 'allEvents']); //auth
        Route::get('/events/{id}', [EventController::class, 'show']); //auth
        Route::post('/event', [EventController::class, 'store']);
        Route::put('/events/{id}', [EventController::class, 'update']);
        Route::delete('/events/{id}', [EventController::class, 'delete']);

        Route::get('/events/{id}/bookings', [EventController::class, 'bookings_by_event']);

        Route::patch('/events/{id}/update_visibility', [EventController::class, 'update_is_visible']);

        Route::post('/events/{id}/hide_tables', [EventController::class, 'hide_tables']);

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/{id}', [CustomerController::class, 'show']);
        Route::post('/customer', [CustomerController::class, 'store']);
        Route::put('/customers/{id}', [CustomerController::class, 'update']);
        Route::delete('/customers/{id}', [CustomerController::class, 'delete']);

        Route::get('/bookings', [BookingController::class, 'index']);
        Route::get('/bookings/dashboard/{id}', [BookingController::class, 'showInDashboard']);
        Route::put('/update_event_in_booking/{id}', [BookingController::class, 'updateRelatedEvent']);
        Route::put('/update_payment_status_in_booking/{id}', [BookingController::class, 'updatePaymentStatus']);
        Route::delete('/bookings/{id}', [BookingController::class, 'delete']);
        Route::put('/bookings/{id}/updateTables', [BookingController::class, 'update_tables']);

        Route::post('/logout', [LoginController::class, 'logout']);

        Route::get('/update_payment_status', [PaymentController::class, 'updatePaymentStatus']);
    });
});


// Route::post('test_payment', [PaymentController::class, 'createTestInvoice']);
// Route::get('/test_result', [PaymentController::class, 'testResult']);