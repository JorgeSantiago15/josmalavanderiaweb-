@extends('layouts.app')

@section('titulo')
    Directorio de Clientes
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="bi bi-people-fill"></i> Clientes Registrados</h2>
        {{-- Contador visual --}}
        <span class="badge bg-primary fs-6">{{ $clientes->total() }} Clientes en total</span>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Teléfono</th>
                            <th class="text-center">Visitas</th>
                            <th>Última Visita</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $cliente->nombre }}</td>
                            <td>
                                <a href="tel:{{ $cliente->telefono }}" class="text-decoration-none fw-bold text-secondary">
                                    <i class="bi bi-telephone-fill small me-1"></i>{{ $cliente->telefono }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if($cliente->notas_count > 5)
                                    <span class="badge bg-success rounded-pill" title="Cliente Frecuente">{{ $cliente->notas_count }}</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ $cliente->notas_count }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $cliente->updated_at->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-end pe-4">
                                {{-- BOTÓN EDITAR/DETALLE --}}
                                <button type="button" class="btn btn-primary btn-editar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarCliente"
                                        data-id="{{ $cliente->id }}"
                                        data-nombre="{{ $cliente->nombre }}"
                                        data-telefono="{{ $cliente->telefono }}">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 bg-light border-top">
                {{ $clientes->links() }} 
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDITAR CLIENTE --}}
<div class="modal fade" id="modalEditarCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarCliente" action="" method="POST">
                @csrf
                @method('PUT') {{-- Importante para actualizaciones en Laravel --}}
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Cliente</label>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono (ID Único)</label>
                        <input type="text" name="telefono" id="editTelefono" class="form-control" required>
                        <div class="form-text text-danger">
                            <i class="bi bi-exclamation-circle"></i> Cuidado: Al cambiar el teléfono, cambiará su forma de identificación.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT PARA LLENAR EL MODAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnsEditar = document.querySelectorAll('.btn-editar');
        
        btnsEditar.forEach(btn => {
            btn.addEventListener('click', function() {
                // 1. Obtener datos del botón
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const telefono = this.getAttribute('data-telefono');
                
                // 2. Llenar el formulario
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editTelefono').value = telefono;
                
                // 3. Actualizar la acción del formulario (Ruta)
                // Usamos una ruta base y reemplazamos el placeholder
                // Asumiendo que tu ruta sea 'clientes.update'
                let url = "{{ url('clientes') }}/" + id; 
                document.getElementById('formEditarCliente').action = url;
            });
        });
    });
</script>
@endsection