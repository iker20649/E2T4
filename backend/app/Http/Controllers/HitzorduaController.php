<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use App\Models\Zerbitzua;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HitzorduaController extends Controller
{
    // Listar las citas del usuario logueado
    public function index(Request $request)
    {
        try {
            // Pillamos el ID del usuario que ha hecho login
            $userId = $request->user()->id;

            // Buscamos sus citas en la tabla 'hitzorduak' usando 'bezero_id'
            $hitzorduak = Hitzordua::where('bezero_id', $userId)->get();

            return response()->json($hitzorduak, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errorea datuak lortzean',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Guardar una nueva cita (Tu lógica del Día 8)
    public function store(Request $request)
    {
        $request->validate([
            'bezero_id' => 'required',
            'profesional_id' => 'required',
            'zerbitzu_id' => 'required',
            'data' => 'required|date',
            'hasiera_ordua' => 'required',
        ]);

        $zerbitzua = Zerbitzua::find($request->zerbitzu_id);
        if (!$zerbitzua) return response()->json(['message' => 'Zerbitzua ez da aurkitu'], 404);

        $iraupena = $zerbitzua->iraupena;
        $hasiera = Carbon::parse($request->data . ' ' . $request->hasiera_ordua);
        $amaiera = $hasiera->copy()->addMinutes($iraupena);

        // Lógica de solapamiento
        $solapatuta = Hitzordua::where('profesional_id', $request->profesional_id)
            ->where('data', $request->data)
            ->where(function ($query) use ($hasiera, $amaiera) {
                $query->where(function ($q) use ($hasiera, $amaiera) {
                    $q->where('hasiera_ordua', '>=', $hasiera->format('H:i:s'))
                      ->where('hasiera_ordua', '<', $amaiera->format('H:i:s'));
                })
                ->orWhere(function ($q) use ($hasiera, $amaiera) {
                    $q->where('amaiera_ordua', '>', $hasiera->format('H:i:s'))
                      ->where('amaiera_ordua', '<=', $amaiera->format('H:i:s'));
                });
            })->exists();

        if ($solapatuta) {
            return response()->json(['message' => 'Profesionala okupatuta dago'], 422);
        }

        $hitzordua = Hitzordua::create([
            'bezero_id' => $request->bezero_id,
            'profesional_id' => $request->profesional_id,
            'data' => $request->data,
            'hasiera_ordua' => $hasiera->format('H:i:s'),
            'amaiera_ordua' => $amaiera->format('H:i:s'),
            'iraupena' => $iraupena,
            'oharrak' => $request->oharrak
        ]);

        return response()->json($hitzordua, 201);
    }
}