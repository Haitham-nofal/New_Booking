<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\Booking\BookingController;
// use App\Http\Controllers\OtpController;
use App\Http\Controllers\Api\Otp\OtpController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//user
Route::post("register",[AuthController::class,"register"]);
Route::post("login",[AuthController::class,"login"]);
Route::delete("logout",[AuthController::class,"logout"])->middleware("auth:sanctum");



Route::middleware('auth:sanctum')->group(function () {
    //category
    Route::post("category.create",[CategoryController::class,"create"]);
    Route::post("category.update/{category}",[CategoryController::class,"update"]);
    Route::delete("category.delete/{category}",[CategoryController::class,"delete"]);

    //event
    Route::post("event.create",[EventController::class,"create"]);
    Route::post("event.update/{event}",[EventController::class,"update"]);
    Route::delete("event.delete/{event}",[EventController::class,"delete"]);
    });

    //category
    Route::get("category.all",[CategoryController::class,"index"]);
    Route::get("category.show/{category}",[CategoryController::class,"show"]);

    //event
    Route::get("event.all",[EventController::class,"index"]);
    Route::get("event.show/{event}",[EventController::class,"show"]);


    Route::get("event.all.w.category",[EventController::class,"allwithCategory"]);
    Route::get("event.show.w.category/{event}",[EventController::class,"showwithCategory"]);



    Route::middleware(['auth:sanctum','role:admin'])->group(function () {
// Booking routes for admin
Route::get('booking.alldata', [BookingController::class, 'allWData']);
Route::get('booking.with.data.show/{booking}', [BookingController::class, 'showWithData']);
Route::post('booking.update/{booking}', [BookingController::class, 'update']);
});
#
Route::middleware(['auth:sanctum' ,'role:user' ])->group(function () {
    Route::get('booking.all', [BookingController::class, 'index']);
    Route::get('booking.show/{booking}', [BookingController::class, 'show']);
    Route::post('booking.create/{event}', [BookingController::class, 'create']);
    });
    Route::delete('booking.destroy/{booking}', [BookingController::class, 'destroy']);

// OTP
Route::post('otp/sendOtp', [OtpController::class, 'sendOtp']);

Route::post('otp/verifyOtp', [OtpController::class, 'verifyOtp']);
