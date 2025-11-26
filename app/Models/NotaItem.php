<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_id', 
        'producto_id', 
        'cantidad', 
        'precio_unitario', 
        'subtotal'
    ];

    // Relación inversa: Un item pertenece a un producto (para saber su nombre)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}