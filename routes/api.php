<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Ruta de prueba (Útil para ver si hay conexión sin loguearse)
Route::get('/ping', function () {
    return response()->json(['status' => 'ok', 'mensaje' => 'Conexión exitosa con Lavandería JOSMA']);
});

// 2. Rutas Públicas de Autenticación
Route::post('/login', [MobileApiController::class, 'login']);
Route::post('/login-google', [MobileApiController::class, 'loginWithGoogle']);

// 3. Rutas Protegidas (Solo accesibles con Token válido)
Route::middleware('auth:sanctum')->group(function () {

    // Datos del usuario actual
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Módulo Mantenimientos (Semáforo)
    Route::get('/mantenimientos', [MobileApiController::class, 'getMantenimientos']);
    
    // Módulo Cortes (Reportes Financieros)
    Route::get('/cortes', [MobileApiController::class, 'getCortes']);
    
    // Módulo Usuarios (CRUD + Claves)
    Route::get('/users', [MobileApiController::class, 'getUsers']);
    Route::post('/users', [MobileApiController::class, 'createUser']);
    Route::put('/users/{id}', [MobileApiController::class, 'updateUser']);
    Route::delete('/users/{id}', [MobileApiController::class, 'deleteUser']);
    
    // Cerrar Sesión
    Route::post('/logout', [MobileApiController::class, 'logout']);
});