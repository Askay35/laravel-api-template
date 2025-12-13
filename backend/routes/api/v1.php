<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResendVerificationEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
| Routes are versioned: api/v1/{locale?}/...
| Supported locales: en, ru (optional - uses default from config if not specified)
|
*/

Route::post('/register', RegisterController::class);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/email/verify/{user}/{hash}', VerifyEmailController::class)
        ->name('verification.verify');

    Route::post('/email/resend', ResendVerificationEmailController::class)
        ->name('verification.resend');
});