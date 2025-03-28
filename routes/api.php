<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Api\AccountController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('accounts', AccountController::class);
// });

use App\Http\Controllers\AccountController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/accounts', [AccountController::class, 'store']); // Create account
    Route::get('/accounts/{account_number}', [AccountController::class, 'show']); // Get account details
    Route::put('/accounts/{account_number}', [AccountController::class, 'update']); // Update account
    Route::delete('/accounts/{account_number}', [AccountController::class, 'destroy']); // Deactivate account
    Route::patch('/accounts/{account_number}/restore', [AccountController::class, 'restore']);

});


use App\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transactions', [TransactionController::class, 'store']); // Create a transaction
    Route::get('/transactions', [TransactionController::class, 'index']); // Get transactions for an account
    Route::post('/transactions/transfer', [TransactionController::class, 'transfer']);

});

use App\Http\Controllers\PdfController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/accounts/{account_number}/statement', [PdfController::class, 'generateStatement']);
});


