<?php
namespace App\Http\Controllers;

use App\Models\Hitzordua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HitzorduaController extends Controller {
    public function index() {
        return response()->json(Hitzordua::orderBy('data', 'asc')->orderBy('ordua', 'asc')->get());
    }

    public function store(Request $request) {
        $request->validate(['data' => 'required', 'ordua' => 'required']);
        $id = $request->bezero_id;

        if (!$id && $request->izena) {
            $u = User::create([
                'name' => $request->izena,
                'email' => Str::slug($request->izena).rand(10,99)."@ilea.app",
                'password' => Hash::make('12345678')
            ]);
            $id = $u->id;
        }

        return Hitzordua::create(['data'=>$request->data, 'ordua'=>$request->ordua, 'bezero_id'=>$id]);
    }

    public function update(Request $request, $id) {
        $h = Hitzordua::findOrFail($id);
        $h->update($request->only(['data', 'ordua', 'bezero_id', 'finalizatuta']));
        return $h;
    }

    public function destroy($id) {
        Hitzordua::findOrFail($id)->delete();
        return response()->json(['msg' => 'ok']);
    }
}