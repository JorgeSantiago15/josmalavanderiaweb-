@extends('layouts.app')

@section('titulo')
    Directorio de Clientes
@endsection

@section('contenido')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-people-fill"></i> Clientes Registrados</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th class="text-center">Visitas</th>
                        <th>Última Visita</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr>
                        <td class="fw-bold">{{ $cliente->nombre }}</td>
                        <td>
                            <a href="tel:{{ $cliente->telefono }}" class="text-decoration-none">
                                {{ $cliente->telefono }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">{{ $cliente->notas_count }}</span>
                        </td>
                        <td>{{ $cliente->updated_at->diffForHumans() }}</td>
                        <td>
                            {{-- Aquí podríamos poner botón para editar o ver historial --}}
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">
                {{ $clientes->links() }} {{-- Paginación --}}
            </div>
        </div>
    </div>
</div>
@endsection