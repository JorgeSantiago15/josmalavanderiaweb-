<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CorteCaja;
use App\Models\Producto;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ruta de prueba para ver si la App conecta
Route::get('/ping', function () {
    return response()->json(['status' => 'ok', 'mensaje' => 'Conexión exitosa con Lavandería']);
});

// Ruta para ver reporte de cortes (Para el Gerente)
Route::get('/cortes', function () {
    return CorteCaja::with('user')->latest()->take(30)->get();
});

// Ruta para ver productos/mantenimiento
Route::get('/productos', function () {
    return Producto::all();
});