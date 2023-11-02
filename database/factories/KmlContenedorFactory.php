<?php

namespace Database\Factories;

use App\Models\KmlContenedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class KmlContenedorFactory extends Factory
{
    protected $model = KmlContenedor::class;

    public function definition()
    {
        return [
            'nombre_archivo' => $this->faker->word,
            'path' => $this->faker->word,
            'fecha_carga' => $this->faker->date,
        ];
    }
}
