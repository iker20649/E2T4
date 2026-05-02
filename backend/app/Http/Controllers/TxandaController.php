<?php

namespace App\Http\Controllers;

use App\Models\Txanda;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TxandaController extends Controller
{
    public function index()
    {
        return response()->json(Txanda::with('ikaslea')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ikasle_id' => 'required|exists:ikasleak,id',
            'data' => 'required|date|after_or_equal:today',
            'hasiera_ordua' => 'required|date_format:H:i',
            'bukaera_ordua' => 'required|date_format:H:i|after:hasiera_ordua',
            'rola' => ['required', Rule::in(['harrera', 'laguntzaile', 'bestea'])],
            'oharrak' => 'nullable|string',
        ]);

        $gainezkatu = Txanda::where('ikasle_id', $validated['ikasle_id'])
            ->where('data', $validated['data'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('hasiera_ordua', [$validated['hasiera_ordua'], $validated['bukaera_ordua']])
                  ->orWhereBetween('bukaera_ordua', [$validated['hasiera_ordua'], $validated['bukaera_ordua']])
                  ->orWhere(function ($sq) use ($validated) {
                      $sq->where('hasiera_ordua', '<=', $validated['hasiera_ordua'])
                        ->where('bukaera_ordua', '>=', $validated['bukaera_ordua']);
                  });
            })->exists();

        if ($gainezkatu) {
            return response()->json(['error' => 'Ikasleak badu jadanik txanda gainezkatzen dena'], 422);
        }

        $txanda = Txanda::create($validated);
        return response()->json($txanda, 201);
    }

    public function show(Txanda $txanda)
    {
        return response()->json($txanda->load('ikaslea'));
    }

    public function update(Request $request, Txanda $txanda)
    {
        $validated = $request->validate([
            'ikasle_id' => 'sometimes|exists:ikasleak,id',
            'data' => 'sometimes|date|after_or_equal:today',
            'hasiera_ordua' => 'sometimes|date_format:H:i',
            'bukaera_ordua' => 'sometimes|date_format:H:i|after:hasiera_ordua',
            'rola' => ['sometimes', Rule::in(['harrera', 'laguntzaile', 'bestea'])],
            'oharrak' => 'nullable|string',
        ]);

        $ikasleId = $validated['ikasle_id'] ?? $txanda->ikasle_id;
        $data = $validated['data'] ?? $txanda->data;
        $hasiera = $validated['hasiera_ordua'] ?? $txanda->hasiera_ordua;
        $bukaera = $validated['bukaera_ordua'] ?? $txanda->bukaera_ordua;

        $gainezkatu = Txanda::where('ikasle_id', $ikasleId)
            ->where('data', $data)
            ->where('id', '!=', $txanda->id)
            ->where(function ($q) use ($hasiera, $bukaera) {
                $q->whereBetween('hasiera_ordua', [$hasiera, $bukaera])
                  ->orWhereBetween('bukaera_ordua', [$hasiera, $bukaera])
                  ->orWhere(function ($sq) use ($hasiera, $bukaera) {
                      $sq->where('hasiera_ordua', '<=', $hasiera)
                        ->where('bukaera_ordua', '>=', $bukaera);
                  });
            })->exists();

        if ($gainezkatu) {
            return response()->json(['error' => 'Txanda gainezkatzen da'], 422);
        }

        $txanda->update($validated);
        return response()->json($txanda);
    }

    public function destroy(Txanda $txanda)
    {
        $txanda->delete();
        return response()->json(null, 204);
    }
}