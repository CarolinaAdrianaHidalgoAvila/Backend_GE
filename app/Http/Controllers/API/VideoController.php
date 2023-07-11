<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Video;
Use Log;

class VideoController extends Controller
{
    // https://carbon.now.sh/
    public function getAll(){
      $data = video::get();
      return response()->json($data, 200);
    }

    public function create(Request $request){
      $data['titulo'] = $request['titulo'];
      $data['url_contenido'] = $request['url_contenido'];
      $data['fecha_carga'] = $request['fecha_carga'];
      $data['fecha_modificacion'] = $request['fecha_modificacion'];
      video::create($data);
      return response()->json([
          'message' => "Successfully created",
          'success' => true
      ], 200);
    }

    public function delete($id){
      $res = video::find($id)->delete();
      return response()->json([
          'message' => "Successfully deleted",
          'success' => true
      ], 200);
    }

    public function get($id){
      $data = video::find($id);
      return response()->json($data, 200);
    }

    public function update(Request $request,$id){
      $data['titulo'] = $request['titulo'];
      $data['url_contenido'] = $request['url_contenido'];
      $data['fecha_modificacion'] = $request['fecha_modificacion'];
      video::find($id)->update($data);
      return response()->json([
          'message' => "Successfully updated",
          'success' => true
      ], 200);
    }
}

