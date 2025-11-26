@extends('layouts.app')

@section('titulo')
    En Proceso
@endsection

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary">Servicios en Proceso</h1>
        
        {{-- Botón para abrir el modal de Nuevo Servicio --}}
       <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio">
    <i class="bi bi-plus-circle"></i> Nuevo Servicio
</button>
    </div>

    {{-- Lógica: ¿Hay notas o está vacío? --}}
    @if($notasEnProceso->isEmpty())
        
        <div class="alert alert-light text-center border py-5">
            <h4 class="text-muted">No hay servicios activos por ahora.</h4>
            <p>¡Es un buen momento para recibir clientes!</p>
        </div>

    @else

        <div class="row">
            @foreach($notasEnProceso as $nota)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold d-flex justify-content-between">
                            <span>Folio #{{ $nota->id }}</span>
                            <span class="badge bg-warning text-dark">En Proceso</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $nota->cliente->nombre }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-telephone"></i> {{ $nota->cliente->telefono ?? 'Sin teléfono' }}
                            </p>
                            
                            {{-- Aquí mostraremos un resumen rápido --}}
                            <p class="card-text mt-3">
                                <strong>Total estimado:</strong> ${{ number_format($nota->total, 2) }}
                            </p>
                            
                            @if($nota->especificaciones)
                                <div class="alert alert-warning p-2 small mb-0">
                                    <strong>Nota:</strong> {{ Str::limit($nota->especificaciones, 50) }}
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-0">
                           <a href="{{ route('notas.show', $nota->id) }}" class="btn btn-outline-primary btn-sm w-100">Ver / Editar Detalles</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
{{-- MODAL DE NUEVO SERVICIO --}}
    <div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Registrar Nuevo Cliente / Servicio</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <form action="{{ route('notas.store') }}" method="POST">
              @csrf
              <div class="modal-body">
                  
                  {{-- Fila 1: Teléfono (Clave para buscar) --}}
                  <div class="mb-3">
                      <label for="telefono" class="form-label fw-bold">Teléfono del Cliente</label>
                      <input type="number" class="form-control form-control-lg" name="telefono" id="telefono" required placeholder="Ej: 3312345678">
                      <div class="form-text">Si ya existe, actualizaremos su nombre.</div>
                  </div>

                  {{-- Fila 2: Nombre --}}
                  <div class="mb-3">
                      <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
                      <input type="text" class="form-control" name="nombre" id="nombre" required placeholder="Nombre del cliente">
                  </div>

                  <hr>

                  {{-- Fila 3: Servicio Inicial (Opcional) --}}
                  <div class="mb-3">
                      <label for="servicio_inicial_id" class="form-label text-primary fw-bold">¿Agregar servicio inicial?</label>
                      <select class="form-select" name="servicio_inicial_id">
                          <option value="">-- Ninguno (Solo registrar cliente) --</option>
                          @foreach($servicios as $servicio)
                              <option value="{{ $servicio->id }}">
                                  {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                              </option>
                          @endforeach
                      </select>
                  </div>

                  {{-- Fila 4: Especificaciones --}}
                  <div class="mb-3">
                      <label for="especificaciones" class="form-label">Notas / Especificaciones</label>
                      <textarea class="form-control" name="especificaciones" rows="2" placeholder="Ej: Cuidado con la camisa roja, usar jabón hipoalergénico..."></textarea>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-primary">Guardar y Crear Nota</button>
              </div>
          </form>
        </div>
      </div>
    </div>
@endsection