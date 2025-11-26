@extends('layouts.app')

@section('titulo')
    Editar Usuario
@endsection

@section('contenido')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    Editar Perfil: {{ $user->nombre }}
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $user->nombre }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usuario (Login)</label>
                            <input type="text" name="usuario" class="form-control" value="{{ $user->usuario }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-danger">Cambiar Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="(Dejar vacío para no cambiar)">
                            <div class="form-text">Solo escribe aquí si quieres restablecer su clave.</div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Rol</label>
                                <select name="tipo" class="form-select">
                                    <option value="empleada" {{ $user->tipo == 'empleada' ? 'selected' : '' }}>Empleada</option>
                                    <option value="gerente" {{ $user->tipo == 'gerente' ? 'selected' : '' }}>Gerente</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Turno</label>
                                <select name="turno_asignado" class="form-select">
                                    <option value="matutino" {{ $user->turno_asignado == 'matutino' ? 'selected' : '' }}>Matutino</option>
                                    <option value="vespertino" {{ $user->turno_asignado == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection