<?php

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

Route::get('/events',[EventController::class, 'index'])->name('event.index');
Route::get('/events/{id}',[EventController::class, 'show'])->name('event.show');
Route::post('/event',[EventController::class, 'store'])->name('event.store');
Route::delete('/events/{id}',[EventController::class, 'delete'])->name('event.delete');
Route::put('/events/{id}', [EventController::class, 'update'])->name('event.update');