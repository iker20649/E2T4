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
            $table->string('telefonoa')->nullable();
            $table->string('email')->nullable();
            $table->boolean('etxeko_bezeroa')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bezeroak');
    }
};