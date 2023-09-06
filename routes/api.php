<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::group( ['prefix' => 'v1/atuh'], function ( $router ) {
        Route::post( '/register', [AuthController::class, 'registrasi'] );
        Route::post( '/login', [AuthController::class, 'login'] );
    } );

 
    // Route::middleware('jwt.auth')->prefix('v1')->group(function () {
    Route::middleware('jwt')->prefix('v1')->group(function () {
        Route::get('/quote', [QuoteController::class, 'index']);
        Route::POST('/transaction', [TransactionController::class, 'createTransaction']);
        Route::POST('/transaction/get', [TransactionController::class, 'getTransaction']);
    });


