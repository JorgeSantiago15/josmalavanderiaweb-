<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. LISTAR USUARIOS
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('usuarios.index', compact('users'));
    }

    // 2. GUARDAR NUEVO USUARIO
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'required|string|max:50|unique:users', // Usuario único
            'password' => 'required|string|min:6',
            'tipo' => 'required|in:empleada,gerente',
            'turno_asignado' => 'required|in:matutino,vespertino',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password), // ¡Encriptar siempre!
            'tipo' => $request->tipo,
            'turno_asignado' => $request->turno_asignado,
            'estatus' => 'activo',
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    // 3. MOSTRAR EDICIÓN
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.edit', compact('user'));
    }

    // 4. ACTUALIZAR USUARIO
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => ['required', Rule::unique('users')->ignore($user->id)], // Único, ignorando al actual
            'tipo' => 'required|in:empleada,gerente',
            'turno_asignado' => 'required|in:matutino,vespertino',
        ]);

        // Actualizamos datos básicos
        $user->nombre = $request->nombre;
        $user->usuario = $request->usuario;
        $user->tipo = $request->tipo;
        $user->turno_asignado = $request->turno_asignado;

        // Solo cambiamos contraseña si escribieron algo
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Perfil actualizado.');
    }

    // 5. ELIMINAR USUARIO
    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return back()->withErrors('No puedes eliminar tu propia cuenta mientras estás logueado.');
        }

        $user = User::findOrFail($id);
        // Podríamos usar SoftDeletes, pero por ahora borrado simple con validación de integridad
        try {
            $user->delete();
            return back()->with('success', 'Usuario eliminado del sistema.');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar porque tiene ventas o cortes registrados. Mejor cámbialo a inactivo.');
        }
    }
}