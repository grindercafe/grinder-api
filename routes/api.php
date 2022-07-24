<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingStatusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/events',[EventController::class, 'index']);
Route::get('/events/{id}',[EventController::class, 'show']);
Route::post('/event',[EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}',[EventController::class, 'delete']);

Route::get('/event/{id}/bookings', [EventController::class, 'bookings_by_event']);

Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::post('/customer', [CustomerController::class, 'store']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'delete']);

Route::get('/status', [BookingStatusController::class, 'index']);
Route::get('/status/{id}', [BookingStatusController::class, 'show']);
Route::post('/status', [BookingStatusController::class, 'store']);
Route::put('/status/{id}', [BookingStatusController::class, 'update']);
Route::delete('/status/{id}', [BookingStatusController::class, 'delete']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::post('/booking', [BookingController::class, 'store']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'delete']);
Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
Route::patch('/bookings/{id}/updateMessageStatus', [BookingController::class, 'messageStatus']);