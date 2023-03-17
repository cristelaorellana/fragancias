<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;
    protected $table = "detalle_ventas";

    //relacion inversa con producto
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    public function venta(){
        return $this->belongsTo(Venta::class);
    }
}
