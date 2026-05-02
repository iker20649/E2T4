<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hitzordua;
use App\Models\Bezeroa;
use App\Models\Ikaslea;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HitzorduaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hitzordua_sortu()
    {
        $bezeroa = Bezeroa::factory()->create();
        $ikaslea = Ikaslea::factory()->create();

        $data = [
            'lekua' => 1,
            'data' => '2026-05-10',
            'hasiera_ordua' => '10:00',
            'bukaera_ordua' => '11:00',
            'bezero_id' => $bezeroa->id,
            'ikasle_id' => $ikaslea->id,
        ];
        $response = $this->postJson('/api/hitzorduak', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('hitzorduak', ['lekua' => 1]);
    }

    public function test_gainezkatzeak_422_itzultzen_du()
    {
        $bezeroa = Bezeroa::factory()->create();
        Hitzordua::factory()->create([
            'lekua' => 1,
            'data' => '2026-05-10',
            'hasiera_ordua' => '10:00',
            'bukaera_ordua' => '11:00',
            'bezero_id' => $bezeroa->id,
        ]);

        $data = [
            'lekua' => 1,
            'data' => '2026-05-10',
            'hasiera_ordua' => '10:30',
            'bukaera_ordua' => '11:30',
            'bezero_id' => $bezeroa->id,
        ];
        $response = $this->postJson('/api/hitzorduak', $data);
        $response->assertStatus(422)->assertJson(['error' => 'Leku hori jadanik hartuta dago ordu tarte horretan']);
    }
}