<?php

namespace App\Http\Controllers;

use App\Models\Zerbitzua;
use Illuminate\Http\Request;

class ZerbitzuaController extends Controller
{
    public function index()
    {
        return response()->json(Zerbitzua::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string',
            'prezioa' => 'required|numeric',
            'etxeko_prezioa' => 'nullable|numeric',
            'iraupena' => 'required|integer',
        ]);
        $zerbitzua = Zerbitzua::create($validated);
        return response()->json($zerbitzua, 201);
    }

    public function show(Zerbitzua $zerbitzua)
    {
        return response()->json($zerbitzua);
    }

    public function update(Request $request, Zerbitzua $zerbitzua)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string',
            'prezioa' => 'sometimes|numeric',
            'etxeko_prezioa' => 'nullable|numeric',
            'iraupena' => 'sometimes|integer',
        ]);
        $zerbitzua->update($validated);
        return response()->json($zerbitzua);
    }

    public function destroy(Zerbitzua $zerbitzua)
    {
        $zerbitzua->delete();
        return response()->json(null, 204);
    }
}