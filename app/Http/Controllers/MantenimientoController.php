<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MantenimientoController extends Controller
{
    public function index()
    {
        // ORDENAMIENTO COMPLEJO:
        // 1. Urgentes pendientes primero.
        // 2. Preventivos por fecha de vencimiento más cercana (ascendente).
        $mantenimientos = Mantenimiento::where('estado', 'pendiente')
            ->orderByRaw("FIELD(tipo, 'urgente', 'preventivo')") // Urgentes arriba
            ->orderBy('fecha_programada', 'asc') // Fechas cercanas primero
            ->get();

        return view('mantenimientos.index', compact('mantenimientos'));
    }

    // REPORTAR URGENCIA (Empleadas)
    public function storeUrgente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100', // Ej: Lavadora 3 falla
            'descripcion' => 'required|string',
            'categoria' => 'required|in:infraestructura,maquinaria',
        ]);

        Mantenimiento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'tipo' => 'urgente',
            'fecha_programada' => Carbon::now(), // Es para HOY
            'usuario_id' => Auth::id(),
            'estado' => 'pendiente'
        ]);

        return back()->with('success', 'Reporte de urgencia creado. Aparecerá en rojo para atención inmediata.');
    }

    // MOSTRAR DETALLE (Para ver las instrucciones técnicas)
    public function show($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        return response()->json($mantenimiento); // Lo usaremos con AJAX/Modal para rápido acceso
    }

    // COMPLETAR / ATENDER
   public function completar(Request $request, $id)
    {
        // Validamos que nos envíen la próxima fecha (si es preventivo)
        $request->validate([
            'proxima_fecha' => 'nullable|date',
        ]);

        $item = Mantenimiento::findOrFail($id);
        
        // 1. Si es URGENTE: Se cierra y listo.
        if ($item->tipo == 'urgente') {
            $item->estado = 'realizado';
            $item->fecha_realizada = Carbon::now();
            $item->save();
            return back()->with('success', "Urgencia resuelta y archivada.");
        } 
        
        // 2. Si es PREVENTIVO: Se reprograma con TU fecha elegida
        else {
            $item->fecha_realizada = Carbon::now(); // Queda registrado que se hizo hoy
            
            // Aquí usamos la fecha que tú elegiste en el calendario
            if ($request->proxima_fecha) {
                $item->fecha_programada = $request->proxima_fecha;
            } else {
                // Respaldo por si acaso: sumar los días automáticamente
                $item->fecha_programada = Carbon::now()->addDays($item->frecuencia_dias);
            }

            $item->save();
            
            return back()->with('success', "Mantenimiento registrado. Próxima revisión programada para el: " . Carbon::parse($item->fecha_programada)->format('d/m/Y'));
        }
    }
}