<?php

namespace Database\Factories;

use App\Models\Erabiltzailea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ErabiltzaileaFactory extends Factory
{
    protected $model = Erabiltzailea::class;

    public function definition()
    {
        return [
            'izena' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'rola' => 'ikasle',
        ];
    }
}