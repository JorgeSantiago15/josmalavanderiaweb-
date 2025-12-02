@extends('layouts.app')

@section('titulo')
    Corte de Caja
@endsection

@section('contenido')

<div class="container pb-5">
    
    {{-- Encabezado con Turno Dinámico --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-0"><i class="bi bi-calculator"></i> Corte de Caja</h2>
            <p class="text-muted mb-0">Usuario: {{ $user->nombre }}</p>
        </div>
        
        {{-- Badge del Turno Detectado --}}
        @if(isset($turnoActual))
            <div class="text-end">
                <small class="text-muted d-block">Turno Detectado:</small>
                @if($turnoActual == 'matutino')
                    <span class="badge bg-warning text-dark border"><i class="bi bi-sun-fill"></i> MATUTINO</span>
                @elseif($turnoActual == 'vespertino')
                    <span class="badge bg-primary border"><i class="bi bi-moon-stars-fill"></i> VESPERTINO</span>
                @else
                    <span class="badge bg-dark border"><i class="bi bi-clock-history"></i> EXTRAORDINARIO</span>
                @endif
            </div>
        @endif
    </div>

    {{-- SI YA SE HIZO EL CORTE (PANTALLA DE ÉXITO) --}}
    @if($corteExistente)
        <div class="alert alert-success text-center p-5 shadow-sm">
            <h1 class="display-4 text-success"><i class="bi bi-check-circle-fill"></i></h1>
            <h3 class="fw-bold">¡Corte de Turno Finalizado!</h3>
            <p class="fs-5">El reporte de este turno ha sido guardado correctamente.</p>
            <hr>
            
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Reportado:</span>
                            <strong>${{ number_format($corteExistente->total_general_reportado, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Estado de Caja:</span>
                            @if($corteExistente->diferencia == 0)
                                <span class="text-success fw-bold">Cuadrada (Exacta)</span>
                            @elseif($corteExistente->diferencia > 0)
                                <span class="text-success fw-bold">Sobra ${{ $corteExistente->diferencia }}</span>
                            @else
                                <span class="text-danger fw-bold">Falta ${{ $corteExistente->diferencia }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <button onclick="window.print()" class="btn btn-outline-dark">
                    <i class="bi bi-printer"></i> Imprimir Comprobante
                </button>
                
                {{-- BOTÓN CLAVE: Salir para dar paso al siguiente turno --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión y Entregar Turno
                    </button>
                </form>
            </div>
        </div>
    
    {{-- SI AÚN NO SE HACE EL CORTE (FORMULARIO) --}}
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
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-water text-primary"></i> Lavadoras</span>
                            <span class="badge bg-primary rounded-pill">{{ $cantLavadoras }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-wind text-warning"></i> Secadoras</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $cantSecadoras }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-layers-fill text-success"></i> Servicios Doblado</span>
                            <span class="badge bg-success rounded-pill">{{ $cantDoblado }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Transferencias Registradas
                            <span>(Según reporte manual)</span>
                        </li>
                    </ul>
                    
                    <div class="alert alert-info mt-2 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Comisión Empleada:</span>
                            <strong>${{ number_format($comisionDoblado, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="bg-light p-2 rounded border mt-2 text-center">
                        <small class="text-muted">Fondo sugerido para siguiente turno:</small><br>
                        <strong>$200.00</strong>
                    </div>
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