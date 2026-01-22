<?php

namespace App\Http\Controllers; // <--- Begiratu ondo hau, ez duela "/Auth" izan behar

use App\Models\User;
use App\Models\StockMugimendua;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getIkasleak()
    {
        return response()->json(User::all());
    }

    public function getStockHistoriala()
    {
        try {
            return response()->json(StockMugimendua::with('user:id,name')->get());
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function destroyIkaslea($id)
    {
        $user = User::find($id);
        if($user) $user->delete();
        return response()->json(['status' => 'ok']);
    }
}