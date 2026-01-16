<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\UserController;
// Aquí iréis importando el resto de controladores mañana (BezeroaController, etc.)

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS ---
// (Las rutas de Login y Registro las gestiona automáticamente routes/auth.php de Breeze)


// --- RUTAS PROTEGIDAS (Solo usuarios logueados) ---
Route::middleware(['auth:sanctum'])->group(function () {

    // 1. Datos del usuario actual
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 2. Gestión de Citas (Día 8: Lógica de no solapamiento)
    Route::post('/hitzorduak', [HitzorduaController::class, 'store']);
    Route::get('/hitzorduak', [HitzorduaController::class, 'index']);

    // 3. Rutas de consulta (Cualquier alumno puede ver productos o servicios)
    // Route::get('/zerbitzuak', [ZerbitzuaController::class, 'index']);
    
    // --- RUTAS DE ADMINISTRACIÓN (Solo Irakasleak) ---
    Route::middleware(['rol:irakaslea'])->group(function () {
        
        // Ejemplo: Solo el profesor puede borrar citas o gestionar el stock crítico
        Route::delete('/hitzorduak/{id}', [HitzorduaController::class, 'destroy']);
        
        Route::get('/admin/stats', function () {
            return response()->json(['message' => 'Hemen ikus ditzakezu estatistika guztiak.']);
        });

    });
});