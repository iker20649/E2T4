<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // IMPORTANTE: Sin esto da error 500

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Intentar el login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'rola' => $user->rola,
                ]
            ]);
        }

        // 3. Si falla la contraseña, enviamos el error 422 de forma segura
        throw ValidationException::withMessages([
            'email' => ['Kredentzialak ez dira zuzenak.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json(['message' => 'Saioa itxi da']);
    }
}