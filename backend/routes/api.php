<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Publikoa: Login-a
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Babestutako Route-ak (Tokena behar da)
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. ERABILTZAILEA ETA PROFILA
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/profile/update', [ProfileController::class, 'update']); 

    // 2. HITZORDUAK (Eguneroko lana)
    // apiResource-k automatikoki sortzen ditu: index, store, show, update eta DESTROY
    Route::apiResource('hitzorduak', HitzorduaController::class);

    // 3. STOCKA (Produktuen kudeaketa)
    Route::get('/produktuak', [ProduktuaController::class, 'index']);
    Route::post('/produktuak', [ProduktuaController::class, 'store']);
    Route::patch('/produktuak/{id}/stock', [ProduktuaController::class, 'updateStock']);
    Route::delete('/produktuak/{id}', [ProduktuaController::class, 'destroy']);

    // 4. BEZEROAK (Josebaren atala)
    Route::get('/bezeroak', [BezeroaController::class, 'index']);
    Route::post('/bezeroak', [BezeroaController::class, 'store']);
    Route::put('/bezeroak/{id}', [BezeroaController::class, 'update']);
    Route::delete('/bezeroak/{id}', [BezeroaController::class, 'destroy']); // <--- HAU GEHITUTA

    // 5. ADMINISTRAZIOA (Irakasleentzako soilik)
    Route::get('admin/ikasleak', [AdminController::class, 'getIkasleak']);
    Route::delete('admin/ikasleak/{id}', [AdminController::class, 'destroyIkaslea']);
    Route::get('admin/stock-historiala', [AdminController::class, 'getStockHistoriala']);
    
    // Logout-a
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});