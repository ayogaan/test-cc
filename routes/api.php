<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WalletController;

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
Route::group(['middleware' => 'payment'], function () {
    Route::controller(DepositController::class)->group(function () {
        Route::post('/deposit', 'store');
        Route::post('/withdrawl', 'withdrawl');
        
    });

    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet', 'getById');
        
    });
});

Route::controller(WalletController::class)->group(function () {
    Route::post('/wallet', 'store');
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
