<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    public function marca(){
        return $this->hasMany(Marca::class);
    }
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    }
}