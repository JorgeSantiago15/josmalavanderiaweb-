<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    // Campos permitidos para guardar masivamente
    protected $fillable = [
        'cliente_id', 
        'usuario_id', 
        'estado', 
        'especificaciones', 
        'total', 
        'fecha_recepcion',
        'fecha_entrega_estimada',
        'fecha_pagado'
    ];

    // Relación: Una nota "pertenece a" un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    // Relación: Una nota "tiene muchos" items
    public function items()
    {
        return $this->hasMany(NotaItem::class);
    }
}