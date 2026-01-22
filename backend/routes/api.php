<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Erabiltzailea
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/profile/update', [ProfileController::class, 'update']); 

    // Hitzorduak
    Route::apiResource('hitzorduak', HitzorduaController::class);

    // Stocka
    Route::get('/produktuak', [ProduktuaController::class, 'index']);
    Route::post('/produktuak', [ProduktuaController::class, 'store']);
    Route::patch('/produktuak/{id}/stock', [ProduktuaController::class, 'updateStock']);
    Route::delete('/produktuak/{id}', [ProduktuaController::class, 'destroy']);
    
    // Administrazioa (Irakaslea)
    Route::get('admin/ikasleak', [AdminController::class, 'getIkasleak']);
    Route::delete('admin/ikasleak/{id}', [AdminController::class, 'destroyIkaslea']);
    Route::get('admin/stock-historiala', [AdminController::class, 'getStockHistoriala']);
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});