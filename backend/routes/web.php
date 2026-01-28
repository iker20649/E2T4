<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Esta ruta solo devuelve la versión de Laravel para comprobar que el server vive.
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// IMPORTANTE: Hemos eliminado Route::post('/login' de aquí.
// El login ahora vive exclusivamente en api.php.

// Si usas Laravel Breeze o Jetstream, comenta o revisa esta línea 
// para que no cree rutas de login ocultas que choquen con tu API.
// require __DIR__.'/auth.php';