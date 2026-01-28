<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importaciones de tus controladores
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS ---
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
// Nueva ruta para que Vue pueda registrar alumnos
Route::post('/register', [AdminController::class, 'storeIkaslea']); 

// --- RUTAS PROTEGIDAS (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::apiResource('hitzorduak', HitzorduaController::class);

    // Produktuak (Stock)
    Route::get('/produktuak', [ProduktuaController::class, 'index']);
    Route::post('/produktuak', [ProduktuaController::class, 'store']);
    Route::patch('/produktuak/{id}/stock', [ProduktuaController::class, 'updateStock']);
    Route::delete('/produktuak/{id}', [ProduktuaController::class, 'destroy']);

    // Bezeroak (Clientes)
    Route::get('/bezeroak', [BezeroaController::class, 'index']);
    Route::post('/bezeroak', [BezeroaController::class, 'store']);
    Route::put('/bezeroak/{id}', [BezeroaController::class, 'update']);
    Route::delete('/bezeroak/{id}', [BezeroaController::class, 'destroy']);

    // Admin Panel
    Route::prefix('admin')->group(function () {
        Route::get('/ikasleak', [AdminController::class, 'getIkasleak']);
        Route::delete('/ikasleak/{id}', [AdminController::class, 'destroyIkaslea']);
        Route::get('/stock-historiala', [AdminController::class, 'getStockHistoriala']);
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});