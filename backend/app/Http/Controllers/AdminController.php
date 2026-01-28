<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Historial; // Asegúrate de que este modelo exista si usas historial
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Devuelve la lista de todos los alumnos
     */
    public function getIkasleak()
    {
        try {
            // Buscamos los usuarios que tengan el rol de 'ikasle'
            $ikasleak = User::where('rola', 'ikasle')->get();
            return response()->json($ikasleak, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve el historial de stock
     */
    // app/Http/Controllers/AdminController.php

public function getStockHistoriala()
{
    try {
        // Usamos tu modelo real: StockMugimendua
        // Cargamos la relación 'user' para saber quién hizo el movimiento
        $historiala = \App\Models\StockMugimendua::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($historiala, 200);
    } catch (\Exception $e) {
        // Si hay un error, lo enviamos para saber qué pasa (ej: falta la columna user_id)
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Registra un nuevo ikasle
     */
    public function storeIkaslea(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rola'     => 'required|string'
        ], [
            'email.unique' => 'Email hau dagoeneko erregistratuta dago.',
            'password.min' => 'Pasahitzak gutxienez 8 karaktere izan behar ditu.',
            'password.confirmed' => 'Pasahitzak ez datoz bat.'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rola'     => $validated['rola'],
        ]);

        return response()->json([
            'message' => 'Ikaslea ondo gorde da',
            'user' => $user
        ], 201);
    }

    /**
     * Elimina un ikasle
     */
    public function destroyIkaslea($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Ikaslea ezabatua'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ezin izan da ezabatu'], 500);
        }
    }
}