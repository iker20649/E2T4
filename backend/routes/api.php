<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\MaterialaController;
use App\Http\Controllers\ProduktuaController;
use App\Http\Controllers\HitzorduaController;

// Ruta pública
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de Administración (Ahora accesibles en /api/admin/...)
    Route::get('/admin/ikasleak', [BezeroaController::class, 'index']);
    Route::get('/admin/stock-historiala', [MaterialaController::class, 'index']);
    Route::get('/admin/stats', [AdminController::class, 'index']);

    // Rutas estándar (Accesibles en /api/...)
   // Rutas estándar (Accesibles en /api/...)
Route::get('/bezeroak', [BezeroaController::class, 'index']);
Route::get('/hitzorduak', [HitzorduaController::class, 'index']);

// Materiales
Route::get('/materialas', [MaterialaController::class, 'index']);
Route::post('/materialas', [MaterialaController::class, 'store']); // <--- AÑADIR ESTA

// Productos
Route::get('/produktuak', [ProduktuaController::class, 'index']);
Route::post('/produktuak', [ProduktuaController::class, 'store']); // <--- AÑADIR ESTA
});
