<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Txanda;
use App\Models\Ikaslea;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TxandaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_txanda_sortu()
    {
        $ikaslea = Ikaslea::factory()->create();
        $data = [
            'ikasle_id' => $ikaslea->id,
            'data' => '2026-05-10',
            'hasiera_ordua' => '09:00',
            'bukaera_ordua' => '13:00',
            'rola' => 'harrera',
        ];
        $response = $this->postJson('/api/txandak', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('txandak', ['rola' => 'harrera']);
    }

    public function test_txanda_gainezkatzeak_errorea()
    {
        $ikaslea = Ikaslea::factory()->create();
        Txanda::factory()->create([
            'ikasle_id' => $ikaslea->id,
            'data' => '2026-05-10',
            'hasiera_ordua' => '09:00',
            'bukaera_ordua' => '13:00',
        ]);
        $data = [
            'ikasle_id' => $ikaslea->id,
            'data' => '2026-05-10',
            'hasiera_ordua' => '12:00',
            'bukaera_ordua' => '14:00',
            'rola' => 'harrera',
        ];
        $response = $this->postJson('/api/txandak', $data);
        $response->assertStatus(422)->assertJson(['error' => 'Ikasleak badu jadanik txanda gainezkatzen dena']);
    }
}