<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bezeroa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BezeroaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_bezeroak_zerrendatu()
    {
        Bezeroa::factory()->count(3)->create();
        $response = $this->getJson('/api/bezeroak');
        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_bezeroa_sortu()
    {
        $data = [
            'izena' => 'Jon',
            'abizenak' => 'Doe',
            'telefonoa' => '123456789',
            'email' => 'jon@example.com',
            'etxeko_bezeroa' => true,
        ];
        $response = $this->postJson('/api/bezeroak', $data);
        $response->assertStatus(201)->assertJsonFragment(['izena' => 'Jon']);
        $this->assertDatabaseHas('bezeroak', ['email' => 'jon@example.com']);
    }

    public function test_bezeroa_ikusi()
    {
        $bezeroa = Bezeroa::factory()->create();
        $response = $this->getJson("/api/bezeroak/{$bezeroa->id}");
        $response->assertStatus(200)->assertJson(['id' => $bezeroa->id]);
    }

    public function test_bezeroa_ezeztatu()
    {
        $bezeroa = Bezeroa::factory()->create();
        $response = $this->deleteJson("/api/bezeroak/{$bezeroa->id}");
        $response->assertStatus(204);
        $this->assertSoftDeleted('bezeroak', ['id' => $bezeroa->id]);
    }
}