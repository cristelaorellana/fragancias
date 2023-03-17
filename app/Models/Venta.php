<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = "ventas";

    //relacion 1:N detalle ventas
    public function detalle_ventas(){
          return $this->hasMany(detalleVenta::class);
    }
    public function user(){
        return $this->belongsTo(user::class);
    }
}
