@extends('layouts.app')

@section('titulo')
    Gestión de Personal
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-0"><i class="bi bi-people-fill"></i> Equipo de Trabajo</h2>
            <p class="text-muted mb-0">Administra accesos y visualiza credenciales.</p>
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
                            <th class="ps-4">Nombre / Rol</th>
                            <th>Credenciales (Acceso)</th>
                            <th>Contacto / RFC</th>
                            <th>Turno</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $user->nombre }}</div>
                                @if($user->tipo == 'gerente') 
                                    <span class="badge bg-dark border border-secondary"><i class="bi bi-shield-lock"></i> Gerencia</span>
                                @else 
                                    <span class="badge bg-secondary"><i class="bi bi-person-badge"></i> Empleada</span> 
                                @endif
                            </td>
                            
                            {{-- COLUMNA CREDENCIALES (Aquí está lo que pediste) --}}
                            <td>
                                <div class="small text-muted">Usuario:</div>
                                <span class="fw-bold text-primary">{{ $user->usuario }}</span>
                                <div class="small text-muted mt-1">Contraseña:</div>
                                <span class="badge bg-light text-danger border border-danger font-monospace">
                                    {{ $user->clave_visible ?? '****' }}
                                </span>
                            </td>

                            {{-- COLUMNA CONTACTO --}}
                            <td>
                                @if($user->telefonoReferencia)
                                    <div><i class="bi bi-telephone-fill text-muted me-1"></i> {{ $user->telefonoReferencia }}</div>
                                @else
                                    <span class="text-muted small">Sin teléfono</span><br>
                                @endif
                                
                                @if($user->rfc)
                                    <small class="text-muted">RFC: {{ $user->rfc }}</small>
                                @endif
                            </td>

                            <td>{{ ucfirst($user->turno_asignado) }}</td>
                            
                            <td class="text-end pe-4" style="width: 240px;">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-primary btn-sm flex-fill shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    @if(Auth::id() != $user->id)
                                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('¿Eliminar acceso a {{ $user->nombre }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100 shadow-sm">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>
                                    @else
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
              <div class="row">
                  <div class="col-md-12 mb-3">
                      <label class="form-label fw-bold">Nombre Completo</label>
                      <input type="text" name="nombre" class="form-control" required placeholder="Ej. Ana García">
                  </div>
              </div>
              
              <div class="row bg-light p-2 rounded border mb-3 mx-0">
                  <div class="col-12 text-muted small mb-2 fw-bold"><i class="bi bi-key"></i> Datos de Acceso</div>
                  <div class="col-6 mb-2">
                      <label class="form-label small">Usuario</label>
                      <input type="text" name="usuario" class="form-control" required placeholder="Ej. ana_g">
                  </div>
                  <div class="col-6 mb-2">
                      <label class="form-label small">Contraseña</label>
                      <input type="text" name="password" class="form-control" required placeholder="Se verá en lista">
                  </div>
              </div>

              <div class="row">
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">Teléfono</label>
                      <input type="number" name="telefonoReferencia" class="form-control" placeholder="10 dígitos">
                  </div>
                  <div class="col-6 mb-3">
                      <label class="form-label fw-bold">RFC</label>
                      <input type="text" name="rfc" class="form-control" placeholder="Opcional">
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