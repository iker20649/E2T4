<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bezeroa;
use App\Models\Profesionala;
use App\Models\Zerbitzua;
use App\Models\Kontsumigarria;
use App\Models\Hitzordua;
use App\Models\HitzorduZerbitzua;
use App\Models\KontsumoErregistroa;
use App\Models\IkasleEkipoa;
use App\Models\User; // <--- NUEVO: Para crear usuarios de login
use Illuminate\Support\Facades\Hash; // <--- NUEVO: Para encriptar contraseñas

class ileapaindegia extends Seeder
{
    public function run(): void
    {
        // 1. BEZEROAK
        $b1 = Bezeroa::create(['izena' => 'Gorka', 'abizenak' => 'Iturbe', 'telefonoa' => '600111222', 'emaila' => 'gorka@email.com', 'etxeko_bezeroa' => false]);
        $b2 = Bezeroa::create(['izena' => 'Elena', 'abizenak' => 'Sanz', 'telefonoa' => '677888999', 'emaila' => 'elena@email.com', 'etxeko_bezeroa' => true]);

        // 2. PROFESIONALAK
        $p1 = Profesionala::create(['izena' => 'Amaia', 'abizenak' => 'Arroyo', 'rola' => 'irakaslea', 'talde_id' => 1]);
        $p2 = Profesionala::create(['izena' => 'Iker', 'abizenak' => 'Zubizarreta', 'rola' => 'ikaslea', 'talde_id' => 2]);

        // 3. ZERBITZUAK
        $z1 = Zerbitzua::create(['izena' => 'Gizonen mozketa', 'prezioa' => 15.50, 'iraupena' => 30]);
        $z2 = Zerbitzua::create(['izena' => 'Emakumeen mozketa', 'prezioa' => 25.00, 'iraupena' => 60]);

        // 4. KONTSUMIGARRIAK (Stock)
        $k1 = Kontsumigarria::create([
            'izena' => 'Xanpu Anticaspa 1L', 
            'marka' => 'Loreal',
            'stock_kopurua' => 10,
            'gutxieneko_stocka' => 2,
            'iraungitze_data' => '2026-12-31'
        ]);

        // 5. HITZORDUAK
        $h1 = Hitzordua::create([
            'bezero_id' => $b1->id,
            'profesional_id' => $p2->id,
            'data' => now()->format('Y-m-d'),
            'hasiera_ordua' => '10:00:00',
            'amaiera_ordua' => '11:00:00',
            'iraupena' => 60,
            'oharrak' => 'Lehenengo aldiz dator'
        ]);

        // 6. HITZORDU_ZERBITZUAK
        HitzorduZerbitzua::create([
            'hitzordu_id' => $h1->id,
            'zerbitzu_id' => $z1->id,
            'kopurua' => 1,
            'oharrak' => 'Oso pozik'
        ]);

        // 7. KONTSUMO_ERREGISTROAK
        KontsumoErregistroa::create([
            'kontsumigarri_id' => $k1->id,
            'erabiltzaile_id' => $p2->id,
            'kopurua' => 1,
            'intzidentzia' => 'Botila erdia erori da',
            'oharrak' => 'Kontuz lurrekin'
        ]);

        // 8. IKASLE_EKIPOAK
        IkasleEkipoa::create([
            'profesional_id' => $p2->id,
            'ekipo_id' => 5,
            'hasiera_data' => now(),
            'amaiera_data' => now()->addMinutes(30),
            'oharrak' => 'Ekipoa ondo funtzionatzen du'
        ]);

        // --- DÍA 7: AUTENTICACIÓN Y ROLES ---
        // 9. USUARIOS DE LOGIN
        User::create([
            'name' => 'Admin Irakaslea',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'), // Contraseña: 12345678
            'rola' => 'irakaslea',
        ]);

        User::create([
            'name' => 'Ikasle Erabiltzailea',
            'email' => 'ikasle@ikasle.com',
            'password' => Hash::make('12345678'), // Contraseña: 12345678
            'rola' => 'ikaslea',
        ]);
    }
}