<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'categoria', 'tipo', 
        'frecuencia_dias', 'fecha_programada', 'fecha_realizada', 
        'estado', 'usuario_id'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // ACCESOR: Calcular color/urgencia dinámicamente
    public function getColorAttribute()
    {
        // Si es urgente, siempre es ROJO
        if ($this->tipo == 'urgente') {
            return 'danger'; 
        }

        $hoy = Carbon::now();
        $fechaProgramada = Carbon::parse($this->fecha_programada);
        $diasRestantes = $hoy->diffInDays($fechaProgramada, false); // false para permitir negativos

        // Si ya se pasó la fecha (dias negativos) -> AMARILLO (según tu indicación)
        // O si tú prefieres Rojo para vencidos, cambia 'warning' por 'danger' aquí.
        if ($diasRestantes < 0) return 'warning'; 

        // Si faltan menos de 14 días (2 semanas) -> ROJO
        if ($diasRestantes <= 14) return 'danger';

        // Si falta menos de un mes (30 días) -> AMARILLO
        if ($diasRestantes <= 30) return 'warning';

        // Si falta más tiempo -> VERDE
        return 'success';
    }
}