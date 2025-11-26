<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    // 1. LISTAR PRODUCTOS
    public function index()
    {
        $productos = Producto::orderBy('nombre', 'asc')->get();
        return view('productos.index', compact('productos'));
    }

    // 2. GUARDAR NUEVO PRODUCTO
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric',
            'tipo' => 'required|in:servicio,producto,descuento',
            // reporte_categoria es opcional, solo para lav/sec/doblado
        ]);

        
        Producto::create($request->except(['_token', '_method']));

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    // 3. MOSTRAR FORMULARIO DE EDICIÓN
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    // 4. ACTUALIZAR PRODUCTO
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric',
            'tipo' => 'required|in:servicio,producto,descuento',
        ]);

        $producto = Producto::findOrFail($id);
        // Guardamos todo MENOS el token y el método interno
       $producto->update($request->except(['_token', '_method']));

        return redirect()->route('productos.index')->with('success', 'Precio actualizado correctamente.');
    }

    // 5. ELIMINAR PRODUCTO (Opcional, con cuidado)
    public function destroy($id)
    {
        // Solo permitimos borrar si no se ha usado en notas (para no romper historiales)
        // Por ahora lo haremos directo, pero idealmente se usaría SoftDeletes
        $producto = Producto::findOrFail($id);
        
        try {
            $producto->delete();
            return back()->with('success', 'Producto eliminado.');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar este producto porque ya está en notas de venta.');
        }
    }
}