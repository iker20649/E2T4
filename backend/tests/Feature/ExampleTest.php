<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Comprueba que la raíz de la API responde correctamente.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Si tu API tiene un prefijo, puedes probar con '/api'
        // O simplemente comprobar que la base del servidor está activa
        $response = $this->getJson('/');

        // Si '/' no tiene ruta, cámbialo por una que exista, como '/api/admin/ikasleak'
        // o simplemente acepta que si no hay nada, devuelva un 404 pero el servidor responda.
        $response->assertStatus(200); 
    }
}