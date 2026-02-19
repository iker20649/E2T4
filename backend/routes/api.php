<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\MaterialaController;
use App\Http\Controllers\ProduktuaController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Grupo para capturar el error de /api/api del frontend
    Route::prefix('api')->group(function () {
        Route::get('/admin/ikasleak', [BezeroaController::class, 'index']);
        Route::get('/admin/stock-historiala', [MaterialaController::class, 'index']); // La que te fallaba
        Route::get('/admin/stats', [AdminController::class, 'index']);
        Route::get('/user', function (Request $request) { return $request->user(); });
    });

    // Rutas estándar
    Route::get('/admin/ikasleak', [BezeroaController::class, 'index']);
    Route::get('/bezeroak', [BezeroaController::class, 'index']);
    Route::get('/hitzorduak', [App\Http\Controllers\HitzorduaController::class, 'index']);
    Route::get('/materialas', [MaterialaController::class, 'index']);
    Route::get('/produktuak', [ProduktuaController::class, 'index']);
});
