<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    //relacion de 1:N con fragancias
    public function producto(){
        return $this->hasMany(producto::class);
    }
}
