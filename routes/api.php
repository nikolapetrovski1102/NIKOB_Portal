<?php

use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\VerificationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::get('user', [AuthController::class, 'user']);

Route::post("email/verify", [VerificationApiController::class, 'verify'])->name('verificationapi.verify');
Route::post("email/resend", [ VerificationApiController::class, 'resend'])->name('verificationapi.resend');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('clients', ClientsController::class );
});