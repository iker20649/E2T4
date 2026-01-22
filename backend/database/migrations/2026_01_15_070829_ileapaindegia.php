<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Produktuen Taula
        Schema::create('produktuak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->string('marka')->nullable();
            $table->decimal('prezioa', 8, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->timestamps();
        });

        // Hitzorduen Taula (Hemen 'user_id' erabiltzen dugu ikaslearekin lotzeko)
        Schema::create('hitzorduak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('bezeroa'); 
            $table->dateTime('data');  
            $table->text('deskribapena')->nullable();
            $table->boolean('finalizatuta')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hitzorduak');
        Schema::dropIfExists('produktuak');
    }
};