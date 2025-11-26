@extends('layouts.app')

@section('titulo')
    Panel de Gerencia
@endsection

@section('contenido')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-primary fw-bold"><i class="bi bi-building-gear"></i> Administración General</h2>
            <p class="text-muted">Bienvenido, Gerente. Seleccione una opción para administrar el negocio.</p>
        </div>
    </div>

    <div class="row">
        {{-- Opción 1: Historial de Cortes --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-5">
                    <div class="display-4 text-success mb-3"><i class="bi bi-cash-coin"></i></div>
                    <h4 class="card-title fw-bold">Cortes de Caja</h4>
                    <p class="card-text text-muted">Ver el historial de ingresos, diferencias y reportes diarios.</p>
                    <a href="{{ route('corte.historial') }}" class="btn btn-outline-success stretched-link">Ver Reportes</a>
                </div>
            </div>
        </div>

        {{-- Opción 2: Gestión de Precios --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-5">
                    <div class="display-4 text-primary mb-3"><i class="bi bi-tags-fill"></i></div>
                    <h4 class="card-title fw-bold">Precios y Productos</h4>
                    <p class="card-text text-muted">Editar precios de lavadoras, productos y servicios.</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-primary stretched-link">Gestionar Catálogo</a>
                </div>
            </div>
        </div>

        {{-- Opción 3: Usuarios --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-5">
                    <div class="display-4 text-dark mb-3"><i class="bi bi-people-fill"></i></div>
                    <h4 class="card-title fw-bold">Personal</h4>
                    <p class="card-text text-muted">Registrar nuevas empleadas o editar perfiles de acceso.</p>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-dark stretched-link">Administrar Usuarios</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card:hover { transform: translateY(-5px); transition: 0.3s; background-color: #f8f9fa; }
</style>
@endsection