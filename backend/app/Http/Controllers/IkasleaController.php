<?php

namespace App\Http\Controllers;

use App\Models\Ikaslea;
use Illuminate\Http\Request;

class IkasleaController extends Controller
{
    public function index()
    {
        return response()->json(Ikaslea::with('taldea')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string',
            'abizena' => 'required|string',
            'talde_id' => 'required|exists:taldeak,id',
        ]);
        $ikaslea = Ikaslea::create($validated);
        return response()->json($ikaslea, 201);
    }

    public function show(Ikaslea $ikaslea)
    {
        return response()->json($ikaslea->load('taldea', 'txandak', 'ekipamenduak'));
    }

    public function update(Request $request, Ikaslea $ikaslea)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string',
            'abizena' => 'sometimes|string',
            'talde_id' => 'sometimes|exists:taldeak,id',
        ]);
        $ikaslea->update($validated);
        return response()->json($ikaslea);
    }

    public function destroy(Ikaslea $ikaslea)
    {
        $ikaslea->delete();
        return response()->json(null, 204);
    }
}