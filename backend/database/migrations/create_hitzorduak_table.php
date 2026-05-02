<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
       Schema::create('hitzorduak', function (Blueprint $table) {
    $table->id();
    $table->integer('lekua');
    $table->date('data');
    $table->time('hasiera_ordua');
    $table->time('bukaera_ordua');
    $table->text('iruzkinak')->nullable();
    $table->foreignId('ikasle_id')->nullable()->constrained('ikasleak')->onDelete('set null');
    $table->foreignId('bezero_id')->constrained('bezeroak')->onDelete('cascade');
    $table->timestamps();
    $table->softDeletes();
});
    }
};