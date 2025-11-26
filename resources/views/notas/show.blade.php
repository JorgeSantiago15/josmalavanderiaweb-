@extends('layouts.app')

@section('titulo')
    Detalle de Servicio #{{ $nota->id }}
@endsection

@section('contenido')
<div class="container">
    
    {{-- ENCABEZADO CON NAVEGACIÓN INTELIGENTE --}}
    <div class="d-flex justify-content-between align-items-center mb-4 print-hide">
        <div>
            <h2 class="text-primary mb-0">{{ $nota->cliente->nombre }}</h2>
            <span class="badge @if($nota->estado=='en_proceso') bg-warning text-dark @elseif($nota->estado=='terminado') bg-success @else bg-primary @endif">
                {{ strtoupper(str_replace('_', ' ', $nota->estado)) }}
            </span>
        </div>
        
        {{-- Botón Regresar: Decide a dónde ir según el estado --}}
        <a href="{{ $nota->estado == 'en_proceso' ? route('dashboard') : ($nota->estado == 'terminado' ? route('terminados') : route('pagados')) }}" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Regresar a Lista
        </a>
    </div>

    {{-- ÁREA DEL TICKET (Visible en pantalla y al imprimir) --}}
    <div class="card shadow-sm border-0 mb-4 ticket-area">
        <div class="card-body">
            
            {{-- Encabezado del Ticket (Solo visible al imprimir o muy discreto en pantalla) --}}
            <div class="text-center d-none d-print-block mb-3">
                <h3 class="fw-bold">JOSMA LAVANDERÍA</h3>
                <p class="mb-0">Juarez 751 B, Prados Coyula, Tonalá, Jal.</p>
                <p class="mb-0">Tel: 333-475-24-22</p>
                <p class="small mt-2">Atendió: {{ $nota->usuario_id }}</p> {{-- Idealmente cargar relación usuario --}}
                <hr>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <strong>Cliente:</strong> {{ $nota->cliente->nombre }}<br>
                    <strong>Tel:</strong> {{ $nota->cliente->telefono }}
                </div>
                <div class="col-6 text-end">
                    <strong>Folio:</strong> #{{ $nota->id }}<br>
                    <strong>Ingreso:</strong> {{ $nota->fecha_recepcion }}<br>
                    @if($nota->fecha_pagado)
                        <strong>Salida/Pago:</strong> {{ $nota->fecha_pagado }}
                    @endif
                </div>
            </div>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Cant.</th>
                        <th>Concepto</th>
                        <th class="text-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nota->items as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->cantidad }}</td>
                        <td>{{ $item->producto->nombre }}</td>
                        <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end fw-bold fs-5">TOTAL:</td>
                        <td class="text-end fw-bold fs-5">${{ number_format($nota->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            
            @if($nota->especificaciones)
                <p class="small mt-2"><strong>Nota:</strong> {{ $nota->especificaciones }}</p>
            @endif

            <div class="text-center d-none d-print-block mt-4">
                <p>¡Gracias por su preferencia!</p>
            </div>
        </div>
    </div>

    {{-- ZONA DE ACCIONES (Solo visible si está EN PROCESO) --}}
    @if($nota->estado == 'en_proceso')
        <div class="row print-hide">
            <div class="col-md-8">
                {{-- (Opcional) Aquí podrías poner historial o notas extra --}}
            </div>
            <div class="col-md-4">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h5 class="text-primary">Agregar Productos</h5>
                        <form action="{{ route('notas.agregar', $nota->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <select name="producto_id" class="form-select" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nombre }} (${{ $prod->precio }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 d-flex">
                                <input type="number" step="0.1" name="cantidad" class="form-control me-2" value="1" placeholder="Cant.">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-plus-lg"></i> Agregar producto
                                </button>
                            </div>
                        </form>
                        <hr>
                        
                        {{-- Botón Terminar --}}
                        <form action="{{ route('notas.terminar', $nota->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2" onclick="return confirm('¿Finalizar servicio?')">
                                <i class="bi bi-check-circle"></i> TERMINAR SERVICIO
                            </button>
                        </form>

                        {{-- Botón Cancelar (Eliminar) --}}
                        <form action="{{ route('notas.destroy', $nota->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 btn-sm" onclick="return confirm('¿BORRAR NOTA? Esto no se puede deshacer.')">
                                <i class="bi bi-trash"></i> Cancelar Cuenta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- SI NO ESTÁ EN PROCESO, SOLO MOSTRAMOS BOTÓN DE IMPRIMIR --}}
        <div class="text-end print-hide">
            <button onclick="window.print()" class="btn btn-warning btn-lg shadow">
                <i class="bi bi-printer-fill"></i> IMPRIMIR TICKET
            </button>
        </div>
    @endif

</div>

<style media="print">
    .print-hide { display: none !important; }
    .ticket-area { border: none !important; box-shadow: none !important; }
    body { background: white; font-size: 12px; }
</style>
@endsection