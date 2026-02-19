<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BezeroaController extends Controller
{
    public function index()
    {
        try {
            // Intentamos obtener los clientes de la tabla 'bezeroak' o 'ikasleas'
            // Si la tabla no existe todavia, devolvemos un array vacio para no dar error 500
            $bezeroak = DB::table('ikasleas')->get(); 
            return response()->json($bezeroak);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
