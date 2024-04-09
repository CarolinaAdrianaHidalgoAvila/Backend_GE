<?php

namespace Database\Factories;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

class RutaFactory extends Factory
{
    protected $model = Ruta::class;

    public function definition()
    {
        return [
            'id'=>1,
            'codigo_carro' => $this->faker->unique()->randomNumber(),
            'nombre_ruta' => $this->faker->sentence,
            'latitud_inicio' => $this->faker->latitude,
            'longitud_inicio' => $this->faker->longitude,
            'latitud_fin' => $this->faker->latitude,
            'longitud_fin' => $this->faker->longitude,
            'tiene_saltos' => $this->faker->boolean,
            'fecha_modificacion' => now(),
            'idKmlRuta' => $this->faker->unique()->randomNumber(),
        ];
    }
}
