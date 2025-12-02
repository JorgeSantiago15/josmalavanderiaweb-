@extends('layouts.app')

@section('titulo')
    Servicios Pagados (Hoy)
@endsection

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-dark"><i class="bi bi-wallet2"></i> Pagos del Día ({{ now()->format('d/m/Y') }})</h1>
        <div class="bg-white p-3 rounded shadow-sm border">
            <span class="text-muted small text-uppercase">Total acumulado hoy:</span>
            <div class="fs-3 fw-bold text-success">${{ number_format($totalVendidoHoy, 2) }}</div>
        </div>
    </div>

    @if($notasPagadas->isEmpty())
        <div class="alert alert-light text-center border py-5">
            <h4 class="text-muted">Aún no hay cobros registrados el día de hoy.</h4>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Hora Recepción</th> {{-- Cambiamos Fecha por Hora (más útil) --}}
                                <th>Atendió</th>        {{-- Aquí va el nombre del empleado --}}
                                <th class="text-end">Total</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                       <tbody>
    @foreach($notasPagadas as $nota)
    <tr>
        {{-- 1. Folio --}}
        <td class="fw-bold">#{{ $nota->id }}</td>
        
        {{-- 2. Cliente --}}
        <td>{{ $nota->cliente->nombre }}</td>
        
        {{-- 3. Fecha de Ingreso (Recepción de la ropa) --}}
        <td>
            {{-- Usamos fecha_recepcion con formato Día/Mes/Año --}}
            <span class="text-muted">
                {{ \Carbon\Carbon::parse($nota->fecha_recepcion)->format('d/m/Y') }}
            </span>
        </td>
        
        {{-- 4. Atendió (Nombre de la empleada) --}}
        <td>
            <span class="badge bg-light text-dark border">
                {{-- Ahora sí mostrará el nombre gracias al ajuste en el Modelo --}}
                <i class="bi bi-person"></i> {{ $nota->user->name ?? 'Desconocido' }}
            </span>
        </td>

        {{-- 5. Total --}}
        <td class="text-end fw-bold text-success fs-5">
            ${{ number_format($nota->total, 2) }}
        </td>

        {{-- 6. Botón Ticket --}}
        <td class="text-center">
            <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-warning btn-sm fw-bold" title="Ver detalle">
                <i class="bi bi-printer-fill"></i> Ticket
            </a>
        </td>
    </tr>
    @endforeach
</tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection