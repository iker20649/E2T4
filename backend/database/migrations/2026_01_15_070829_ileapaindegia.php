<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. BEZEROAK
        Schema::create('bezeroak', function (Blueprint $table) {
            $table->id();
            $table->string('izena', 100);
            $table->string('abizenak', 100);
            $table->string('telefonoa', 20)->nullable();
            $table->string('emaila', 100)->nullable();
            $table->boolean('etxeko_bezeroa')->default(false);
            $table->timestamps(); // sortze_data eta eguneratze_data
            $table->softDeletes(); // ezabatze_data
        });

        // 2. PROFESIONALAK
        Schema::create('profesionalak', function (Blueprint $table) {
            $table->id();
            $table->string('izena', 100);
            $table->string('abizenak', 100);
            $table->string('rola', 50); // adib: 'irakaslea', 'ikaslea'
            $table->integer('talde_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. ZERBITZUAK
        Schema::create('zerbitzuak', function (Blueprint $table) {
            $table->id();
            $table->string('izena', 100);
            $table->decimal('prezioa', 10, 2);
            $table->integer('iraupena')->nullable(); // minutuetan
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. KONTSUMIGARRIAK (Stock)
        Schema::create('kontsumigarriak', function (Blueprint $table) {
            $table->id();
            $table->string('izena', 100);
            $table->text('deskribapena')->nullable();
            $table->string('lotea', 50)->nullable();
            $table->string('marka', 50)->nullable();
            $table->integer('stock_kopurua')->default(0);
            $table->integer('gutxieneko_stocka')->default(5);
            $table->date('iraungitze_data')->nullable();
            $table->integer('kategoria_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. HITZORDUAK
        Schema::create('hitzorduak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bezero_id')->constrained('bezeroak');
            $table->foreignId('profesional_id')->constrained('profesionalak');
            $table->date('data');
            $table->time('hasiera_ordua');
            $table->time('amaiera_ordua')->nullable();
            $table->integer('iraupena')->nullable();
            $table->text('oharrak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. HITZORDU_ZERBITZUAK (Pibot taula)
        Schema::create('hitzordu_zerbitzuak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hitzordu_id')->constrained('hitzorduak')->onDelete('cascade');
            $table->foreignId('zerbitzu_id')->constrained('zerbitzuak');
            $table->integer('kopurua')->default(1);
            $table->text('oharrak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. KONTSUMO_ERREGISTROAK
        Schema::create('kontsumo_erregistroak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontsumigarri_id')->constrained('kontsumigarriak');
            $table->foreignId('erabiltzaile_id')->constrained('profesionalak');
            $table->integer('kopurua');
            $table->text('intzidentzia')->nullable();
            $table->text('oharrak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. IKASLE_EKIPOAK
        Schema::create('ikasle_ekipoak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesional_id')->constrained('profesionalak');
            $table->integer('ekipo_id')->nullable();
            $table->timestamp('hasiera_data')->nullable();
            $table->timestamp('amaiera_data')->nullable();
            $table->text('oharrak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikasle_ekipoak');
        Schema::dropIfExists('kontsumo_erregistroak');
        Schema::dropIfExists('hitzordu_zerbitzuak');
        Schema::dropIfExists('hitzorduak');
        Schema::dropIfExists('kontsumigarriak');
        Schema::dropIfExists('zerbitzuak');
        Schema::dropIfExists('profesionalak');
        Schema::dropIfExists('bezeroak');
    }
};