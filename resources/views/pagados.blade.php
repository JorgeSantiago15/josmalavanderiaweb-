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
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Ingreso</th>
                                <th>Salida (Pago)</th>
                                <th>Atendió (Nota)</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($notasPagadas as $nota)
                    <tr>
                         <td>#{{ $nota->id }}</td>
                         <td class="fw-bold">{{ $nota->cliente->nombre }}</td>
                         <td>{{ \Carbon\Carbon::parse($nota->fecha_recepcion)->format('d/m/Y') }}</td>
                         <td class="text-end fw-bold fs-5">${{ number_format($nota->total, 2) }}</td>
                         <td class="text-center">
                                {{-- Botón amarillo pequeño --}}
                       <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-warning btn-sm text-dark fw-bold" title="Reimprimir Ticket">
                     <i class="bi bi-printer-fill"></i> Ticket
                      </a>
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