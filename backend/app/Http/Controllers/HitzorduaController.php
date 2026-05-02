<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use Illuminate\Http\Request;

class HitzorduaController extends Controller
{
    public function index()
    {
        return response()->json(Hitzordua::with(['bezeroa', 'ikaslea'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lekua' => 'required|integer|min:1',
            'data' => 'required|date',
            'hasiera_ordua' => 'required|date_format:H:i',
            'bukaera_ordua' => 'required|date_format:H:i|after:hasiera_ordua',
            'iruzkinak' => 'nullable|string',
            'ikasle_id' => 'nullable|exists:ikasleak,id',
            'bezero_id' => 'required|exists:bezeroak,id',
        ]);

        // Gainezkatze kontrola leku berean eta ordu tarte berean
        $gainezkatu = Hitzordua::where('data', $validated['data'])
            ->where('lekua', $validated['lekua'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('hasiera_ordua', [$validated['hasiera_ordua'], $validated['bukaera_ordua']])
                  ->orWhereBetween('bukaera_ordua', [$validated['hasiera_ordua'], $validated['bukaera_ordua']])
                  ->orWhere(function ($sq) use ($validated) {
                      $sq->where('hasiera_ordua', '<=', $validated['hasiera_ordua'])
                        ->where('bukaera_ordua', '>=', $validated['bukaera_ordua']);
                  });
            })->exists();

        if ($gainezkatu) {
            return response()->json(['error' => 'Leku hori jadanik hartuta dago ordu tarte horretan'], 422);
        }

        $hitzordua = Hitzordua::create($validated);
        return response()->json($hitzordua, 201);
    }

    public function show(Hitzordua $hitzordua)
    {
        return response()->json($hitzordua->load(['bezeroa', 'ikaslea']));
    }

    public function update(Request $request, Hitzordua $hitzordua)
    {
        $validated = $request->validate([
            'lekua' => 'sometimes|integer|min:1',
            'data' => 'sometimes|date',
            'hasiera_ordua' => 'sometimes|date_format:H:i',
            'bukaera_ordua' => 'sometimes|date_format:H:i|after:hasiera_ordua',
            'iruzkinak' => 'nullable|string',
            'ikasle_id' => 'nullable|exists:ikasleak,id',
            'bezero_id' => 'sometimes|exists:bezeroak,id',
        ]);

        $data = $validated['data'] ?? $hitzordua->data;
        $hasiera = $validated['hasiera_ordua'] ?? $hitzordua->hasiera_ordua;
        $bukaera = $validated['bukaera_ordua'] ?? $hitzordua->bukaera_ordua;
        $lekua = $validated['lekua'] ?? $hitzordua->lekua;

        $gainezkatu = Hitzordua::where('data', $data)
            ->where('lekua', $lekua)
            ->where('id', '!=', $hitzordua->id)
            ->where(function ($q) use ($hasiera, $bukaera) {
                $q->whereBetween('hasiera_ordua', [$hasiera, $bukaera])
                  ->orWhereBetween('bukaera_ordua', [$hasiera, $bukaera])
                  ->orWhere(function ($sq) use ($hasiera, $bukaera) {
                      $sq->where('hasiera_ordua', '<=', $hasiera)
                        ->where('bukaera_ordua', '>=', $bukaera);
                  });
            })->exists();

        if ($gainezkatu) {
            return response()->json(['error' => 'Ordu tarte horretan leku hori ez dago libre'], 422);
        }

        $hitzordua->update($validated);
        return response()->json($hitzordua);
    }

    public function destroy(Hitzordua $hitzordua)
    {
        $hitzordua->delete();
        return response()->json(null, 204);
    }
}