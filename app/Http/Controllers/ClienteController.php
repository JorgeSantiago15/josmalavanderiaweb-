<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        // Traemos clientes y contamos sus visitas (notas)
        // Ordenamos por última actualización (los que han venido recientemente primero)
        $clientes = Cliente::withCount('notas')
                           ->orderBy('updated_at', 'desc')
                           ->paginate(20); // Paginación de 20 en 20

        return view('clientes.index', compact('clientes'));
    }
}