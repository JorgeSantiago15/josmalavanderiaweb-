<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lavandería - @yield('titulo')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Josma Lavandería</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        {{-- Menú Principal --}}
        <div class="collapse navbar-collapse" id="navbarNav">
          
          {{-- Enlaces Izquierda --}}
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            {{-- Opción: En Proceso --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('notas.show') ? 'active' : '' }}" 
                 href="{{ route('dashboard') }}">
                 <i class="bi bi-hourglass-split"></i> En Proceso
              </a>
            </li>

            {{-- Opción: Terminados --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('terminados') ? 'active' : '' }}" 
                 href="{{ route('terminados') }}">
                 <i class="bi bi-check2-circle"></i> Terminados
              </a>
            </li>

            {{-- Opción: Pagados --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('pagados') ? 'active' : '' }}" 
                 href="{{ route('pagados') }}">
                 <i class="bi bi-cash-stack"></i> Pagados
              </a>
            </li>
{{-- Opción: Corte de caja --}}
           <li class="nav-item">
  <a class="nav-link {{ request()->routeIs('corte.index') ? 'active' : '' }}" 
     href="{{ route('corte.index') }}">
     <i class="bi bi-calculator"></i> Corte de Caja
  </a>
</li>
{{-- Opción: Lista de clientes --}}
<li class="nav-item">
  <a class="nav-link" href="{{ route('clientes.index') }}"><i class="bi bi-people"></i> Clientes</a>
</li>
   {{-- Opción EXCLUSIVA para Gerentes --}}
            @if(Auth::user()->tipo === 'gerente')
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light fw-bold border-2" 
                       href="{{ route('gerencia.index') }}">
                       <i class="bi bi-shield-lock-fill"></i> Gerencia
                    </a>
                </li>
            @endif
          </ul>
       

          {{-- Lado Derecho: Usuario y Salir --}}
          <div class="d-flex align-items-center">
             {{-- Verificamos que haya usuario logueado para evitar errores --}}
             @auth
                <span class="navbar-text me-3 text-white">
                    <i class="bi bi-person-circle"></i> 
                    {{ Auth::user()->nombre }} ({{ Auth::user()->turno_asignado }})
                </span>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Salir</button>
                </form>
             @endauth
          </div>

        </div>
      </div>
    </nav>

    <main class="container">
        @yield('contenido')
    </main>

  </body>
</html>