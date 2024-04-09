<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Video;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllVideos()
    {
        // Crear registros de prueba utilizando la factory
        Video::factory()->create([
            'titulo' => 'Video 1',
            'url_contenido' => 'url1.mp4',
        ]);

        Video::factory()->create([
            'titulo' => 'Video 2',
            'url_contenido' => 'url2.mp4',
        ]);

        // Realizar la solicitud GET a la ruta '/api/videos'
        $response = $this->get('/api/video');

        // Verificar que la solicitud se haya realizado con éxito (código de respuesta 200)
        $response->assertStatus(200);

        // Verificar que la respuesta sea en formato JSON (sin paréntesis)
        $response->assertJson([
            [
                'titulo' => 'Video 1',
                'url_contenido' => 'url1.mp4',
            ],
            [
                'titulo' => 'Video 2',
                'url_contenido' => 'url2.mp4',
            ],
        ]);

        // Verificar que los registros de prueba estén presentes en la respuesta
        $response->assertJsonFragment(['titulo' => 'Video 1']);
        $response->assertJsonFragment(['titulo' => 'Video 2']);
    }
    public function testCreateVideo()
{
    $response = $this->post('/api/video', [
        'titulo' => 'Nuevo Video',
        'url_contenido' => 'nuevo_video.mp4',
        'fecha_carga' => '2023-09-08',
        'fecha_modificacion' => '2023-09-08',
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Successfully created',
        'success' => true,
    ]);
}
public function testGetVideoById(){
    $video = Video::factory()->create();
    $videoId = $video->id;
    $response = $this->get("/api/video/{$videoId}");
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => $videoId]);
    $response->assertJsonFragment(['titulo' => $video->titulo]);
    $response->assertJsonFragment(['url_contenido' => $video->url_contenido]);
}
public function testUpdateVideoById()
{
    $video = Video::factory()->create();
    $videoId = $video->id;
    $newData = [
        'titulo' => 'Nuevo Título',
        'url_contenido' => 'nueva_url.mp4',
        'fecha_modificacion' => now()->toDateTimeString(),
    ];
    $response = $this->put("/api/video/{$videoId}", $newData);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Successfully updated',
        'success' => true,
    ]);
    $updatedVideo = Video::find($videoId);
    $this->assertEquals($newData['titulo'], $updatedVideo->titulo);
    $this->assertEquals($newData['url_contenido'], $updatedVideo->url_contenido);
}

    public function testUpdateVideoWithMissingData(){
        $video = Video::factory()->create();
        $videoData = [
            'titulo' => 'Nuevo Título del Video',
        ];
        $response = $this->put("/api/video/{$video->id}", $videoData);
        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
        $response->assertJsonFragment(['message' => 'Los datos proporcionados son inválidos']);
    }
    public function testCreateVideoWithMissingData(){
        $videoData = [
            'titulo' => 'Nuevo Título del Video',
        ];
        $response = $this->post("/api/video", $videoData);
        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
        $response->assertJsonFragment(['message' => 'Los datos proporcionados son inválidos']);
    }
    public function testDeleteNonExistentVideo()
{
    $nonExistentVideoId = 999; 
    $response = $this->delete("/api/video/{$nonExistentVideoId}");
    $response->assertStatus(404);
    $response->assertJson(['success' => false]);
    $response->assertJsonFragment(['message' => 'El video no fue encontrado']);
}

  public function testGetNonExistentVideo()
  {
      $nonExistentVideoId = 999;
      $response = $this->get("/api/video/{$nonExistentVideoId}");
      $response->assertStatus(404);
      $response->assertJson(['success' => false]);
      $response->assertJsonFragment(['message' => 'El video no fue encontrado']);
  }
  public function testUpdateVideoNotFound()
    {
        $nonExistentVideoId = 999; 
        $response = $this->put("/api/video/{$nonExistentVideoId}", []);
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'El video no fue encontrado',
            'success' => false,
        ]);
    }
}
