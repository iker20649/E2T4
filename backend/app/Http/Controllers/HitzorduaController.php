<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use App\Models\Zerbitzua; // Importante para saber cuánto dura cada servicio
use Illuminate\Http\Request;
use Carbon\Carbon;

class HitzorduaController extends Controller
{
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'bezero_id' => 'required|exists:bezeroak,id',
            'profesional_id' => 'required|exists:profesionalak,id',
            'zerbitzu_id' => 'required|exists:zerbitzuak,id',
            'data' => 'required|date',
            'hasiera_ordua' => 'required',
        ]);

        // 2. BUSCAR DURACIÓN DEL SERVICIO
        $zerbitzua = Zerbitzua::find($request->zerbitzu_id);
        $iraupena = $zerbitzua->iraupena; // Sacamos los minutos (ej: 30)

        // 3. CALCULAR HORARIOS CON CARBON
        $hasiera = Carbon::parse($request->data . ' ' . $request->hasiera_ordua);
        $amaiera = $hasiera->copy()->addMinutes($iraupena);

        // 4. LÓGICA DE SOLAPAMIENTO (No dos citas a la vez para el mismo alumno)
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
            return response()->json([
                'message' => 'Errorea: Profesional honek badu beste hitzordu bat ordu tarte horretan.'
            ], 422);
        }

        // 5. GUARDAR CITA
        $hitzordua = Hitzordua::create([
            'bezero_id' => $request->bezero_id,
            'profesional_id' => $request->profesional_id,
            'data' => $request->data,
            'hasiera_ordua' => $hasiera->format('H:i:s'),
            'amaiera_ordua' => $amaiera->format('H:i:s'),
            'iraupena' => $iraupena,
        ]);

        return response()->json([
            'message' => 'Hitzordua ondo gorde da!',
            'data' => $hitzordua
        ], 201);
    }

    public function index()
    {
        // Devolvemos todas las citas con la información de cliente y profesional
        return Hitzordua::with(['bezeroa', 'profesionala'])->get();
    }
}