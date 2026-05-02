<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zerbitzuak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->decimal('prezioa', 8, 2);
            $table->decimal('etxeko_prezioa', 8, 2)->nullable();
            $table->integer('iraupena'); // minutuak
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('zerbitzuak');
    }
};