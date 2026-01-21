<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Actualiza el perfil del usuario autenticado.
     */
    public function update(Request $request)
    {
        // 1. Obtenemos el usuario que tiene el token activo
        $user = $request->user();

        // 2. Validamos los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'argazkia' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        // 3. Actualizamos nombre y email
        $user->name = $request->name;
        $user->email = $request->email;

        // 4. Si se ha escrito una contraseña, la ciframos
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 5. Gestión de la imagen de perfil
        if ($request->hasFile('argazkia')) {
            // Si el usuario ya tenía una foto, la borramos del disco para no acumular basura
            if ($user->argazkia) {
                // Convertimos la URL guardada (/storage/avatars/...) en ruta de disco (public/avatars/...)
                $oldPath = str_replace('/storage/', 'public/', $user->argazkia);
                Storage::delete($oldPath);
            }
            
            // Guardamos el nuevo archivo en storage/app/public/avatars
            $path = $request->file('argazkia')->store('public/avatars');
            
            // Generamos la URL pública (ej: /storage/avatars/nombre_archivo.jpg)
            $user->argazkia = Storage::url($path); 
        }

        // 6. Guardamos los cambios en la base de datos
        $user->save();

        // 7. Devolvemos el usuario actualizado al Frontend
        return response()->json([
            'message' => 'Profila ondo eguneratu da!',
            'user' => $user
        ]);
    }
}