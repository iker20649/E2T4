<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabla de Usuarios (Sanctum)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // Tabla de Citas (Hitzorduak)
        Schema::create('hitzorduak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bezero_id')->constrained('users')->onDelete('cascade');
            $table->date('data');
            $table->time('ordua');
            $table->boolean('finalizatuta')->default(false); // Columna para completado
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hitzorduak');
        Schema::dropIfExists('users');
    }
};