<?php

namespace Database\Factories;

use App\Models\Txanda;
use App\Models\Ikaslea;
use Illuminate\Database\Eloquent\Factories\Factory;

class TxandaFactory extends Factory
{
    protected $model = Txanda::class;

    public function definition()
    {
        return [
            'ikasle_id' => Ikaslea::factory(),
            'data' => $this->faker->date(),
            'hasiera_ordua' => '09:00',
            'bukaera_ordua' => '13:00',
            'rola' => 'harrera',
        ];
    }
}