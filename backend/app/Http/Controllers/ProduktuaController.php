<?php

namespace App\Http\Controllers;

use App\Models\Produktua;
use App\Models\StockMugimendua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduktuaController extends Controller
{
    /**
     * Produktu guztiak bueltatu (lehen index-ean errorea ematen dizu is_material falta delako)
     */
    public function index()
    {
        // Datu-basean zutabea falta denez, denak bueltatuko ditugu errorea saihesteko
        return Produktua::orderBy('izena', 'asc')->get();
    }

    /**
     * Materialak / Tresneria
     * Datu-basean is_material falta denez, denak bueltatuko ditugu oraingoz
     */
    public function getMaterialak()
    {
        // Kontuz: 'with' horrek errorea eman dezake azken_mailegua erlazioa definituta ez badago
        // Seguruena hau da:
        return response()->json(Produktua::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena'        => 'required|string|unique:produktuak',
            'marka'        => 'nullable|string',
            'stock'        => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'prezioa'      => 'required|numeric|min:0',
            // 'is_material' kendu dugu datu-basean ez dagoelako
        ]);

        $produktua = Produktua::create($validated);

        StockMugimendua::create([
            'user_id'    => Auth::id(),
            'produktua'  => $produktua->izena,
            'kantitatea' => $produktua->stock,
            'ekintza'    => 'Produktu berria sortua (' . $produktua->prezioa . '€)'
        ]);

        return response()->json($produktua, 201);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'kantitatea' => 'required|integer', 
            'ekintza'    => 'required|string'
        ]);

        $produktua = Produktua::findOrFail($id);
        $produktua->stock += $request->kantitatea;
        
        if ($produktua->stock < 0) {
            $produktua->stock = 0;
        }
        
        $produktua->save();

        StockMugimendua::create([
            'user_id'    => Auth::id(),
            'produktua'  => $produktua->izena,
            'kantitatea' => $request->kantitatea,
            'ekintza'    => $request->ekintza
        ]);

        return response()->json($produktua);
    }

    public function destroy($id)
    {
        $produktua = Produktua::findOrFail($id);
        $produktua->delete();
        return response()->json(['message' => 'Produktua ezabatuta']);
    }
}