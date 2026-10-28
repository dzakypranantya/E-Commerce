<?php

namespace Tests\Feature;

use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_the_service_list(): void
    {
        $this->get('/')->assertRedirect('/layanan');
    }

    public function test_service_list_page_loads(): void
    {
        $this->get('/layanan')->assertOk();
    }

    public function test_create_form_loads(): void
    {
        $this->get('/layanan/create')->assertOk();
    }

    public function test_api_can_create_a_service(): void
    {
        $response = $this->postJson('/api/layanan', [
            'nama_ekspedisi' => 'Kurir Baru',
            'jenis_layanan' => 'Reguler',
            'zonasi' => 'Zona 1',
            'tarif' => 10000,
            'estimasi' => '2-3 Hari',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.nama_ekspedisi', 'KURIR BARU');

        $this->assertDatabaseHas('layanan', ['nama_ekspedisi' => 'KURIR BARU']);
    }

    public function test_api_rejects_invalid_service_data(): void
    {
        $this->postJson('/api/layanan', [
            'nama_ekspedisi' => 'Kurir',
            'jenis_layanan' => 'Tidak dikenal',
            'zonasi' => 'Zona 1',
            'tarif' => -1,
            'estimasi' => 'Besok',
        ])->assertUnprocessable();
    }

    public function test_api_can_show_update_and_delete_a_service(): void
    {
        $layanan = Layanan::create([
            'nama_ekspedisi' => 'JNE',
            'jenis_layanan' => 'Reguler',
            'zonasi' => 'Zona 1',
            'tarif' => 9000,
            'estimasi' => '2-3 Hari',
            'status' => 'ON',
        ]);

        $this->getJson("/api/layanan/{$layanan->id}")
            ->assertOk()
            ->assertJsonPath('data.nama_ekspedisi', 'JNE');

        $this->patchJson("/api/layanan/{$layanan->id}", ['nama_ekspedisi' => 'Kurir Baru'])
            ->assertOk()
            ->assertJsonPath('data.nama_ekspedisi', 'KURIR BARU')
            ->assertJsonPath('data.tarif', 9000);

        $this->deleteJson("/api/layanan/{$layanan->id}")
            ->assertOk();

        $this->assertDatabaseMissing('layanan', ['id' => $layanan->id]);
    }

    public function test_web_form_can_create_a_service(): void
    {
        $this->post('/layanan', [
            'nama_ekspedisi' => 'Kurir Web',
            'jenis_layanan' => 'Cargo',
            'zonasi' => 'Zona 2',
            'tarif' => 15000,
            'estimasi' => '3-4 Hari',
            'status' => 'ON',
        ])->assertRedirect('/layanan');

        $this->assertDatabaseHas('layanan', ['nama_ekspedisi' => 'KURIR WEB']);
    }

    public function test_web_can_update_and_delete_a_service(): void
    {
        $layanan = Layanan::create([
            'nama_ekspedisi' => 'JNE',
            'jenis_layanan' => 'Reguler',
            'zonasi' => 'Zona 1',
            'tarif' => 9000,
            'estimasi' => '2-3 Hari',
            'status' => 'ON',
        ]);

        $this->put("/layanan/{$layanan->id}", [
            'nama_ekspedisi' => 'Kurir Web',
            'jenis_layanan' => 'Cargo',
            'zonasi' => 'Zona 2',
            'tarif' => 15000,
            'estimasi' => '3-4 Hari',
            'status' => 'OFF',
        ])->assertRedirect('/layanan');

        $this->assertDatabaseHas('layanan', ['id' => $layanan->id, 'nama_ekspedisi' => 'KURIR WEB']);

        $this->delete("/layanan/{$layanan->id}")->assertRedirect('/layanan');
        $this->assertDatabaseMissing('layanan', ['id' => $layanan->id]);
    }
}
