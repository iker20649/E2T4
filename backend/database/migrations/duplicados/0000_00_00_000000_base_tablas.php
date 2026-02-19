<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('taldeak', function (Blueprint $table) {
            $table->id();
            $table->string('izena'); 
            $table->json('lan_egunak')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('rola')->default('ikaslea');
            $table->foreignId('taldea_id')->nullable()->constrained('taldeak')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('bezeroak', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->string('abizenak');
            $table->string('telefonoa')->nullable();
            $table->timestamps();
        });

        Schema::create('produktuas', function (Blueprint $table) {
            $table->id();
            $table->string('izena');
            $table->string('marka')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->timestamps();
        });

        Schema::create('hitzorduas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bezero_id')->constrained('bezeroak')->onDelete('cascade');
            $table->dateTime('data');
            $table->text('deskribapena')->nullable();
            $table->boolean('finalizatuta')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hitzorduak');
        Schema::dropIfExists('produktuak');
        Schema::dropIfExists('bezeroak');
        Schema::dropIfExists('users');
        Schema::dropIfExists('taldeak');
    }
};
