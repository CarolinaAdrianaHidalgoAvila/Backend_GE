<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Factories\ContenedorFactory; 
use Tests\TestCase;
use App\Models\Contenedor;
Use App\Models\KmlContenedor;

class ContenedorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteWithValidData()
    {
        $contenedor = ContenedorFactory::new()->create();
        $response = $this->delete("/api/kmlContenedor/{$contenedor->idKmlContenedor}/contenedor/{$contenedor->id}");
        $response->assertStatus(200);
    }

    public function testDeleteWithInvalidKml()
    {
        $response = $this->delete("/api/kmlContenedor/1/contenedor/999");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El contenedor no fue encontrado',
        ]);
    }

    public function testGetWithValidData()
    {
        $contenedor = Contenedor::factory()->create();
        $response = $this->get("/api/kmlContenedor/{$contenedor->id}/contenedor/{$contenedor->id}");
        $response->assertStatus(200);
    }

    public function testGetWithNoRecords()
    {
        // Caso 200: Si no tienes registros, asegúrate de que la respuesta sea un array vacío.
        $response = $this->get("/api/kmlContenedor/1/contenedor/1");
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testUpdateWithValidData()
    {
        // Caso 200: Supongamos que tienes al menos un registro en la base de datos.
        $contenedor = Contenedor::factory()->create();
        $contenedorData = Contenedor::factory()->make(); // Genera datos válidos.
        $response = $this->put("/api/kmlContenedor/{$contenedor->idKmlContenedor}/contenedor/{$contenedor->id}", $contenedorData->toArray());
        $response->assertStatus(200);
    }

    public function testUpdateWithMissingData()
    {
        // Caso 400: Prueba la actualización con datos faltantes (debe fallar).
        $contenedor = Contenedor::factory()->create();
        $response = $this->put("/api/kmlContenedor/{$contenedor->idKmlContenedor}/contenedor/{$contenedor->id}", []);
        $response->assertStatus(400);
    }

    public function testUpdateWithInvalidContenedor()
    {
        $contenedor = Contenedor::factory()->create();
        $contenedorData = Contenedor::factory()->make(); // Genera datos válidos.
        $response = $this->put("/api/kmlContenedor/1/contenedor/999", $contenedorData->toArray());
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El contenedor no fue encontrado',
        ]);
    }
}
