<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Rutas públicas (Login)
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Rutas protegidas (Requieren estar logueado)
Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario y Perfil
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/profile/update', [ProfileController::class, 'update']); 

    // Citas (Hitzorduak) - apiResource crea GET, POST, PUT, DELETE
    Route::apiResource('hitzorduak', HitzorduaController::class);

    // Stock (Produktuak)
    Route::get('/produktuak', [ProduktuaController::class, 'index']);
    Route::post('/produktuak', [ProduktuaController::class, 'store']);
    Route::patch('/produktuak/{id}/stock', [ProduktuaController::class, 'updateStock']);
    Route::delete('/produktuak/{id}', [ProduktuaController::class, 'destroy']);
    
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});