<?php

namespace Database\Factories;

use App\Models\Bezeroa;
use Illuminate\Database\Eloquent\Factories\Factory;

class BezeroaFactory extends Factory
{
    protected $model = Bezeroa::class;

    public function definition()
    {
        return [
            'izena' => $this->faker->firstName,
            'abizenak' => $this->faker->lastName,
            'telefonoa' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'etxeko_bezeroa' => $this->faker->boolean,
        ];
    }
}