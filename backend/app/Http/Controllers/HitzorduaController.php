<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use App\Models\Bezeroa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HitzorduaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rola === 'irakasle') {
            return Hitzordua::with('user:id,name')->orderBy('data', 'asc')->get();
        }
        return Hitzordua::where('user_id', $user->id)->orderBy('data', 'asc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bezeroa' => 'required|string|max:255',
            'data' => 'required|date',
            'deskribapena' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // 1. Hitzordua sortu
        $hitzordua = Hitzordua::create([
            'bezeroa' => $validated['bezeroa'],
            'data' => $validated['data'],
            'deskribapena' => $validated['deskribapena'] ?? '',
            'user_id' => $userId,
            'finalizatuta' => false
        ]);

        // 2. AUTOMATISMOA: Bezeroa sortu edo eguneratu
        $bezeroa = Bezeroa::firstOrCreate(
            [
                'izena' => $validated['bezeroa'],
                'user_id' => $userId
            ],
            [
                'abizenak' => '', 
                'bisita_kopurua' => 0,
                'azken_bisita' => $validated['data'],
                'deskribapena' => 'Hitzordu bidez automatikoki sortua.'
            ]
        );

        // Bisitak eguneratu
        $bezeroa->increment('bisita_kopurua');
        $bezeroa->update(['azken_bisita' => $validated['data']]);

        return response()->json($hitzordua, 201);
    }

    public function update(Request $request, $id)
    {
        $hitzordua = Hitzordua::findOrFail($id);
        $hitzordua->update($request->all());
        return response()->json($hitzordua);
    }

    public function destroy($id)
    {
        $hitzordua = Hitzordua::findOrFail($id);
        $hitzordua->delete();
        return response()->json(['message' => 'Hitzordua ezabatuta']);
    }
}