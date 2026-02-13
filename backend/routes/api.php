<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MaileguaController;

// --- PUBLIKOAK ---
// .name('login') gehituta errorea saihesteko
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/register', [AdminController::class, 'storeIkaslea']); 

// Probatzeko, jarri materialak hemen (babesik gabe) nabigatzailean ikusteko
Route::get('/materialak', [ProduktuaController::class, 'getMaterialak']);

// --- BABESTUAK ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard-stats', [HitzorduaController::class, 'getStats']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Stock eguneraketa babestuta mantentzen dugu
    Route::patch('/produktuak/{id}/stock', [ProduktuaController::class, 'updateStock']);

    // Baliabideak
    Route::apiResource('hitzorduak', HitzorduaController::class);
    Route::apiResource('bezeroak', BezeroaController::class);
    Route::apiResource('produktuak', ProduktuaController::class);
    
    // Maileguak
    Route::post('/maileguak', [MaileguaController::class, 'store']);
    Route::put('/maileguak/{id}', [MaileguaController::class, 'update']);

    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/ikasleak', [AdminController::class, 'getIkasleak']);
        Route::get('/stock-historiala', [AdminController::class, 'getStockHistoriala']);
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});