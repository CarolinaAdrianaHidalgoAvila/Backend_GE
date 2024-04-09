<?php

namespace Database\Factories;

use App\Models\DetalleRuta;
use App\Models\Ruta; 
use Illuminate\Database\Eloquent\Factories\Factory;
class DetalleRutaFactory extends Factory
{
    protected $model = DetalleRuta::class;

    public function definition()
    {
        return [
            'codigo_vehiculo' => $this->faker->unique()->randomNumber(),
            'nombre_ruta' => $this->faker->sentence,
            'distrito' => $this->faker->word,
            'hora_inicio' => $this->faker->time,
            'hora_fin' => $this->faker->time,
            'peso' => $this->faker->randomFloat(2, 100, 1000),
            'distancia' => $this->faker->randomFloat(2, 10, 100),
            'observacion' => $this->faker->sentence,
            'fecha_modificacion' => now(),
            'idRuta' => 1,
        ];
    }
}