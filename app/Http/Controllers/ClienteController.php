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
    // En ClienteController.php

public function update(Request $request, string $id)
{
    // Validamos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'telefono' => 'required|string|max:20', // Ojo: validar que no se repita con otro cliente
    ]);

    // Buscamos y actualizamos
    $cliente = Cliente::findOrFail($id);
    $cliente->update($validated);

    // Regresamos con mensaje de éxito
    return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
}
}