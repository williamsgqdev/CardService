<?php

use App\Http\Controllers\AccountController;

use App\Http\Controllers\CustomerController;
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

Route::middleware(['valid_key'])->prefix('/v1/cards')->group(function () {
    Route::post('/accounts', [AccountController::class, "createAccount"]);
    Route::post('/customers', [CustomerController::class, "createCustomer"]);
    Route::get('/customers', [CustomerController::class, "getCustomers"]);
    Route::get('/customers/{customer_id}', [CustomerController::class, "getCustomer"]);
});
