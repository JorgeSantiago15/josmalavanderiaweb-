<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesa los datos del formulario
    public function login(Request $request)
    {
        // 1. Validamos que los campos no vengan vacíos
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Intentamos autenticar (Laravel compara el password encriptado automáticamente)
        if (Auth::attempt($credentials)) {
            // Si es correcto:
            $request->session()->regenerate(); // Seguridad contra ataques de sesión
            return redirect()->intended('dashboard'); // Redirige al Dashboard
        }

        // 3. Si falla: Regresa al formulario con un error
        return back()->withErrors([
            'usuario' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    }

    // Cierra la sesión
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}