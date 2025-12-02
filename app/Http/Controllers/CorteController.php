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
    // FUNCIÓN PRIVADA: Determina el turno según la hora del reloj
    private function determinarTurnoActual()
    {
        $hora = (int) now()->format('H'); // Obtiene solo la hora (0 a 23)

        // De 8:00 AM a 2:59 PM (14:59)
        if ($hora >= 8 && $hora < 15) {
            return 'matutino';
        }
        // De 3:00 PM (15:00) a 9:59 PM (21:59)
        elseif ($hora >= 15 && $hora < 22) {
            return 'vespertino';
        }
        // Cualquier otra hora (Madrugada o muy noche)
        else {
            return 'extraordinario'; 
        }
    }

    public function index()
    {
        $user = Auth::user();
        $hoy = Carbon::today();
        
        // Calculamos el turno actual basado en la hora real, no en el usuario
        $turnoActual = $this->determinarTurnoActual();

        // 1. BUSCAR VENTAS (Respetando filtro de fecha y usuario)
        $notasDelTurno = Nota::where('usuario_id', $user->id)
                             ->where('estado', 'pagado')
                             ->whereDate('fecha_pagado', $hoy)
                             ->get();

        // 2. TOTALES
        $totalVentas = $notasDelTurno->sum('total');

        // 3. DESGLOSE DE SERVICIOS
        $items = NotaItem::whereIn('nota_id', $notasDelTurno->pluck('id'))->with('producto')->get();

        $cantLavadoras = 0;
        $cantSecadoras = 0;
        $cantDoblado = 0;

        foreach ($items as $item) {
            // Lógica Servicio Completo
            if (str_contains($item->producto->nombre, 'Completo')) { 
                $cantLavadoras += $item->cantidad;
                $cantSecadoras += $item->cantidad;
                $cantDoblado   += $item->cantidad;
            } 
            // Lógica Normal
            else {
                if ($item->producto->reporte_categoria == 'lavadora') $cantLavadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'secadora') $cantSecadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'doblado')  $cantDoblado   += $item->cantidad;
            }
        }

        // 4. COMISIONES
        $comisionDoblado = $cantDoblado * 5;
        $totalEsperado = $totalVentas - $comisionDoblado;

        // 5. VERIFICAR CORTE EXISTENTE
        // Buscamos si ESTE usuario ya hizo corte HOY en ESTE turno (o en general hoy)
        $corteExistente = CorteCaja::where('usuario_id', $user->id)
                                   ->whereDate('fecha', $hoy)
                                   ->first();

        return view('corte.index', compact(
            'totalVentas', 'cantLavadoras', 'cantSecadoras', 'cantDoblado', 
            'comisionDoblado', 'totalEsperado', 'corteExistente', 
            'user', 'hoy', 'turnoActual' // Pasamos el turno a la vista
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fondo_caja' => 'required|numeric',
            'efectivo_reportado' => 'required|numeric',
            'transferencias_reportado' => 'required|numeric',
        ]);

        $user = Auth::user();
        $hoy = Carbon::today();
        
        // Verificar doble candado: No permitir duplicados
        $existe = CorteCaja::where('usuario_id', $user->id)->whereDate('fecha', $hoy)->first();
        if($existe) {
            return back()->withErrors('Ya existe un corte registrado para este usuario el día de hoy.');
        }

        // Recálculo de seguridad (copiado de la lógica anterior)
        $notasDelTurno = Nota::where('usuario_id', $user->id)
                             ->where('estado', 'pagado')
                             ->whereDate('fecha_pagado', $hoy)
                             ->get();

        $items = NotaItem::whereIn('nota_id', $notasDelTurno->pluck('id'))->with('producto')->get();
        
        $cantLavadoras = 0; $cantSecadoras = 0; $cantDoblado = 0;
        foreach ($items as $item) {
            if (str_contains($item->producto->nombre, 'Completo')) { 
                $cantLavadoras += $item->cantidad; $cantSecadoras += $item->cantidad; $cantDoblado += $item->cantidad;
            } else {
                if ($item->producto->reporte_categoria == 'lavadora') $cantLavadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'secadora') $cantSecadoras += $item->cantidad;
                if ($item->producto->reporte_categoria == 'doblado')  $cantDoblado   += $item->cantidad;
            }
        }
        
        $totalVentas = $notasDelTurno->sum('total');
        $comisionPagada = $cantDoblado * 5;
        $totalEsperadoEnCaja = $totalVentas - $comisionPagada;
        $totalReportado = $request->efectivo_reportado + $request->transferencias_reportado;
        $diferencia = $totalReportado - $totalEsperadoEnCaja;

        // GUARDAR CON EL TURNO AUTOMÁTICO
        CorteCaja::create([
            'usuario_id' => $user->id,
            'fecha' => $hoy,
            'turno' => $this->determinarTurnoActual(), // <--- AQUÍ USAMOS LA LÓGICA DE HORARIOS
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

    // ... (Mantén las funciones historial y show iguales) ...
    public function historial() {
        $cortes = CorteCaja::with('usuario')->orderBy('fecha', 'desc')->orderBy('created_at', 'desc')->paginate(15);
        return view('corte.historial', compact('cortes'));
    }
    public function show($id) {
        $corte = CorteCaja::with('usuario')->findOrFail($id);
        return view('corte.show', compact('corte'));
    }
}