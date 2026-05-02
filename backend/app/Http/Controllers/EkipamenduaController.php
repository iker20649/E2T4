<?php

namespace App\Http\Controllers;

use App\Models\Ekipamendua;
use Illuminate\Http\Request;

class EkipamenduaController extends Controller
{
    public function index()
    {
        return response()->json(Ekipamendua::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string',
            'stock' => 'nullable|integer',
            'stock_minimoa' => 'nullable|integer',
        ]);
        $ekipamendua = Ekipamendua::create($validated);
        return response()->json($ekipamendua, 201);
    }

    public function show(Ekipamendua $ekipamendua)
    {
        return response()->json($ekipamendua);
    }

    public function update(Request $request, Ekipamendua $ekipamendua)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string',
            'stock' => 'sometimes|integer',
            'stock_minimoa' => 'sometimes|integer',
        ]);
        $ekipamendua->update($validated);
        return response()->json($ekipamendua);
    }

    public function destroy(Ekipamendua $ekipamendua)
    {
        $ekipamendua->delete();
        return response()->json(null, 204);
    }
}