<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bezeroa;
use App\Models\Hitzordua;
use App\Models\Produktua;
use App\Models\StockMugimendua;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ERABILTZAILEAK (Vue-ko 'irakasle' eta 'ikasle' balioekin bat egiteko)
        $irakasle = User::create([
            'name' => 'Irakasle Principal',
            'email' => 'irakasle@test.com',
            'password' => Hash::make('password'),
            'rola' => 'irakasle', // 'a' gabe, Vue-n horrela dagoelako
        ]);

        $ikasle = User::create([
            'name' => 'Ikasle Test',
            'email' => 'ikasle@test.com',
            'password' => Hash::make('password'),
            'rola' => 'ikasle', // 'a' gabe
        ]);

        // 2. BEZEROAK (Ikasleari lotuta, berak kudeatzen dituelako)
        $bezero1 = Bezeroa::create([
            'izena' => 'Ane',
            'abizenak' => 'Garcia',
            'emaila' => 'ane@example.com',
            'telefonoa' => '600112233',
            'deskribapena' => 'Alergia a tintes amoniacales.',
            'user_id' => $ikasle->id, // Ikaslearen bezeroa
            'bisita_kopurua' => 1,
        ]);

        $bezero2 = Bezeroa::create([
            'izena' => 'Jon',
            'abizenak' => 'Iturbe',
            'emaila' => 'jon@example.com',
            'telefonoa' => '655443322',
            'deskribapena' => 'Corte clásico de caballero.',
            'user_id' => $ikasle->id, // Ikaslearen bezeroa
            'bisita_kopurua' => 3,
        ]);

        // 3. PRODUKTUAK
        $xanpua = Produktua::create([
            'izena' => 'Champú Hidratante 1L',
            'marka' => 'Loreal',
            'prezioa' => 15.50,
            'stock' => 10,
            'stock_minimo' => 2
        ]);

        // 4. HITZORDUAK (Ikaslearen bezeroekin)
        Hitzordua::create([
            'user_id' => $ikasle->id,
            'bezeroa' => 'Ane Garcia',
            'data' => now()->addDays(1),
            'deskribapena' => 'Tinte y Secado profesional',
            'finalizatuta' => false
        ]);

        Hitzordua::create([
            'user_id' => $ikasle->id,
            'bezeroa' => 'Jon Iturbe',
            'data' => now()->addHours(2),
            'deskribapena' => 'Corte de pelo rápido',
            'finalizatuta' => false
        ]);

        // 5. STOCK MUGIMENDUAK (Irakasleak egindakoa bezala agertzeko)
        StockMugimendua::create([
            'user_id' => $irakasle->id,
            'produktua' => 'Champú Hidratante 1L',
            'kantitatea' => 10,
            'ekintza' => 'Erosita'
        ]);
    }
}