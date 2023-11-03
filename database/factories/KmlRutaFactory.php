<?php

namespace Database\Factories;

use App\Models\KmlRuta;
use Illuminate\Database\Eloquent\Factories\Factory;

class KmlRutaFactory extends Factory
{
    protected $model = KmlRuta::class;

    public function definition()
    {
        return [
            'nombre_archivo' => $this->faker->word,
            'path' => $this->faker->word,
            'fecha_carga' => $this->faker->date,
        ];
    }
}
