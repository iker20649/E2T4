<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario PROFESOR
        User::create([
            'name' => 'Irakasle Test',
            'email' => 'irakasle@test.com',
            'password' => Hash::make('password'),
            'rola' => 'irakaslea',
        ]);

        // Usuario ALUMNO
        User::create([
            'name' => 'Ikasle Test',
            'email' => 'ikasle@test.com',
            'password' => Hash::make('password'),
            'rola' => 'ikaslea',
        ]);
    }
}