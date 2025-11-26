@extends('layouts.app')

@section('titulo')
    Gestión de Personal
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark"><i class="bi bi-people-fill"></i> Equipo de Trabajo</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
            <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario (Login)</th>
                        <th>Rol</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->nombre }}</td>
                        <td class="font-monospace text-primary">{{ $user->usuario }}</td>
                        <td>
                            @if($user->tipo == 'gerente') 
                                <span class="badge bg-dark">Gerencia</span>
                            @else 
                                <span class="badge bg-secondary">Empleada</span> 
                            @endif
                        </td>
                        <td>{{ ucfirst($user->turno_asignado) }}</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            @if(Auth::id() != $user->id)
                            <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar acceso a {{ $user->nombre }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR USUARIO --}}
<div class="modal fade" id="modalCrearUsuario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Nuevo Colaborador</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('usuarios.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Nombre Completo</label>
                  <input type="text" name="nombre" class="form-control" required placeholder="Ej. Ana García">
              </div>
              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label">Usuario (Login)</label>
                      <input type="text" name="usuario" class="form-control" required placeholder="Ej. ana_g">
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label">Contraseña</label>
                      <input type="password" name="password" class="form-control" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label">Rol</label>
                      <select name="tipo" class="form-select">
                          <option value="empleada">Empleada</option>
                          <option value="gerente">Gerente</option>
                      </select>
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label">Turno</label>
                      <select name="turno_asignado" class="form-select">
                          <option value="matutino">Matutino</option>
                          <option value="vespertino">Vespertino</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Crear Cuenta</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection