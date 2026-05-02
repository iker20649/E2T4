<?php

namespace Database\Factories;

use App\Models\Hitzordua;
use App\Models\Bezeroa;
use App\Models\Ikaslea;
use Illuminate\Database\Eloquent\Factories\Factory;

class HitzorduaFactory extends Factory
{
    protected $model = Hitzordua::class;

    public function definition()
    {
        return [
            'lekua' => $this->faker->numberBetween(1, 10),
            'data' => $this->faker->date(),
            'hasiera_ordua' => '10:00',
            'bukaera_ordua' => '11:00',
            'bezero_id' => Bezeroa::factory(),
            'ikasle_id' => Ikaslea::factory(),
        ];
    }
}