<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Taldea;
use App\Models\Bezeroa;
use App\Models\Produktua;
use App\Models\Hitzordua;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Taldea sortu
        $t1 = Taldea::create([
            'izena' => '3WAG2',
            'lan_egunak' => json_encode(['Monday', 'Wednesday', 'Friday'])
        ]);

        // 2. Erabiltzailea sortu
        $u1 = User::create([
            'name' => 'Iker Zubizarreta',
            'email' => 'iker@ikasle.com',
            'password' => Hash::make('12345678'),
            'rola' => 'ikaslea',
            'taldea_id' => $t1->id
        ]);

        // 3. Bezeroa sortu
        $b1 = Bezeroa::create([
            'izena' => 'Gorka',
            'abizenak' => 'Iturbe',
            'telefonoa' => '600111222'
        ]);

        // 4. Produktua sortu
        Produktua::create([
            'izena' => 'Xanpu Loreal 1L',
            'stock' => 10,
            'stock_minimo' => 2
        ]);

        // 5. Hitzordua sortu
        Hitzordua::create([
            'user_id' => $u1->id,
            'bezero_id' => $b1->id,
            'data' => now(),
            'deskribapena' => 'Mozketa orokorra'
        ]);
    }
}