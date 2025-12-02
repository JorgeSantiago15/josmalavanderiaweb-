<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\NotaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Traer notas activas
        $notasEnProceso = Nota::where('estado', 'en_proceso')
                              ->with('cliente')
                              ->latest()
                              ->get();
        
        // 2. Traer servicios básicos para el menú desplegable del modal
        // Solo traemos los de tipo 'servicio' para no llenar la lista de jabones
        $servicios = Producto::where('tipo', 'servicio')->get();

        return view('dashboard', compact('notasEnProceso', 'servicios'));
    }

    public function store(Request $request)
    {
        // Validar los datos básicos
        $request->validate([
            'telefono' => 'required|string|max:15',
            'nombre' => 'required|string|max:150',
        ]);

        // Usamos una "Transacción" para asegurar que se guarde todo o nada
        DB::transaction(function () use ($request) {
            
            // 1. BUSCAR O CREAR CLIENTE
            // Si el teléfono ya existe, actualiza el nombre. Si no, crea uno nuevo.
            $cliente = Cliente::updateOrCreate(
                ['telefono' => $request->telefono],
                ['nombre' => $request->nombre]
            );

            // 2. CREAR LA NOTA (El Ticket)
            $nota = Nota::create([
                'cliente_id' => $cliente->id,
                'usuario_id' => Auth::id(), // La empleada logueada
                'estado' => 'en_proceso',
                'especificaciones' => $request->especificaciones,
                'fecha_recepcion' => now(),
            ]);

            // 3. (Opcional) AGREGAR EL PRIMER SERVICIO SI LO SELECCIONÓ
            if ($request->servicio_inicial_id) {
                $producto = Producto::find($request->servicio_inicial_id);
                
                if($producto) {
                    NotaItem::create([
                        'nota_id' => $nota->id,
                        'producto_id' => $producto->id,
                        'cantidad' => 1, // Por defecto 1, luego podrá editarlo
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $producto->precio * 1,
                    ]);
                    
                    // Actualizar el total de la nota
                    $nota->total = $producto->precio;
                    $nota->save();
                }
            }


            
        });



        return redirect()->route('dashboard')->with('success', '¡Servicio registrado correctamente!');
    }
    // 1. MOSTRAR EL DETALLE (Ver la cuenta)
    public function show($id)
    {
        // Buscamos la nota con sus items y el producto de cada item
        $nota = Nota::with(['cliente', 'items.producto'])->findOrFail($id);
        
        // También mandamos la lista de productos para poder agregar más cosas
        $productos = Producto::all();

        return view('notas.show', compact('nota', 'productos'));
    }

    // 2. AGREGAR UN PRODUCTO A LA CUENTA
    public function agregarItem(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:0.1', // Decimales permitidos
        ]);

        $nota = Nota::findOrFail($id);
        $producto = Producto::find($request->producto_id);

        // Calculamos el subtotal de este item
        $subtotal = $request->cantidad * $producto->precio;

        // Guardamos el item en la base de datos
        NotaItem::create([
            'nota_id' => $nota->id,
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $producto->precio, // Precio congelado al momento
            'subtotal' => $subtotal,
        ]);

        // ACTUALIZAR EL TOTAL DE LA NOTA PRINCIPAL
        // Sumamos todos los subtotales de los items de esta nota
        $nuevoTotal = $nota->items()->sum('subtotal');
        $nota->total = $nuevoTotal;
        $nota->save();

        return back()->with('success', 'Producto agregado correctamente');
    }
    // 3. CAMBIAR ESTADO A "TERMINADO"
    public function terminar($id)
    {
        $nota = Nota::findOrFail($id);

        // Validamos que tenga al menos un item antes de terminar 
        if ($nota->items()->count() == 0) {
            return back()->withErrors('No puedes terminar una nota vacía. Agrega servicios primero.');
        }

        $nota->estado = 'terminado';
        $nota->save();

        return redirect()->route('dashboard')->with('success', '¡Servicio marcado como TERMINADO! Pasó a la bandeja de entrega.');
    }

    // 4. VISTA DE "TERMINADOS" (La bandeja de salida)
    public function indexTerminados()
    {
        // Traemos solo las notas que ya están listas
        $notasTerminadas = Nota::where('estado', 'terminado')
                               ->with('cliente')
                               ->latest() // Las más recientes primero
                               ->get();

        return view('terminados', compact('notasTerminadas'));
    }
    // 5. COBRAR Y ENTREGAR (Cierra el ciclo)
   public function pagar($id)
{
    $nota = Nota::findOrFail($id);
    
    // AQUÍ ESTÁ LA CLAVE: Guardamos el momento exacto (now())
    $nota->update([
        'estado' => 'pagado',
        'fecha_pagado' => now(), // <--- ESTO ES LO QUE TE FALTABA O FALLABA
    ]);

    return redirect()->route('pagados')->with('success', 'Servicio cobrado correctamente.');
}

    // 6. VISTA DE PAGADOS (Historial del día)
    public function indexPagados()
    {
        // Traemos SOLO lo que se pagó HOY (para que coincida con el corte del día)
        $notasPagadas = Nota::where('estado', 'pagado')
                            ->whereDate('fecha_pagado', today()) // Filtro de "HOY"
                            ->with('cliente')
                            ->latest('fecha_pagado')
                            ->get();
        
        // Calculamos el total vendido hoy para mostrarlo arriba
        $totalVendidoHoy = $notasPagadas->sum('total');

        return view('pagados', compact('notasPagadas', 'totalVendidoHoy'));
    }
    // ELIMINAR NOTA (Solo si está en proceso)
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);

        if ($nota->estado !== 'en_proceso') {
            return back()->withErrors('No se pueden cancelar notas terminadas o pagadas.');
        }

        // Al borrar la nota, se borran los items automáticamente si configuramos "cascade" en BD,
        // pero por seguridad, Laravel lo maneja bien si borramos primero los items o usamos softDeletes.
        // Por ahora, borrado directo:
        $nota->items()->delete(); 
        $nota->delete();

        return redirect()->route('dashboard')->with('success', 'Servicio cancelado y eliminado.');
    }
}