<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DebtController;
use App\Http\Controllers\Api\DebtorController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Lectura: cualquier usuario autenticado
    Route::get('/debtors', [DebtorController::class, 'index']);
    Route::get('/debtors/{debtor}', [DebtorController::class, 'show']);

    Route::get('/debts', [DebtController::class, 'index']);
    Route::get('/debts/{debt}', [DebtController::class, 'show']);

    // Escritura: solo admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/debtors', [DebtorController::class, 'store']);
        Route::put('/debtors/{debtor}', [DebtorController::class, 'update']);
        Route::delete('/debtors/{debtor}', [DebtorController::class, 'destroy']);
        Route::post('/debts', [DebtController::class, 'store']);
        Route::put('/debts/{debt}', [DebtController::class, 'update']);
        Route::delete('/debts/{debt}', [DebtController::class, 'destroy']);
    });
});
