<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importamos los controladores necesarios
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 🔓 RUTAS PÚBLICAS ---
// Esta ruta permite que Vue envíe el email y password para recibir el Token
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


// --- 🔐 RUTAS PROTEGIDAS (Solo con Bearer Token) ---
Route::middleware('auth:sanctum')->group(function () {

    // 1. Obtener los datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 2. Gestión de Citas (Hitzorduak)
    // El método index debería devolver las citas del usuario: $request->user()->hitzorduak
    Route::get('/hitzorduak', [HitzorduaController::class, 'index']);
    
    // Para crear nuevas citas desde el frontend
    Route::post('/hitzorduak', [HitzorduaController::class, 'store']);

    // 3. Ruta de Logout (Para invalidar el token al salir)
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

});