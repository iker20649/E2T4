<?php

namespace App\Http\Controllers;

use App\Models\Bezeroa;
use Illuminate\Http\Request;

class BezeroaController extends Controller
{
    public function index()
    {
        return response()->json(Bezeroa::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string',
            'abizenak' => 'required|string',
            'telefonoa' => 'nullable|string',
            'email' => 'nullable|email',
            'etxeko_bezeroa' => 'boolean',
        ]);
        $bezeroa = Bezeroa::create($validated);
        return response()->json($bezeroa, 201);
    }

    public function show(Bezeroa $bezeroa)
    {
        return response()->json($bezeroa);
    }

    public function update(Request $request, Bezeroa $bezeroa)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string',
            'abizenak' => 'sometimes|string',
            'telefonoa' => 'nullable|string',
            'email' => 'nullable|email',
            'etxeko_bezeroa' => 'boolean',
        ]);
        $bezeroa->update($validated);
        return response()->json($bezeroa);
    }

    public function destroy(Bezeroa $bezeroa)
    {
        $bezeroa->delete();
        return response()->json(null, 204);
    }
}