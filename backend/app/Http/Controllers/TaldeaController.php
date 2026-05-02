<?php

namespace App\Http\Controllers;

use App\Models\Taldea;
use Illuminate\Http\Request;

class TaldeaController extends Controller
{
    public function index()
    {
        return response()->json(Taldea::with('ikasleak')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string|unique:taldeak,izena',
        ]);
        $taldea = Taldea::create($validated);
        return response()->json($taldea, 201);
    }

    public function show(Taldea $taldea)
    {
        return response()->json($taldea->load('ikasleak', 'ordutegiak'));
    }

    public function update(Request $request, Taldea $taldea)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string|unique:taldeak,izena,' . $taldea->id,
        ]);
        $taldea->update($validated);
        return response()->json($taldea);
    }

    public function destroy(Taldea $taldea)
    {
        $taldea->delete();
        return response()->json(null, 204);
    }
}