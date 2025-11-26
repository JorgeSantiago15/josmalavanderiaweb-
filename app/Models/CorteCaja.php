<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorteCaja extends Model
{
    use HasFactory;

    // Le decimos a Laravel que la tabla se llama 'cortes_caja' (plural)
    protected $table = 'cortes_caja';

    // Permitimos que se guarden todos estos campos masivamente
    protected $fillable = [
        'usuario_id',
        'fecha',
        'turno',
        'fondo_caja_inicial',
        'total_ventas_calculado',
        'total_servicios_lavadora',
        'total_servicios_secadora',
        'total_servicios_doblado',
        'total_comisiones_pagadas',
        'total_efectivo_reportado',
        'total_transferencia_reportado',
        'total_general_reportado',
        'diferencia'
    ];

    // Relación: Un corte pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}