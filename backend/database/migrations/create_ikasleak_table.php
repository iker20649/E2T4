<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikasleak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->string('abizena');
            $table->foreignId('talde_id')->constrained('taldeak')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ikasleak');
    }
};