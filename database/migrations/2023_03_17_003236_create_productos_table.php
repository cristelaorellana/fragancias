<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_producto',80);
            $table->decimal('cantidad_producto',8,2);
            $table->decimal('codigo',8,2);
            $table->decimal('precio',8,2);
            $table->string('imagen',100);
            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')->references('id')->on ('marcas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
