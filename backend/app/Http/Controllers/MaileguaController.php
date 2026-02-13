<?php

namespace App\Http\Controllers;

use App\Models\Mailegua;
use App\Models\Produktua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaileguaController extends Controller
{
    /**
     * Mailegu berri bat hasi (Materiala okupatu)
     */
    public function store(Request $request)
    {
        $request->validate([
            'materiala_id' => 'required|exists:produktuak,id',
            'ikasle_ekipoa_id' => 'required|exists:users,id',
        ]);

        // Ziurtatu materiala libre dagoela
        $materiala = Produktua::findOrFail($request->materiala_id);
        
        // Materiala dagoeneko maileguan badago (itzuli gabe)
        $okupatuta = Mailegua::where('materiala_id', $request->materiala_id)
                             ->where('itzulita', false)
                             ->exists();

        if ($okupatuta) {
            return response()->json(['message' => 'Material hau okupatuta dago'], 422);
        }

        $mailegua = Mailegua::create([
            'materiala_id' => $request->materiala_id,
            'ikasle_id'    => $request->ikasle_ekipoa_id,
            'hasiera_data' => now(),
            'itzulita'     => false
        ]);

        return response()->json($mailegua, 201);
    }

    /**
     * Materiala itzuli (Materiala libratu)
     */
    public function update(Request $request, $id)
    {
        $mailegua = Mailegua::findOrFail($id);
        
        $mailegua->update([
            'itzulita'     => true,
            'amaiera_data' => now()
        ]);

        return response()->json(['message' => 'Materiala itzuli da']);
    }
}