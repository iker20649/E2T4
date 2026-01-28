<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Añadimos Request
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        // 1. Validar credenciales
        $request->authenticate();

        $user = Auth::user();

        // 2. Limpiar tokens anteriores
        $user->tokens()->delete();

        // 3. Crear el token de acceso
        // Cambiamos 'token' por 'access_token' para que coincida con lo que busca el Test
        $token = $user->createToken('token-app')->plainTextToken;

        return response()->json([
            'access_token' => $token, 
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'rola' => $user->rola, 
            ]
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        // Añadimos una comprobación para evitar el error de TransientToken en los tests
        $token = $request->user()->currentAccessToken();
        
        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }

        return response()->json(['message' => 'Saioa itxi da']);
    }
}