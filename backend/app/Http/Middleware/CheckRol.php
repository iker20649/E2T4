<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // Si el usuario no está logueado o su rol no es el que pedimos
        if (!$request->user() || $request->user()->rola !== $rol) {
            return response()->json([
                'message' => 'Baimenik gabe. Rol hau behar duzu: ' . $rol
            ], 403);
        }

        return $next($request);
    }
}