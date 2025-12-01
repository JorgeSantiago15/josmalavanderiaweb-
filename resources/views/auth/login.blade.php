<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lavandería - Iniciar Sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
  </head>
  <body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="text-primary fw-bold">Josma Lavandería</h2>
            <p class="text-muted"></p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="{{ old('usuario') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            {{-- Mostrar errores de validación --}}
            @error('usuario')
                <div class="alert alert-danger text-center">{{ $message }}</div>
            @enderror

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Entrar al Sistema</button>
            </div>
        </form>
    </div>

  </body>
</html>