<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CorteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GerenteController; // Importante
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MantenimientoController;
// --- RUTAS PÚBLICAS ---
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- GRUPO 1: EMPLEADAS Y GERENTES (Middleware 'auth') ---
// Aquí van el Dashboard, Notas, Cortes, Clientes. Todos pueden entrar aquí.
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/nuevo-servicio', [DashboardController::class, 'store'])->name('notas.store');

    // Notas y Servicios
    Route::get('/notas/{id}', [DashboardController::class, 'show'])->name('notas.show');
    Route::post('/notas/{id}/agregar', [DashboardController::class, 'agregarItem'])->name('notas.agregar');
    Route::post('/notas/{id}/terminar', [DashboardController::class, 'terminar'])->name('notas.terminar');
    Route::delete('/notas/{id}', [DashboardController::class, 'destroy'])->name('notas.destroy'); // Cancelar
    Route::post('/notas/{id}/pagar', [DashboardController::class, 'pagar'])->name('notas.pagar');

    // Listados
    Route::get('/terminados', [DashboardController::class, 'indexTerminados'])->name('terminados');
    Route::get('/pagados', [DashboardController::class, 'indexPagados'])->name('pagados');
    
   // Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    
    // CORRECCIÓN: Usamos PUT y agregamos /{id}
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');

    // Corte de Caja
    Route::get('/corte', [CorteController::class, 'index'])->name('corte.index');
    Route::post('/corte', [CorteController::class, 'store'])->name('corte.store');
    // Módulo de Mantenimiento
Route::get('/mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos.index');
Route::post('/mantenimientos/urgente', [MantenimientoController::class, 'storeUrgente'])->name('mantenimientos.storeUrgente');
Route::post('/mantenimientos/{id}/completar', [MantenimientoController::class, 'completar'])->name('mantenimientos.completar');
Route::get('/mantenimientos/{id}', [MantenimientoController::class, 'show'])->name('mantenimientos.show'); // Para modal
}); 
// <--- AQUÍ SE CIERRA EL GRUPO 'AUTH' (El de todos)  tailwind 


// --- GRUPO 2: SOLO GERENCIA (Middleware 'auth' Y 'admin') ---
// Solo el gerente puede entrar aquí.
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Panel Principal de Gerencia
    Route::get('/gerencia', [GerenteController::class, 'index'])->name('gerencia.index');

    // Gestión de Productos (Rutas automáticas de Laravel para CRUD)
    Route::resource('productos', ProductoController::class)->except(['create', 'show']);
    // Gestión de Usuarios
    Route::resource('usuarios', UserController::class)->except(['create', 'show']);
    //  Historial de Cortes
    Route::get('/gerencia/cortes', [CorteController::class, 'historial'])->name('corte.historial');
    Route::get('/gerencia/cortes/{id}', [CorteController::class, 'show'])->name('corte.show');
});