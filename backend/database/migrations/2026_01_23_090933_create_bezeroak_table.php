<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bezeroak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->string('abizenak');
            $table->string('emaila')->nullable();
            $table->string('telefonoa')->nullable();
            $table->integer('bisita_kopurua')->default(1);
            $table->date('azken_bisita')->nullable();
            $table->text('deskribapena')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bezeroak');
    }
};