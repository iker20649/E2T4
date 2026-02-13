<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Balidazioa (Honek ematen du 422 errorea datuak gaizki badoaz)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Saiatu saioa hasten
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 3. Tokena sortu (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'rola' => $user->rola, // Ezinbestekoa Frontend-erako
                ]
            ]);
        }

        // Kredentzialak okerrak badira
        return response()->json([
            'message' => 'Kredentzialak ez dira zuzenak.'
        ], 401);
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Saioa itxi da']);
    }
}