<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si está logueado Y si es gerente
        if (Auth::check() && Auth::user()->tipo === 'gerente') {
            return $next($request);
        }

        // Si no es gerente, lo mandamos al dashboard normal con un regaño
        return redirect('/dashboard')->withErrors('Acceso denegado. Área exclusiva para Gerencia.');
    }
}