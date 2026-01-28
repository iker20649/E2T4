<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Hemos quitado $middleware->statefulApi(); para que no pida CSRF
        
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'api/*', // Esto ignora el CSRF en toda la API
            'login'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();