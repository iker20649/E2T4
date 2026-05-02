<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordutegiak', function (Blueprint $table) {
            $table->id();
            $table->integer('eguna'); // 0-6 astelehena-igandea
            $table->date('hasiera_data');
            $table->date('bukaera_data');
            $table->time('hasiera_ordua');
            $table->time('bukaera_ordua');
            $table->foreignId('talde_id')->constrained('taldeak')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ordutegiak');
    }
};