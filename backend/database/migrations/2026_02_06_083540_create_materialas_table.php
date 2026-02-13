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
    Schema::create('materialas', function (Blueprint $table) {
        $table->id();
        $table->string('izena'); // Secador, Plancha...
        $table->string('etiketa')->unique(); // Zenbaki bat identifikatzeko
        $table->boolean('libre')->default(true); // Maileguan dagoen ala ez
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materialas');
    }
};
