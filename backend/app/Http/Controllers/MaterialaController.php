<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialaController extends Controller
{
    public function index()
    {
        try {
            $materialas = DB::table('materialas')->get();
            return response()->json($materialas);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
