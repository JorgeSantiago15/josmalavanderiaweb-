@extends('layouts.app')

@section('titulo')
    Mantenimiento General
@endsection

@section('contenido')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="bi bi-tools"></i> Mantenimiento e Infraestructura</h2>
        
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalUrgencia">
            <i class="bi bi-exclamation-triangle-fill"></i> Reportar Falla Urgente
        </button>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Tareas Pendientes</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($mantenimientos->isEmpty())
                        <div class="alert alert-success text-center">
                            ¡Todo al día! No hay mantenimientos pendientes.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Mantenimiento</th>
                                        <th>Categoría</th>
                                        <th>Programado Para</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mantenimientos as $item)
                                    <tr class="@if($item->color == 'danger') table-danger @elseif($item->color == 'warning') table-warning @endif">
                                        <td class="text-center">
                                            @if($item->tipo == 'urgente')
                                                <span class="badge bg-danger">URGENTE</span>
                                            @else
                                                @if($item->color == 'danger') <span class="badge bg-danger">VENCIDO</span>
                                                @elseif($item->color == 'warning') <span class="badge bg-warning text-dark">PRÓXIMO</span>
                                                @else <span class="badge bg-success">A TIEMPO</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->nombre }}</strong>
                                            @if($item->tipo == 'urgente')
                                                <br><small class="text-danger">{{ $item->descripcion }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->categoria == 'infraestructura')
                                                <i class="bi bi-building"></i> Infraestructura
                                            @else
                                                <i class="bi bi-gear-wide-connected"></i> Maquinaria
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->fecha_programada)->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($item->fecha_programada)->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                
                                                {{-- BOTÓN 1: INSTRUCCIONES (Usa Data Attributes) --}}
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-ver-instrucciones" 
                                                        data-titulo="{{ $item->nombre }}"
                                                        data-desc="{{ $item->descripcion }}">
                                                    <i class="bi bi-eye"></i> Ver Detalle
                                                </button>

                                                {{-- BOTÓN 2: ACCIONES --}}
                                                @if($item->tipo == 'urgente')
                                                    {{-- Urgentes: Se completan directo --}}
                                                    <form action="{{ route('mantenimientos.completar', $item->id) }}" method="POST" onsubmit="return confirm('¿Falla resuelta?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Listo</button>
                                                    </form>
                                                @else
                                                    {{-- Preventivos: Se abren en Modal --}}
                                                    {{-- La clave aquí es 'data-frecuencia="{{ $item->frecuencia_dias ?? 0 }}"' --}}
                                                    <button type="button" class="btn btn-sm btn-success btn-realizar"
                                                            data-id="{{ $item->id }}"
                                                            data-nombre="{{ $item->nombre }}"
                                                            data-frecuencia="{{ $item->frecuencia_dias ?? 0 }}">
                                                        <i class="bi bi-check-lg"></i> Realizar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REPORTE URGENTE --}}
<div class="modal fade" id="modalUrgencia" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Reportar Falla Urgente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('mantenimientos.storeUrgente') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="mb-3">
                  <label>Falla en:</label>
                  <input type="text" name="nombre" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Categoría</label>
                  <select name="categoria" class="form-select">
                      <option value="maquinaria">Maquinaria</option>
                      <option value="infraestructura">Infraestructura</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Detalle</label>
                  <textarea name="descripcion" class="form-control" rows="2" required></textarea>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger">Reportar</button>
          </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL INSTRUCCIONES --}}
<div class="modal fade" id="modalInstrucciones" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="instTitulo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <p id="instDesc" class="fs-5"></p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL FINALIZAR --}}
<div class="modal fade" id="modalFinalizar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Registrar Mantenimiento Realizado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formFinalizar" action="" method="POST">
          @csrf
          <div class="modal-body">
              <h5 id="finTitulo" class="fw-bold mb-3"></h5>
              
              <div class="alert alert-light border">
                  <i class="bi bi-info-circle"></i> Este servicio se realiza cada <strong id="finFrecuencia"></strong> días.
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold">Se realizó el mantenimiento hoy.</label>
                  <p class="text-muted small">Al guardar, se actualizará el historial.</p>
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold text-primary">¿Cuándo toca la próxima revisión?</label>
                  <input type="date" name="proxima_fecha" id="finFecha" class="form-control form-control-lg" required>
                  <div class="form-text">Calculado automáticamente (Hoy + Frecuencia). Puedes cambiarlo.</div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Confirmar y Reprogramar</button>
          </div>
      </form>
    </div>
  </div>
</div>

{{-- SCRIPT CORREGIDO Y ROBUSTO --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Manejar botones de INSTRUCCIONES
        const btnsInstrucciones = document.querySelectorAll('.btn-ver-instrucciones');
        btnsInstrucciones.forEach(btn => {
            btn.addEventListener('click', function() {
                const titulo = this.getAttribute('data-titulo');
                const desc = this.getAttribute('data-desc');

                document.getElementById('instTitulo').innerText = titulo;
                document.getElementById('instDesc').innerText = desc;
                
                new bootstrap.Modal(document.getElementById('modalInstrucciones')).show();
            });
        });

        // 2. Manejar botones de REALIZAR (Reprogramar)
        const btnsRealizar = document.querySelectorAll('.btn-realizar');
        btnsRealizar.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                // Convertimos a entero, si viene vacío usa 0
                const frecuencia = parseInt(this.getAttribute('data-frecuencia')) || 0;

                document.getElementById('finTitulo').innerText = nombre;
                document.getElementById('finFrecuencia').innerText = frecuencia;

                // Construir URL del formulario
                let urlBase = "{{ route('mantenimientos.completar', ':id') }}";
                urlBase = urlBase.replace(':id', id);
                document.getElementById('formFinalizar').action = urlBase;

                // Calcular Fecha: Hoy + Frecuencia
                let hoy = new Date();
                hoy.setDate(hoy.getDate() + frecuencia);

                // Formatear fecha para input date (YYYY-MM-DD)
                let dia = ("0" + hoy.getDate()).slice(-2);
                let mes = ("0" + (hoy.getMonth() + 1)).slice(-2);
                let fechaCalculada = hoy.getFullYear() + "-" + mes + "-" + dia;

                document.getElementById('finFecha').value = fechaCalculada;

                new bootstrap.Modal(document.getElementById('modalFinalizar')).show();
            });
        });

    });
</script>
@endsection