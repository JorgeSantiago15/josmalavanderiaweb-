<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('usuarios.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'tipo' => 'required|in:empleada,gerente',
            'turno_asignado' => 'required|in:matutino,vespertino',
            'rfc' => 'nullable|string|max:20',
            'telefonoReferencia' => 'nullable|string|max:20',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password), // Para el sistema (Login)
            'clave_visible' => $request->password,        // Para el Gerente (Texto plano)
            'tipo' => $request->tipo,
            'turno_asignado' => $request->turno_asignado,
            'rfc' => $request->rfc,
            'telefonoReferencia' => $request->telefonoReferencia,
            'estatus' => 'activo',
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => ['required', Rule::unique('users')->ignore($user->id)],
            'tipo' => 'required|in:empleada,gerente',
            'turno_asignado' => 'required|in:matutino,vespertino',
            'rfc' => 'nullable|string|max:20',
            'telefonoReferencia' => 'nullable|string|max:20',
        ]);

        $user->nombre = $request->nombre;
        $user->usuario = $request->usuario;
        $user->tipo = $request->tipo;
        $user->turno_asignado = $request->turno_asignado;
        $user->rfc = $request->rfc;
        $user->telefonoReferencia = $request->telefonoReferencia;

        // Si escribieron una nueva contraseña, actualizamos ambas columnas
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->clave_visible = $request->password;
        }

        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Perfil actualizado.');
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return back()->withErrors('No puedes eliminar tu propia cuenta mientras estás logueado.');
        }

        $user = User::findOrFail($id);
        try {
            $user->delete();
            return back()->with('success', 'Usuario eliminado del sistema.');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar porque tiene registros asociados.');
        }
    }
}