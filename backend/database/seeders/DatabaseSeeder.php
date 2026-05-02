<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taldea;
use App\Models\Ikaslea;
use App\Models\Bezeroa;
use App\Models\Hitzordua;
use App\Models\Txanda;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Taldea::factory(3)->create();
        Ikaslea::factory(10)->create();
        Bezeroa::factory(20)->create();
        Hitzordua::factory(15)->create();
        Txanda::factory(10)->create();
    }
}