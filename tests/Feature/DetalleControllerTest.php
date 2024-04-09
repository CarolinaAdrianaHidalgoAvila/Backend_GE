<?php

namespace Tests\Feature;

use Database\Factories\DetalleRutaFactory;
use Database\Factories\FrecuenciaFactory;
use Database\Factories\RutaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\DetalleRuta;



class DetalleControllerTest extends TestCase
{



    public function testGetFrecuencias()
    {

        $response = $this->get("/api/detalleRuta/1/frecuencias");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'dia',
                'estado',
                'idDetalleRuta',
            ],
        ]);
    }

    public function testGetWithInvalidId()
    {
        $invalidId = 9999;
        $response = $this->get("/api/ruta/{$invalidId}/detalleRuta");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El registro no fue encontrado',
            'success' => false,
        ]);
    }
}
