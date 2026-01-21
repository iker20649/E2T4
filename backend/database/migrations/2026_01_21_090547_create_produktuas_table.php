<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produktuak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');          // Nombre del producto
            $table->string('marka')->nullable(); // Marca
            $table->integer('stock')->default(0); // Cantidad actual
            $table->integer('stock_minimo')->default(5); // Umbral de alerta
            $table->decimal('prezioa', 8, 2); // Precio
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produktuak');
    }
};
