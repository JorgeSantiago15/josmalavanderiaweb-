@extends('layouts.app')

@section('titulo')
    Editar Usuario
@endsection

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8"> {{-- Hice un poco más ancha la tarjeta --}}
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Editar Perfil: {{ $user->nombre }}
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $user->nombre }}" required>
                            </div>
                            
                            {{-- Sección Datos de Contacto Nuevos --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Teléfono de Referencia</label>
                                <input type="number" name="telefonoReferencia" class="form-control" value="{{ $user->telefonoReferencia }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">RFC</label>
                                <input type="text" name="rfc" class="form-control" value="{{ $user->rfc }}">
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-primary fw-bold mb-3"><i class="bi bi-shield-lock"></i> Credenciales de Acceso</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Usuario (Login)</label>
                                <input type="text" name="usuario" class="form-control" value="{{ $user->usuario }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-danger fw-bold">Restablecer Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-key"></i></span>
                                    <input type="text" name="password" class="form-control" placeholder="Escribir nueva clave solo si cambia">
                                </div>
                                <div class="form-text">Si dejas esto vacío, se mantiene la actual: <strong>{{ $user->clave_visible }}</strong></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">Rol</label>
                                <select name="tipo" class="form-select">
                                    <option value="empleada" {{ $user->tipo == 'empleada' ? 'selected' : '' }}>Empleada</option>
                                    <option value="gerente" {{ $user->tipo == 'gerente' ? 'selected' : '' }}>Gerente</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">Turno</label>
                                <select name="turno_asignado" class="form-select">
                                    <option value="matutino" {{ $user->turno_asignado == 'matutino' ? 'selected' : '' }}>Matutino</option>
                                    <option value="vespertino" {{ $user->turno_asignado == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Actualizar Datos</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection