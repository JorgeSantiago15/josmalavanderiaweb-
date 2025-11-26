@extends('layouts.app')

@section('titulo')
    Detalle de Corte #{{ $corte->id }}
@endsection

@section('contenido')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Reporte de Cierre #{{ $corte->id }}</h2>
        <a href="{{ route('corte.historial') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row">
        {{-- Tarjeta de Información General --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-info-circle"></i> Datos del Turno
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Fecha:</span>
                            <strong>{{ \Carbon\Carbon::parse($corte->fecha)->format('d \d\e F, Y') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Responsable:</span>
                            <strong>{{ $corte->usuario->nombre }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Turno:</span>
                            <strong>{{ ucfirst($corte->turno) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Hora de Cierre:</span>
                            <span>{{ $corte->created_at->format('h:i A') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Tarjeta de Dinero --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-success">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-cash-stack"></i> Balance Financiero
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <small class="text-muted">Ventas Totales</small>
                            <h4 class="text-primary">${{ number_format($corte->total_ventas_calculado, 2) }}</h4>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Reportado en Caja</small>
                            <h4 class="text-dark">${{ number_format($corte->total_general_reportado, 2) }}</h4>
                        </div>
                    </div>
                    
                    <div class="alert @if($corte->diferencia < 0) alert-danger @else alert-success @endif text-center mb-0">
                        <strong>Diferencia: </strong> 
                        <span class="fs-5">${{ number_format($corte->diferencia, 2) }}</span>
                    </div>

                    <hr>
                    <small class="text-muted">Desglose del dinero reportado:</small>
                    <div class="d-flex justify-content-between">
                        <span>Efectivo: ${{ number_format($corte->total_efectivo_reportado, 2) }}</span>
                        <span>Transf: ${{ number_format($corte->total_transferencia_reportado, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Desglose de Servicios --}}
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">
            <i class="bi bi-list-check"></i> Desglose Operativo
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <div class="p-3 bg-light rounded border">
                        <h3 class="mb-0">{{ $corte->total_servicios_lavadora }}</h3>
                        <small class="text-muted">Lavadoras</small>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="p-3 bg-light rounded border">
                        <h3 class="mb-0">{{ $corte->total_servicios_secadora }}</h3>
                        <small class="text-muted">Secadoras</small>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="p-3 bg-light rounded border border-warning">
                        <h3 class="mb-0">{{ $corte->total_servicios_doblado }}</h3>
                        <small class="text-muted">Servicios Doblado</small>
                        <div class="mt-2 text-warning fw-bold">
                            Comisión Pagada: ${{ number_format($corte->total_comisiones_pagadas, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-end mt-4">
        <button onclick="window.print()" class="btn btn-dark">
            <i class="bi bi-printer"></i> Imprimir Reporte
        </button>
    </div>
</div>
@endsection