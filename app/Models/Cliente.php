<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'telefono'];

    // --- AGREGA ESTA FUNCIÓN ---
    // Relación: Un cliente tiene muchas notas de servicio
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}