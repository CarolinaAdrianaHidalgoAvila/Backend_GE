<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Video;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'url_contenido' => $this->faker->url,
            // Otras propiedades del modelo Video
        ];
    }
}
