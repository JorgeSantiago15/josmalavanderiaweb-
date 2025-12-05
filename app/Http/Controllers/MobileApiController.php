<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <--- ESTE FALTABA PARA EL LOG ROJO
use App\Models\CorteCaja; // Asegúrate de tener estos imports también
use App\Models\Mantenimiento;

class MobileApiController extends Controller
{
    // --- LOGIN ---
    public function login(Request $request) {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('usuario', $request->usuario)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        if ($user->tipo !== 'gerente') {
            return response()->json(['message' => 'Solo gerentes pueden acceder a la App'], 403);
        }

        // Crear token simple
        $token = $user->createToken('android-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    // --- GET DATA ---
    public function getMantenimientos() {
        return Mantenimiento::orderBy('fecha_programada', 'asc')->get();
    }

    public function getCortes() {
        return CorteCaja::orderBy('fecha', 'desc')->take(20)->get();
    }

    public function getUsers() {
        return User::orderBy('id', 'desc')->get();
    }

   // --- CREATE USER ---
    public function createUser(Request $request) {
        try {
            // 1. Validamos los datos obligatorios
            $request->validate([
                'nombre' => 'required|string|max:255',
                'usuario' => 'required|string|max:50',
                'password' => 'required|string|min:4',
            ]);

            // 2. Verificar duplicado
            if (User::where('usuario', $request->usuario)->exists()) {
                return response()->json(['message' => 'El usuario ya existe'], 409);
            }

            $user = new User();
            
            // --- CAMPOS OBLIGATORIOS ---
            $user->nombre = $request->nombre;
            $user->usuario = $request->usuario;
            
            // Contraseñas (Encriptada y Visible)
            $user->password = Hash::make($request->password);
            $user->clave_visible = $request->password;
            
            // Datos fijos
            $user->tipo = 'empleada'; // Tal cual lo pide tu sistema
            $user->turno_asignado = 'matutino'; 
            $user->estatus = 'activo';

            // --- CAMPOS OPCIONALES (RFC y Teléfono) ---
            // Si no los mandamos, los ponemos como NULL o vacíos para que no falle
            $user->rfc = null;
            $user->telefonoReferencia = null;
            
          
            
            $user->save();

            return response()->json(['message' => 'Usuario creado', 'user' => $user], 201);

        } catch (\Exception $e) {
            Log::error('Error App CreateUser: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // --- DELETE USER ---
    public function deleteUser($id) {
        try {
            $user = User::findOrFail($id);
            
            // Evitar suicidio digital (borrarse a sí mismo)
            if ($user->id == Auth::id()) {
                return response()->json(['message' => 'No puedes borrarte a ti mismo'], 400);
            }

            $user->delete();
            return response()->json(['message' => 'Usuario eliminado']);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'No se pudo eliminar'], 500);
        }
    }
    // --- LOGOUT (CERRAR SESIÓN) ---
    public function logout(Request $request) {
        // Borra el token actual que está usando el dispositivo
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}