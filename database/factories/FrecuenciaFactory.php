<?php

namespace Database\Factories;
use App\Models\Frecuencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrecuenciaFactory extends Factory
{
    protected $model = Frecuencia::class;

    public function definition()
    {
        return [
            'dia' => $this->faker->dayOfWeek, 
            'estado' => $this->faker->boolean, 
            'idDetalleRuta' => 1,
        ];
    }
}
