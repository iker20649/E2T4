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
    // Kontuz hemen! 'hitzorduas' zegoen tokian 'hitzorduak' jarri behar duzu
    Schema::table('hitzorduak', function (Blueprint $table) {
        $table->foreignId('ikasle_ekipoa_id')->nullable()->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('hitzorduak', function (Blueprint $table) {
        $table->dropForeign(['ikasle_ekipoa_id']);
        $table->dropColumn('ikasle_ekipoa_id');
    });
}
};
