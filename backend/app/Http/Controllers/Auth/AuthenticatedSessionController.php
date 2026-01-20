<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- IMPORTANTE
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validamos los datos que vienen de Vue
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentamos el login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email edo pasahitza okerra.'
            ], 401);
        }

        // 3. Si el login es correcto, obtenemos al usuario
        $user = Auth::user();

        // 4. (Opcional) Borramos tokens antiguos para que solo tenga uno activo
        $user->tokens()->delete();

        // 5. Generamos el nuevo Token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // 6. Respondemos a Vue con el Token y los datos del usuario
        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'Login zuzena!'
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Al hacer Logout, borramos el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Saioa ondo itxi da.'
        ]);
    }
}