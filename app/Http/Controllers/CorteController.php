<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\CorteCaja;
use App\Models\NotaItem;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CorteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hoy = Carbon::today();

        // 1. BUSCAR LO QUE VENDIÓ ESTA EMPLEADA HOY
        $notasDelTurno = Nota::where('usuario_id', $user->id)
                             ->where('estado', 'pagado')
                             ->whereDate('fecha_pagado', $hoy)
                             ->get();

        // 2. CALCULAR TOTALES MONETARIOS
        $totalVentas = $notasDelTurno->sum('total');

        // 3. CONTAR LOS SERVICIOS (Para el reporte y comisiones)
        // Buscamos dentro de los items de esas notas
       // 3. CONTAR LOS SERVICIOS (Lógica mejorada)
        $items = NotaItem::whereIn('nota_id', $notasDelTurno->pluck('id'))->with('producto')->get();

        $cantLavadoras = 0;
        $cantSecadoras = 0;
        $cantDoblado = 0;

        foreach ($items as $item) {
            // Caso A: Es un Servicio Completo (Vale por 3 cosas)
            // Asegúrate que el nombre en la BD sea EXACTAMENTE este, o usa el ID del producto
            if (str_contains($item->producto->nombre, 'Completo')) { 
                $cantLavadoras += $item->cantidad;
                $cantSecadoras += $item->cantidad;
                $cantDoblado   += $item->cantidad; // ¡Aquí sumamos la comisión!
            } 
            // Caso B: Es un servicio individual normal
            else {
                if ($item->producto->reporte_categoria == 'lavadora') $cantLavadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'secadora') $cantSecadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'doblado')  $cantDoblado   += $item->cantidad;
            }
        }

        // 4. CALCULAR COMISIÓN ($5 por cada servicio de doblado)
        $comisionDoblado = $cantDoblado * 5;

        // 5. CALCULAR CUÁNTO DEBERÍA HABER EN CAJA (Ventas - Comisiones que se lleva)
        $totalEsperado = $totalVentas - $comisionDoblado;

        // Verificamos si ya hizo un corte hoy para no dejarla hacer dos
        $corteExistente = CorteCaja::where('usuario_id', $user->id)
                                   ->whereDate('fecha', $hoy)
                                   ->first();

        return view('corte.index', compact(
            'totalVentas', 
            'cantLavadoras', 
            'cantSecadoras', 
            'cantDoblado', 
            'comisionDoblado', 
            'totalEsperado',
            'corteExistente',
            'user',
            'hoy'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fondo_caja' => 'required|numeric',
            'efectivo_reportado' => 'required|numeric',
            'transferencias_reportado' => 'required|numeric',
        ]);

        // Recalculamos todo para asegurar integridad (no confiamos en lo que envíe el front oculto)
        $user = Auth::user();
        $hoy = Carbon::today();
        
        $notasDelTurno = Nota::where('usuario_id', $user->id)
                             ->where('estado', 'pagado')
                             ->whereDate('fecha_pagado', $hoy)
                             ->get();

        $items = NotaItem::whereIn('nota_id', $notasDelTurno->pluck('id'))->with('producto')->get();
        
        $cantLavadoras = $items->where('producto.reporte_categoria', 'lavadora')->sum('cantidad');
        $cantSecadoras = $items->where('producto.reporte_categoria', 'secadora')->sum('cantidad');
        $cantDoblado   = $items->where('producto.reporte_categoria', 'doblado')->sum('cantidad');
        
        $totalVentas = $notasDelTurno->sum('total');
        $comisionPagada = $cantDoblado * 5;
        $totalEsperadoEnCaja = $totalVentas - $comisionPagada; // Ventas puras menos la comisión que se lleva

        // Lo que la empleada dice que tiene (Efectivo + Transferencias)
        $totalReportado = $request->efectivo_reportado + $request->transferencias_reportado;

        // La Diferencia (¿Falta o sobra dinero?)
        // Nota: Comparamos lo reportado contra (Lo esperado + Fondo inicial) si quisiéramos ser estrictos,
        // pero usualmente el fondo se deja intacto. Aquí compararemos Venta vs Reporte.
        $diferencia = $totalReportado - $totalEsperadoEnCaja;

        CorteCaja::create([
            'usuario_id' => $user->id,
            'fecha' => $hoy,
            'turno' => $user->turno_asignado,
            'fondo_caja_inicial' => $request->fondo_caja,
            'total_ventas_calculado' => $totalVentas,
            'total_servicios_lavadora' => $cantLavadoras,
            'total_servicios_secadora' => $cantSecadoras,
            'total_servicios_doblado' => $cantDoblado,
            'total_comisiones_pagadas' => $comisionPagada,
            'total_efectivo_reportado' => $request->efectivo_reportado,
            'total_transferencia_reportado' => $request->transferencias_reportado,
            'total_general_reportado' => $totalReportado,
            'diferencia' => $diferencia
        ]);

        return redirect()->route('corte.index')->with('success', '¡Corte de caja guardado exitosamente!');
    }
    // --- FUNCIONES EXCLUSIVAS DE GERENCIA ---

    // 1. VER LISTA DE TODOS LOS CORTES
    public function historial()
    {
        // Traemos los cortes con la información del usuario que lo hizo
        // Ordenamos por fecha y hora (created_at) descendente
        $cortes = CorteCaja::with('usuario')
                           ->orderBy('fecha', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(15); // Paginación para no saturar la pantalla

        return view('corte.historial', compact('cortes'));
    }

    // 2. VER DETALLE DE UN CORTE PASADO (Read-Only)
    public function show($id)
    {
        $corte = CorteCaja::with('usuario')->findOrFail($id);
        return view('corte.show', compact('corte'));
    }
}