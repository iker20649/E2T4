<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('maileguas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('materiala_id')->constrained('materialas');
        $table->foreignId('ikaslea_id')->constrained('ikasleas');
        $table->dateTime('hasiera');
        $table->dateTime('amaiera')->nullable(); // Itzultzean beteko da
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maileguas');
    }
};
