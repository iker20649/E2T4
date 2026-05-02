<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikasle_ekipamenduak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikasle_id')->constrained('ikasleak')->onDelete('cascade');
            $table->foreignId('ekipamendu_id')->constrained('ekipamenduak')->onDelete('cascade');
            $table->datetime('hasiera_data');
            $table->datetime('bukaera_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ikasle_ekipamenduak');
    }
};