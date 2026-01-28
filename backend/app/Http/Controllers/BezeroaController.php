<?php

namespace App\Http\Controllers;

use App\Models\Bezeroa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BezeroaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Evitamos errores si el usuario no tiene rol definido
        if ($user && $user->rola === 'irakasle') {
            return Bezeroa::with('user')
                ->get()
                ->groupBy(function($bezero) {
                    return $bezero->user ? $bezero->user->name : 'Jaberik gabe'; 
                });
        }

        return Bezeroa::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string|max:255',
            'abizenak' => 'nullable|string|max:255',
            'telefonoa' => 'nullable|string',
            'deskribapena' => 'nullable|string',
        ]);

        // Usar 'new' y 'save' es más seguro que 'create' si el modelo no tiene $fillable
        $bezeroa = new Bezeroa();
        $bezeroa->izena = $validated['izena'];
        $bezeroa->abizenak = $validated['abizenak'] ?? '';
        $bezeroa->telefonoa = $validated['telefonoa'] ?? null;
        $bezeroa->deskribapena = $validated['deskribapena'] ?? null;
        $bezeroa->user_id = Auth::id();
        $bezeroa->bisita_kopurua = 1;
        $bezeroa->azken_bisita = now();
        $bezeroa->save();

        return response()->json($bezeroa, 201);
    }

    public function update(Request $request, $id)
    {
        $bezeroa = Bezeroa::findOrFail($id);
        
        $validated = $request->validate([
            'izena' => 'sometimes|string|max:255',
            'abizenak' => 'nullable|string|max:255',
            'telefonoa' => 'nullable|string',
            'deskribapena' => 'nullable|string',
        ]);

        $bezeroa->update($validated);
        return response()->json($bezeroa);
    }

    public function destroy($id)
    {
        try {
            $bezeroa = Bezeroa::findOrFail($id);
            $bezeroa->delete();
            return response()->json(['message' => 'Bezeroa ezabatu da'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errorea ezabatzean'], 500);
        }
    }
}