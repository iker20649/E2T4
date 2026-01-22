<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mugimenduak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('produktua');
            $table->integer('kantitatea'); // Ej: -5 o +10
            $table->string('ekintza');    // Ej: "Erabilita" o "Erosita"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mugimenduak');
    }
};