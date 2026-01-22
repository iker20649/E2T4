<?php

namespace App\Http\Controllers;

use App\Models\Hitzordua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HitzorduaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->rola === 'irakasle') {
            // Irakasleak hitzordu guztiak ikusten ditu, ikaslearen datuekin
            return Hitzordua::with('user:id,name')->orderBy('data', 'asc')->get();
        }

        // Ikasleak bereak bakarrik
        return Hitzordua::where('user_id', $user->id)->orderBy('data', 'asc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bezeroa' => 'required|string|max:255',
            'data' => 'required|date',
            'deskribapena' => 'nullable|string',
        ]);

        $hitzordua = Hitzordua::create([
            'bezeroa' => $validated['bezeroa'],
            'data' => $validated['data'],
            'deskribapena' => $validated['deskribapena'] ?? '',
            'user_id' => Auth::id(), // Saioa hasi duenaren IDa gordetzen da
            'finalizatuta' => false
        ]);

        return response()->json($hitzordua, 201);
    }

    public function destroy($id)
    {
        $hitzordua = Hitzordua::findOrFail($id);
        $hitzordua->delete();
        return response()->json(['message' => 'Hitzordua ezabatuta']);
    }
}