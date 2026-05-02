<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BezeroaController;
use App\Http\Controllers\EkipamenduaController;
use App\Http\Controllers\IkasleaController;
use App\Http\Controllers\HitzorduaController;
use App\Http\Controllers\TxandaController;
use App\Http\Controllers\ZerbitzuaController;
use App\Http\Controllers\TaldeaController;
use App\Http\Controllers\ErabiltzaileaController;
use App\Http\Controllers\AuthController;

// Autentikazioa (aukerakoa)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('bezeroak', BezeroaController::class);
    Route::apiResource('ekipamenduak', EkipamenduaController::class);
    Route::apiResource('ikasleak', IkasleaController::class);
    Route::apiResource('hitzorduak', HitzorduaController::class);
    Route::apiResource('txandak', TxandaController::class);
    Route::apiResource('zerbitzuak', ZerbitzuaController::class);
    Route::apiResource('taldeak', TaldeaController::class);
    Route::apiResource('erabiltzaileak', ErabiltzaileaController::class);
});