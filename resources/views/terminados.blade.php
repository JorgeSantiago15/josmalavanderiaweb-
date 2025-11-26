@extends('layouts.app')

@section('titulo')
    Servicios Terminados
@endsection

@section('contenido')
    <h1 class="h3 text-success mb-4"><i class="bi bi-basket"></i> Servicios Terminados (Listos para entrega)</h1>

    @if($notasTerminadas->isEmpty())
        <div class="alert alert-light text-center border py-5">
            <h4 class="text-muted">No hay servicios pendientes de entrega.</h4>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover shadow-sm border">
                <thead class="table-success">
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Fecha Recepción</th>
                        <th>Total a Pagar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notasTerminadas as $nota)
                    <tr>
                        <td class="fw-bold">#{{ $nota->id }}</td>
                        <td>{{ $nota->cliente->nombre }}</td>
                        <td>{{ $nota->cliente->telefono }}</td>
                        <td>{{ $nota->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="fw-bold text-primary fs-5">${{ number_format($nota->total, 2) }}</td>
                        <td>
                            <div class="d-flex">
                                {{-- Botón Azul: COBRAR --}}
                                <form action="{{ route('notas.pagar', $nota->id) }}" method="POST" onsubmit="return confirm('¿Confirmas recibir el pago de ${{ $nota->total }}?');">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                                        <i class="bi bi-cash-coin"></i> Cobrar y Entregar
                                    </button>
                                </form>
                                
                                {{-- Botón Gris Sólido: VER DETALLE --}}
                                <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-secondary btn-sm ms-2 text-nowrap">
                                    <i class="bi bi-eye-fill"></i> Ver Detalle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection