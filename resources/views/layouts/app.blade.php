<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Josma Lavandería - @yield('titulo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Fuente Opcional (Google Fonts) para que se vea más moderno --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
        .nav-link { font-weight: 500; transition: all 0.2s; border-radius: 8px; margin-right: 5px; padding: 8px 15px !important; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); }
        .nav-link.active { background-color: rgba(255,255,255,0.2); color: white !important; font-weight: 600; }
    </style>
  </head>
  <body>

    {{-- Navbar con más padding (py-3) y sombra --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 shadow mb-4">
      <div class="container"> {{-- Usamos container en vez de container-fluid para centrarlo más --}}
        
        <a class="navbar-brand d-flex align-items-center gap-2 me-4" href="{{ route('dashboard') }}">
             {{-- LOGO --}}
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="rounded shadow-sm" style="height: 45px; width: auto; background: white; padding: 4px;">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold fs-5">JOSMA</span>
                <span style="font-size: 0.75rem; opacity: 0.9;">Punto de Venta</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
            
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('notas.show') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                 <i class="bi bi-hourglass-split me-1"></i> Proceso
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('terminados') ? 'active' : '' }}" href="{{ route('terminados') }}">
                 <i class="bi bi-check2-circle me-1"></i> Terminados
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('pagados') ? 'active' : '' }}" href="{{ route('pagados') }}">
                 <i class="bi bi-wallet2 me-1"></i> Pagados
              </a>
            </li>

            {{-- Separador Vertical Visual --}}
            <div class="d-none d-lg-block mx-2 border-end border-white opacity-25" style="height: 25px;"></div>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('corte.index') ? 'active' : '' }}" href="{{ route('corte.index') }}">
                 <i class="bi bi-calculator me-1"></i> Corte
              </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('clientes.*') || request()->routeIs('mantenimientos.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-grid-fill me-1"></i> Catálogos
                </a>
                <ul class="dropdown-menu shadow border-0">
                    <li><a class="dropdown-item" href="{{ route('clientes.index') }}"><i class="bi bi-people me-2 text-primary"></i>Clientes</a></li>
                    <li><a class="dropdown-item" href="{{ route('mantenimientos.index') }}"><i class="bi bi-tools me-2 text-warning"></i>Mantenimiento</a></li>
                </ul>
            </li>

            @if(Auth::user()->tipo === 'gerente')
                <li class="nav-item ms-2">
                    <a class="btn btn-sm bg-white text-primary fw-bold shadow-sm" href="{{ route('gerencia.index') }}">
                       <i class="bi bi-shield-lock-fill"></i> Gerencia
                    </a>
                </li>
            @endif
          </ul>

        {{-- Lado Derecho: Usuario y Salir --}}
          <div class="d-flex align-items-center gap-3">
             @auth
                {{-- Info del Usuario (Nombre y Turno) --}}
                <div class="text-end text-white d-none d-lg-block" style="line-height: 1.2;">
                    <div class="fw-bold">{{ Auth::user()->nombre }}</div>
                    <small class="badge bg-white bg-opacity-25 rounded-pill" style="font-size: 0.7em; letter-spacing: 0.5px;">
                        {{ strtoupper(Auth::user()->turno_asignado) }}
                    </small>
                </div>
                
                {{-- Botón Salir --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light d-flex align-items-center gap-2 opacity-75" 
                            style="transition: all 0.2s;"
                            onmouseover="this.classList.remove('opacity-75'); this.classList.add('bg-white', 'text-primary')" 
                            onmouseout="this.classList.add('opacity-75'); this.classList.remove('bg-white', 'text-primary')">
                        
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline small">Cerrar Sesión</span>
                    
                    </button>
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