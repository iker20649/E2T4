<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekipamenduak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->integer('stock')->default(0);
            $table->integer('stock_minimoa')->default(5);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ekipamenduak');
    }
};