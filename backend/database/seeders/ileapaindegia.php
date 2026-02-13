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
use App\Models\User;
use App\Models\Taldea; // <--- BERRIA
use App\Models\Materiala; // <--- BERRIA
use App\Models\Mailegua; // <--- BERRIA
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TALDEAK (Lehenik sortu behar dira profesionalak lotzeko)
        $t1 = Taldea::create([
            'izena' => '3WAG2',
            'lan_egunak' => ['Monday', 'Wednesday', 'Friday']
        ]);

        $t2 = Taldea::create([
            'izena' => '2WAG1',
            'lan_egunak' => ['Tuesday', 'Thursday']
        ]);

        // 2. BEZEROAK
        $b1 = Bezeroa::create(['izena' => 'Gorka', 'abizenak' => 'Iturbe', 'telefonoa' => '600111222', 'emaila' => 'gorka@email.com', 'etxeko_bezeroa' => false]);
        $b2 = Bezeroa::create(['izena' => 'Elena', 'abizenak' => 'Sanz', 'telefonoa' => '677888999', 'emaila' => 'elena@email.com', 'etxeko_bezeroa' => true]);

        // 3. PROFESIONALAK (Taldeei lotuta)
        $p1 = Profesionala::create(['izena' => 'Amaia', 'abizenak' => 'Arroyo', 'rola' => 'irakaslea', 'talde_id' => $t1->id]);
        $p2 = Profesionala::create(['izena' => 'Iker', 'abizenak' => 'Zubizarreta', 'rola' => 'ikaslea', 'talde_id' => $t1->id]);

        // 4. ZERBITZUAK
        $z1 = Zerbitzua::create(['izena' => 'Gizonen mozketa', 'prezioa' => 15.50, 'iraupena' => 30]);
        $z2 = Zerbitzua::create(['izena' => 'Emakumeen mozketa', 'prezioa' => 25.00, 'iraupena' => 60]);

        // 5. KONTSUMIGARRIAK (Stock Fungiblea)
        $k1 = Kontsumigarria::create([
            'izena' => 'Xanpu Anticaspa 1L', 
            'marka' => 'Loreal',
            'stock_kopurua' => 10,
            'gutxieneko_stocka' => 2,
            'iraungitze_data' => '2026-12-31'
        ]);

        $k2 = Kontsumigarria::create([
            'izena' => 'Tinte Gorria #05', 
            'marka' => 'Wella',
            'stock_kopurua' => 1, // Alerta gorria emateko
            'gutxieneko_stocka' => 3,
            'iraungitze_data' => '2027-01-01'
        ]);

        // 6. MATERIAL EZ-FUNGIBLEA (Tresneria / Maileguak)
        $m1 = Materiala::create([
            'izena' => 'Lehorgailu Profesionala A1', 
            'etiketa' => 'LEH-001', 
            'libre' => true
        ]);

        $m2 = Materiala::create([
            'izena' => 'Plantxa GHD XL', 
            'etiketa' => 'PLA-002', 
            'libre' => true
        ]);

        // 7. HITZORDUAK (Iker ikasleari esleituta)
        $h1 = Hitzordua::create([
            'bezero_id' => $b1->id,
            'profesional_id' => $p2->id,
            'data' => now()->format('Y-m-d'),
            'hasiera_ordua' => '10:00:00',
            'amaiera_ordua' => '11:00:00',
            'iraupena' => 60,
            'oharrak' => 'Lehenengo aldiz dator',
            'finalizatuta' => false
        ]);

        // 8. ERABILTZAILEAK (Vue-ko login-erako)
        User::create([
            'name' => 'Admin Irakaslea',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'rola' => 'irakaslea',
        ]);

        User::create([
            'name' => 'Ikasle Erabiltzailea',
            'email' => 'ikasle@ikasle.com',
            'password' => Hash::make('12345678'),
            'rola' => 'ikaslea',
        ]);

        // 9. KONTSUMO ETA EKIPO ERREGISTROAK (Lehendik zenuena)
        KontsumoErregistroa::create([
            'kontsumigarri_id' => $k1->id,
            'erabiltzaile_id' => $p2->id,
            'kopurua' => 1,
            'intzidentzia' => 'Botila erdia erori da'
        ]);

        IkasleEkipoa::create([
            'profesional_id' => $p2->id,
            'ekipo_id' => 5,
            'hasiera_data' => now(),
            'amaiera_data' => now()->addMinutes(30)
        ]);
    }
}