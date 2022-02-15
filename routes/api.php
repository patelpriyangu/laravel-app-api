<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\LoanApplicationsController;
use App\Http\Controllers\Api\LoanPaymentController;
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

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::apiResource('loan-applications', LoanApplicationsController::class);

Route::group([
    'middleware' => 'api',
    'prefix' => 'repayment',
], function ($router) {
    Route::post('pay', [LoanPaymentController::class, 'pay']);
});