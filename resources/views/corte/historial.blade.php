@extends('layouts.app')

@section('titulo')
    Historial de Cortes
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="bi bi-journal-text"></i> Historial de Cierres de Caja</h2>
        <a href="{{ route('gerencia.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Turno / Empleada</th>
                        <th>Ventas Totales</th>
                        <th>Reportado (Efectivo+Transf)</th>
                        <th>Diferencia</th>
                        <th class="text-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cortes as $corte)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($corte->fecha)->format('d/m/Y') }}</span><br>
                            <small class="text-muted">{{ $corte->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ ucfirst($corte->turno) }}</span><br>
                            <small>{{ $corte->usuario->nombre }}</small>
                        </td>
                        <td class="text-primary fw-bold">
                            ${{ number_format($corte->total_ventas_calculado, 2) }}
                        </td>
                        <td>
                            ${{ number_format($corte->total_general_reportado, 2) }}
                        </td>
                        <td>
                            @if($corte->diferencia == 0)
                                <span class="badge bg-success"><i class="bi bi-check"></i> Exacto</span>
                            @elseif($corte->diferencia > 0)
                                <span class="text-success fw-bold">+${{ number_format($corte->diferencia, 2) }}</span>
                                <i class="bi bi-arrow-up-circle-fill text-success" title="Sobrante"></i>
                            @else
                                <span class="text-danger fw-bold">${{ number_format($corte->diferencia, 2) }}</span>
                                <i class="bi bi-exclamation-circle-fill text-danger" title="Faltante"></i>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('corte.show', $corte->id) }}" class="btn btn-sm btn-outline-primary">
                                Ver Reporte
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="p-3">
                {{ $cortes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection