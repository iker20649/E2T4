<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use App\Models\Produktua;
use Illuminate\Http\Request;

class HitzorduaController extends Controller
{
    public function index()
    {
        return Hitzordua::with('bezeroa')->get();
    }

    public function getStats()
    {
        return response()->json([
            'stats' => [
                'hitzorduak' => Hitzordua::count(),
                'stockBaxua' => Produktua::whereColumn('stock', '<=', 'stock_minimo')->count(),
                'produktuaGuztiak' => Produktua::count(),
            ],
            // Gaurko lehen 3 hitzorduak bezeroaren izenarekin
            'gaurkoHitzorduak' => Hitzordua::with('bezeroa')
                ->whereDate('data', '>=', now()->toDateString())
                ->orderBy('data', 'asc')
                ->take(3)
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bezero_id' => 'required|exists:bezeroak,id',
            'user_id' => 'required|exists:users,id',
            'data' => 'required|date',
            'deskribapena' => 'nullable|string',
        ]);

        return Hitzordua::create($validated);
    }
}