<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ikaslea;
use App\Models\Taldea;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IkasleaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ikasleak_zerrendatu()
    {
        Ikaslea::factory()->count(3)->create();

        $response = $this->getJson('/api/ikasleak');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function ikaslea_sortu()
    {
        $taldea = Taldea::factory()->create();

        $data = [
            'izena' => 'Mikel',
            'abizena' => 'Irazabal',
            'talde_id' => $taldea->id,
        ];

        $response = $this->postJson('/api/ikasleak', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['izena' => 'Mikel']);

        $this->assertDatabaseHas('ikasleak', [
            'izena' => 'Mikel',
            'abizena' => 'Irazabal',
            'talde_id' => $taldea->id,
        ]);
    }

    /** @test */
    public function ikaslea_ikusi()
    {
        $ikaslea = Ikaslea::factory()->create();

        $response = $this->getJson("/api/ikasleak/{$ikaslea->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $ikaslea->id]);
    }

    /** @test */
    public function ikaslea_eguneratu()
    {
        $ikaslea = Ikaslea::factory()->create();

        $data = ['izena' => 'Ane', 'abizena' => 'Lopez'];

        $response = $this->putJson("/api/ikasleak/{$ikaslea->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['izena' => 'Ane']);

        $this->assertDatabaseHas('ikasleak', [
            'id' => $ikaslea->id,
            'izena' => 'Ane',
            'abizena' => 'Lopez',
        ]);
    }

    /** @test */
    public function ikaslea_ezeztatu()
    {
        $ikaslea = Ikaslea::factory()->create();

        $response = $this->deleteJson("/api/ikasleak/{$ikaslea->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('ikasleak', ['id' => $ikaslea->id]);
    }

    /** @test */
    public function ezin_duzu_ikaslea_sortu_talde_gabe()
    {
        $data = [
            'izena' => 'Mikel',
            'abizena' => 'Irazabal',
            // talde_id falta
        ];

        $response = $this->postJson('/api/ikasleak', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['talde_id']);
    }
}