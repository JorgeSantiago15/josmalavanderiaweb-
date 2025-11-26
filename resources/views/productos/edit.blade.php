@extends('layouts.app')

@section('titulo')
    Editar Producto
@endsection

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    Editar: {{ $producto->nombre }}
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Importante para actualizaciones --}}

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" class="form-control form-control-lg" value="{{ $producto->precio }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select">
                                <option value="servicio" {{ $producto->tipo == 'servicio' ? 'selected' : '' }}>Servicio</option>
                                <option value="producto" {{ $producto->tipo == 'producto' ? 'selected' : '' }}>Producto</option>
                                <option value="descuento" {{ $producto->tipo == 'descuento' ? 'selected' : '' }}>Descuento</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoría Reporte</label>
                            <select name="reporte_categoria" class="form-select">
                                <option value="">-- Ninguna --</option>
                                <option value="lavadora" {{ $producto->reporte_categoria == 'lavadora' ? 'selected' : '' }}>Lavadora</option>
                                <option value="secadora" {{ $producto->reporte_categoria == 'secadora' ? 'selected' : '' }}>Secadora</option>
                                <option value="doblado" {{ $producto->reporte_categoria == 'doblado' ? 'selected' : '' }}>Doblado</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Precio</button>
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection