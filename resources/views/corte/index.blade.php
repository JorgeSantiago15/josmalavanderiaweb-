@extends('layouts.app')

@section('titulo')
    Corte de Caja
@endsection

@section('contenido')

<div class="container pb-5">
    <h2 class="mb-4 text-primary"><i class="bi bi-calculator"></i> Corte de Caja: {{ $user->nombre }}</h2>

    @if($corteExistente)
        <div class="alert alert-success text-center p-4">
            <h4><i class="bi bi-check-circle-fill"></i> ¡Corte Realizado!</h4>
            <p class="mb-0">Ya has realizado el cierre de caja el día de hoy.</p>
            <hr>
            <p>Total Reportado: <strong>${{ number_format($corteExistente->total_general_reportado, 2) }}</strong></p>
            <p>Diferencia: 
                <span class="@if($corteExistente->diferencia < 0) text-danger @else text-success @endif fw-bold">
                    ${{ number_format($corteExistente->diferencia, 2) }}
                </span>
            </p>
            <button onclick="window.print()" class="btn btn-outline-dark mt-3"><i class="bi bi-printer"></i> Imprimir Comprobante</button>
        </div>
    @else

    <div class="row">
        {{-- COLUMNA IZQUIERDA: Resumen Automático --}}
        <div class="col-md-6">
            <div class="card border-primary mb-3 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Resumen del Sistema (Calculado)</div>
                <div class="card-body">
                    <h5 class="card-title">Ventas Totales del Turno: ${{ number_format($totalVentas, 2) }}</h5>
                    <hr>
                   <ul class="list-group list-group-flush">
                        {{-- ... tus contadores de lavadora/secadora ... --}}
                        
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Transferencias Registradas
                            {{-- Necesitamos calcular esto en el controlador si quieres que sea automático, 
                                 pero generalmente esto lo reporta la empleada. 
                                 Aquí mostraremos lo reportado al cerrar si ya existe corte --}}
                            <span>(Según reporte)</span>
                        </li>
                    </ul>
                    
                    {{-- Total Comisiones --}}
                    <div class="alert alert-info mt-2 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Comisión Empleada:</span>
                            <strong>${{ number_format($comisionDoblado, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
               {{-- Fondo sugerido --}}
                    <div class="bg-light p-2 rounded border mt-2 text-center">
                        <small class="text-muted">Fondo sugerido para siguiente turno:</small><br>
                        <strong>$200.00</strong> {{-- O la cantidad fija que manejes --}}
                    </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Formulario de Conteo --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Ingreso de Valores Reales</div>
                <div class="card-body">
                    <form action="{{ route('corte.store') }}" method="POST" onsubmit="return confirm('¿Estás segura de cerrar la caja? No podrás editarlo después.');">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Fondo de Caja (Base)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="fondo_caja" class="form-control" required value="0">
                            </div>
                            <div class="form-text">El dinero que se deja para cambio.</div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Efectivo Contado (Billetes y Monedas)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="efectivo_reportado" class="form-control form-control-lg" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-info">Transferencias / Pagos Digitales</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="transferencias_reportado" class="form-control form-control-lg" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock-fill"></i> Cerrar Turno y Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection