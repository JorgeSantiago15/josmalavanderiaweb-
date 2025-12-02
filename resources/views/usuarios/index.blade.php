@extends('layouts.app')

@section('titulo')
    Gestión de Personal
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-0"><i class="bi bi-people-fill"></i> Equipo de Trabajo</h2>
            <p class="text-muted mb-0">Administra los accesos al sistema.</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
            <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Usuario (Login)</th>
                            <th>Rol</th>
                            <th>Turno</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->nombre }}</td>
                            <td class="font-monospace text-primary bg-light px-2 rounded d-inline-block mt-1">{{ $user->usuario }}</td>
                            <td>
                                @if($user->tipo == 'gerente') 
                                    <span class="badge bg-dark border border-secondary"><i class="bi bi-shield-lock"></i> Gerencia</span>
                                @else 
                                    <span class="badge bg-secondary"><i class="bi bi-person-badge"></i> Empleada</span> 
                                @endif
                            </td>
                            <td>{{ ucfirst($user->turno_asignado) }}</td>
                            <td><span class="badge bg-success bg-opacity-75">Activo</span></td>
                            
                            {{-- COLUMNA DE ACCIONES ESTILIZADA --}}
                            <td class="text-end pe-4" style="width: 240px;">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-primary btn-sm flex-fill shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    {{-- Botón Eliminar (Con lógica de seguridad) --}}
                                    @if(Auth::id() != $user->id)
                                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('¿Eliminar acceso a {{ $user->nombre }}? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100 shadow-sm">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
                                        {{-- Espacio vacío para mantener alineación si es el mismo usuario --}}
                                        <div class="flex-fill text-center text-muted small py-1">
                                            <i class="bi bi-person-check"></i> Tú
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREAR USUARIO --}}
<div class="modal fade" id="modalCrearUsuario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Nuevo Colaborador</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('usuarios.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label fw-bold">Nombre Completo</label>
                  <input type="text" name="nombre" class="form-control" required placeholder="Ej. Ana García">
              </div>
              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">Usuario (Login)</label>
                      <input type="text" name="usuario" class="form-control" required placeholder="Ej. ana_g">
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">Contraseña</label>
                      <input type="password" name="password" class="form-control" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">Rol</label>
                      <select name="tipo" class="form-select">
                          <option value="empleada">Empleada</option>
                          <option value="gerente">Gerente</option>
                      </select>
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">Turno</label>
                      <select name="turno_asignado" class="form-select">
                          <option value="matutino">Matutino</option>
                          <option value="vespertino">Vespertino</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary px-4">Crear Cuenta</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection