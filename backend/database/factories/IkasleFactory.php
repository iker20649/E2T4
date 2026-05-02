<?php

namespace Database\Factories;

use App\Models\Ikaslea;
use App\Models\Taldea;
use Illuminate\Database\Eloquent\Factories\Factory;

class IkasleaFactory extends Factory
{
    protected $model = Ikaslea::class;

    public function definition()
    {
        return [
            'izena' => $this->faker->firstName,
            'abizena' => $this->faker->lastName,
            'talde_id' => Taldea::factory(),
        ];
    }
}