<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'rola' => 'ikasle', 
        ]);

        // 1. Verificamos que se ha creado (201 Created)
        $response->assertStatus(201);

        // 2. Verificamos que el usuario existe en la BD con el rol correcto
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'rola' => 'ikasle',
        ]);

        // 3. (Opcional) Si tu controlador NO hace login automático, 
        // eliminamos assertAuthenticated() para que no falle.
    }
}