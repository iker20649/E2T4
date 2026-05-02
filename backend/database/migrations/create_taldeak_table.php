<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('txandak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikasle_id')->constrained('ikasleak')->onDelete('cascade');
            $table->date('data');
            $table->time('hasiera_ordua');
            $table->time('bukaera_ordua');
            $table->enum('rola', ['harrera', 'laguntzaile', 'bestea'])->default('harrera');
            $table->text('oharrak')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['ikasle_id', 'data', 'hasiera_ordua']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('txandak');
    }
};