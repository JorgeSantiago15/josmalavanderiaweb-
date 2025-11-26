<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CorteController;
use App\Http\Controllers\ClienteController;

// --- RUTAS PÚBLICAS (Cualquiera puede entrar) ---

// Redirigir la raíz '/' al login si no están logueados
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS (Solo usuarios logueados) ---
// El 'middleware' -> 'auth' es el guardián de la puerta
Route::middleware(['auth'])->group(function () {
    

 // RUTAS DE CORTE DE CAJA
    Route::get('/corte', [CorteController::class, 'index'])->name('corte.index');
    Route::post('/corte', [CorteController::class, 'store'])->name('corte.store');

    // Aquí pondremos todas las futuras rutas (clientes, cortes, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //  Para guardar el formulario del modal
    Route::post('/dashboard/nuevo-servicio', [DashboardController::class, 'store'])->name('notas.store');

    // Ver detalle de la nota
    Route::get('/notas/{id}', [DashboardController::class, 'show'])->name('notas.show');
    
    // Agregar producto a la nota
    Route::post('/notas/{id}/agregar', [DashboardController::class, 'agregarItem'])->name('notas.agregar');

    // Acción de terminar nota
    Route::post('/notas/{id}/terminar', [DashboardController::class, 'terminar'])->name('notas.terminar');

    // Vista de la lista de terminados
    Route::get('/terminados', [DashboardController::class, 'indexTerminados'])->name('terminados');
    // Acción de cobrar (Pagar)
    Route::post('/notas/{id}/pagar', [DashboardController::class, 'pagar'])->name('notas.pagar');

    // Vista de historial de pagos
    Route::get('/pagados', [DashboardController::class, 'indexPagados'])->name('pagados');
    //Eliminar nota de venta
    Route::delete('/notas/{id}', [DashboardController::class, 'destroy'])->name('notas.destroy');
    //Clientes frecuentes 
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
});