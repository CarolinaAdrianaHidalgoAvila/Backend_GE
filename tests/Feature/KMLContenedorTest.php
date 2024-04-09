<?php


namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Contenedor;
class KMLContenedorTest extends TestCase
{
    public function testGetAll()
    {
    
        $response = $this->get('/api/kmlContenedor');
        $response->assertStatus(200);
    }
    public function testCreateWithValidFile()
    {
        Storage::fake('kmls');
        $file = UploadedFile::fake()->create('document.kml', 100); 
        $response = $this->post('/api/kmlContenedor', [
            'file' => $file,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Documento cargado exitosamente.',
            'success' => true,
        ]);
    }
    public function testCreateWithNoFile()
    {
        $response = $this->post('/api/kmlContenedor', []);
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Ningún archivo cargado.',
            'success' => false,
        ]);
    }
    public function testDelete()
    {
        $id = 5;
        $response = $this->delete("/api/kmlContenedor/{$id}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Eliminado con éxito',
            'success' => true,
        ]);
    }
    public function testDeleteNonExistentRecord()
    {
        $nonExistentId = 999;
        $response = $this->delete("/api/kmlContenedor/{$nonExistentId}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El registro no fue encontrado',
            'success' => false,
        ]);
    }
    public function testGet()
    {
        $id = 4; 
        $response = $this->get("/api/kmlContenedor/{$id}");
        $response->assertStatus(200);
    }
    public function testGetWithInvalidId()
    {
        $invalidId = 9999; 
        $response = $this->get("/api/kmlContenedor/{$invalidId}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El registro no fue encontrado',
            'success' => false,
        ]);
    }


}
