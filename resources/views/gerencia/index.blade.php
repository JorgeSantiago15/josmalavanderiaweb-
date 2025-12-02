@extends('layouts.app')

@section('titulo')
    Panel de Gerencia
@endsection

@section('contenido')
<div class="container pb-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="text-primary fw-bold mb-0"><i class="bi bi-building-gear me-2"></i> Administración General</h2>
            <p class="text-muted mt-1">Panel de control y configuración del negocio.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                <i class="bi bi-calendar-event"></i> Hoy: {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4"> {{-- g-4 da un espaciado uniforme entre tarjetas --}}
        
        {{-- Opción 1: Historial de Cortes --}}
        <div class="col-md-4">
            <div class="card h-100 shadow border-0 card-hover border-top border-4 border-success">
                <div class="card-body text-center p-5">
                    {{-- Burbuja de icono --}}
                    <div class="icon-circle bg-success bg-opacity-10 text-success mx-auto mb-4">
                        <i class="bi bi-cash-coin display-4"></i>
                    </div>
                    
                    <h4 class="card-title fw-bold text-dark">Cortes de Caja</h4>
                    <p class="card-text text-muted mb-4">Historial financiero, reportes de cierre y diferencias de efectivo.</p>
                    
                    <a href="{{ route('corte.historial') }}" class="btn btn-outline-success fw-bold stretched-link px-4 rounded-pill">
                        Ver Reportes <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Opción 2: Gestión de Precios --}}
        <div class="col-md-4">
            <div class="card h-100 shadow border-0 card-hover border-top border-4 border-primary">
                <div class="card-body text-center p-5">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary mx-auto mb-4">
                        <i class="bi bi-tags-fill display-4"></i>
                    </div>
                    
                    <h4 class="card-title fw-bold text-dark">Precios y Productos</h4>
                    <p class="card-text text-muted mb-4">Configuración del catálogo de servicios y ajuste de tarifas.</p>
                    
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-primary fw-bold stretched-link px-4 rounded-pill">
                        Gestionar Catálogo <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Opción 3: Usuarios --}}
        <div class="col-md-4">
            <div class="card h-100 shadow border-0 card-hover border-top border-4 border-dark">
                <div class="card-body text-center p-5">
                    <div class="icon-circle bg-dark bg-opacity-10 text-dark mx-auto mb-4">
                        <i class="bi bi-people-fill display-4"></i>
                    </div>
                    
                    <h4 class="card-title fw-bold text-dark">Personal</h4>
                    <p class="card-text text-muted mb-4">Control de acceso, alta de empleados y asignación de turnos.</p>
                    
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-dark fw-bold stretched-link px-4 rounded-pill">
                        Administrar Usuarios <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efecto de elevación suave */
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-10px); /* Sube un poco más que antes */
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important; /* Sombra difusa elegante */
    }

    /* Círculo para los iconos */
    .icon-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    
    /* Pequeña animación del icono al pasar el mouse por la tarjeta */
    .card-hover:hover .icon-circle {
        transform: scale(1.1) rotate(5deg);
    }
</style>
@endsection