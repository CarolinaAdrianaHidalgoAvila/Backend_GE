<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contenedor;
use App\Models\KmlContenedor;
class ContenedorFactory extends Factory
{
    protected $model = Contenedor::class;

    public function definition()
    {
        return [
            'nombre_contenedor' => $this->faker->text(30),
            'latitud' => $this->faker->randomFloat(6, -90, 90), 
            'longitud' => $this->faker->randomFloat(6, -180, 180), 
            'fecha_modificacion' => now(),
            'tipo' => 'contenedor',
            'idKmlContenedor' => KmlContenedor::factory() 
        ];
    }
}
