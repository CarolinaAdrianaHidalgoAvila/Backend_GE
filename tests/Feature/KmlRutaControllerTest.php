<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\KmlRuta;

class KmlRutaControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testGetAll()
    {
        $response = $this->get('/api/kmlRuta');
        $response->assertStatus(200);
    }
    public function testCreateWithValidFile()
    {
        Storage::fake('kmls');
        $file = UploadedFile::fake()->create('document.kml', 100);

        $kmlRuta = KmlRuta::factory()->create();

        $response = $this->post('/api/kmlRuta', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Archivo cargado .',
            'success' => true,
        ]);
    }

    public function testCreateWithNoFile()
    {
        $response = $this->post('/api/kmlRuta', []);
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Ningún archivo cargado.',
            'success' => false,
        ]);
    }

    public function testDelete()
    {
        $kmlRuta = KmlRuta::factory()->create();

        $response = $this->delete("/api/kmlRuta/{$kmlRuta->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Eliminado con éxito',
            'success' => true,
        ]);
    }

    public function testDeleteNonExistentRecord()
    {
        $response = $this->delete("/api/kmlRuta/999");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El registro no fue encontrado',
            'success' => false,
        ]);
    }

    public function testGet()
    {
        $kmlRuta = KmlRuta::factory()->create();

        $response = $this->get("/api/kmlRuta/{$kmlRuta->id}");
        $response->assertStatus(200);
    }

    public function testGetWithInvalidId()
    {
        $response = $this->get("/api/kmlRuta/9999");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El registro no fue encontrado',
            'success' => false,
        ]);
    }
}
