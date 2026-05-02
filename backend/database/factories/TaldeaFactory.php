<?php

namespace Database\Factories;

use App\Models\Taldea;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaldeaFactory extends Factory
{
    protected $model = Taldea::class;

    public function definition()
    {
        return [
            'izena' => $this->faker->word,
        ];
    }
}