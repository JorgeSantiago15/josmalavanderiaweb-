@extends('layouts.app')

@section('titulo')
    Catálogo de Precios
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="bi bi-tags"></i> Gestión de Productos y Precios</h2>
        
        {{-- Botón para abrir modal de crear --}}
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearProducto">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Precio Actual</th>
                        <th>Categoría Reporte</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $prod)
                    <tr>
                        <td class="fw-bold">{{ $prod->nombre }}</td>
                        <td>
                            @if($prod->tipo == 'servicio') <span class="badge bg-info text-dark">Servicio</span>
                            @elseif($prod->tipo == 'producto') <span class="badge bg-secondary">Producto</span>
                            @else <span class="badge bg-warning text-dark">Descuento</span> @endif
                        </td>
                        <td class="fs-5 text-success fw-bold">${{ number_format($prod->precio, 2) }}</td>
                        <td class="text-muted small">
                            {{ $prod->reporte_categoria ? strtoupper($prod->reporte_categoria) : '-' }}
                        </td>
                        <td class="text-end">
                            {{-- Botón Editar --}}
                            <a href="{{ route('productos.edit', $prod->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            
                            {{-- Formulario Borrar --}}
                            <form action="{{ route('productos.destroy', $prod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar {{ $prod->nombre }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR PRODUCTO --}}
<div class="modal fade" id="modalCrearProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Registrar Nuevo Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('productos.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Nombre del Producto/Servicio</label>
                  <input type="text" name="nombre" class="form-control" required placeholder="Ej. Jabón Premium 1L">
              </div>
              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label">Precio ($)</label>
                      <input type="number" step="0.01" name="precio" class="form-control" required>
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label">Tipo</label>
                      <select name="tipo" class="form-select" required>
                          <option value="producto">Producto (Insumo)</option>
                          <option value="servicio">Servicio (Mano obra)</option>
                          <option value="descuento">Descuento</option>
                      </select>
                  </div>
              </div>
              <div class="mb-3">
                 <label class="form-label">Categoría Especial (Opcional)</label>
                 <select name="reporte_categoria" class="form-select">
                     <option value="">-- Ninguna --</option>
                     <option value="lavadora">Cuenta como Lavadora</option>
                     <option value="secadora">Cuenta como Secadora</option>
                     <option value="doblado">Cuenta como Doblado (Comisión)</option>
                 </select>
                 <div class="form-text small">Solo selecciona esto si el item debe sumar en el reporte de cortes.</div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection