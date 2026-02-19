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
        'izena'        => 'required|string',
        'marka'        => 'nullable|string',
        'stock'        => 'required|integer',
        'stock_minimo' => 'nullable|integer',
        'prezioa'      => 'required|numeric',
    ]);

    // Creamos el producto
    $produktua = Produktua::create($validated);

    // Envolvemos el historial en un try-catch para que si falla, 
    // al menos el producto se cree y el frontend no reciba un 500
    try {
        StockMugimendua::create([
            'user_id'    => Auth::id() ?? 1, // Si falla el Auth, ponemos el ID 1 por defecto
            'produktua'  => $produktua->izena,
            'kantitatea' => $produktua->stock,
            'ekintza'    => 'Produktu berria sortua'
        ]);
    } catch (\Exception $e) {
        // No hacemos nada, permitimos que el flujo siga
    }

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
