<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hitzorduak', function (Blueprint $table) {
            // Añadimos la columna booleana (true/false)
            $table->boolean('finalizatuta')->default(false)->after('ordua');
        });
    }

    public function down(): void
    {
        Schema::table('hitzorduak', function (Blueprint $table) {
            $table->dropColumn('finalizatuta');
        });
    }
};