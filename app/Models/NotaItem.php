<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    use HasFactory;

    protected $table = 'nota_items'; // Asegúrate que coincida con tu BD

    protected $fillable = [
        'nota_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // ESTA ES LA FUNCIÓN QUE TE FALTA PARA QUE LOS CONTADORES FUNCIONEN
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    // Relación inversa con nota (opcional pero recomendada)
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}