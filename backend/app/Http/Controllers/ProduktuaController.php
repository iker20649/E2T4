<?php

namespace App\Http\Controllers;

use App\Models\Produktua;
use Illuminate\Http\Request;

class ProduktuaController extends Controller
{
    // Listar productos
    public function index()
    {
        return response()->json(Produktua::all());
    }

    // Crear producto nuevo
    public function store(Request $request)
    {
        $validado = $request->validate([
            'izena' => 'required|string|max:255',
            'marka' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'prezioa' => 'required|numeric|min:0',
        ]);

        $produktu = Produktua::create($validado);

        return response()->json([
            'message' => 'Produktua ondo sortu da!',
            'producto' => $produktu
        ], 201);
    }

    // Actualizar Stock (+ o -)
    public function updateStock(Request $request, $id)
    {
        $producto = Produktua::findOrFail($id);

        if ($request->action === 'add') {
            $producto->increment('stock');
        } elseif ($request->action === 'sub' && $producto->stock > 0) {
            $producto->decrement('stock');
        }

        return response()->json($producto);
    }

    // Borrar producto
    public function destroy($id)
    {
        $producto = Produktua::findOrFail($id);
        $producto->delete();
        return response()->json(['message' => 'Produktua ezabatu da']);
    }
}