<?php

namespace App\Http\Controllers;

use App\Models\Erabiltzailea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ErabiltzaileaController extends Controller
{
    public function index()
    {
        return response()->json(Erabiltzailea::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'izena' => 'required|string',
            'email' => 'required|email|unique:erabiltzaileak,email',
            'password' => 'required|string|min:6',
            'rola' => 'in:admin,harrera,ikasle',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $erabiltzailea = Erabiltzailea::create($validated);
        return response()->json($erabiltzailea, 201);
    }

    public function show(Erabiltzailea $erabiltzailea)
    {
        return response()->json($erabiltzailea);
    }

    public function update(Request $request, Erabiltzailea $erabiltzailea)
    {
        $validated = $request->validate([
            'izena' => 'sometimes|string',
            'email' => 'sometimes|email|unique:erabiltzaileak,email,' . $erabiltzailea->id,
            'password' => 'sometimes|string|min:6',
            'rola' => 'sometimes|in:admin,harrera,ikasle',
        ]);
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        $erabiltzailea->update($validated);
        return response()->json($erabiltzailea);
    }

    public function destroy(Erabiltzailea $erabiltzailea)
    {
        $erabiltzailea->delete();
        return response()->json(null, 204);
    }
}