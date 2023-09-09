<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\KmlContenedor;
Use Log;
class kmlContenedorController extends Controller
{
    public function getAll(){
      $kmlContenedor= KmlContenedor::get()->toArray();
        return response()->json($kmlContenedor, 200);
      }
  
      public function create(Request $request){

        if ($request->hasFile('file')) {
          $file = $request->file('file');
  
          // Obtener el nombre y la ruta del archivo
          $nombreArchivo = $file->getClientOriginalName();
          $rutaArchivo = $file->store('kmls');
  
          // Crear un nuevo registro en la base de datos
          $kmlContenedor = new KmlContenedor();
          $kmlContenedor->nombre_archivo = $nombreArchivo;
          $kmlContenedor->path = $rutaArchivo;
          $kmlContenedor->fecha_carga = now();
          $kmlContenedor->save();
  
          return response()->json([
              'message' => 'Documento cargado exitosamente.',
              'success' => true
          ], 200);
      }
  
      return response()->json([
          'message' => 'Ningún archivo cargado.',
          'success' => false
      ], 400);
      }
  
      public function delete($id){
        $res = KmlContenedor::find($id)->delete();
        return response()->json([
            'message' => "Eliminado con éxito",
            'success' => true
        ], 200);
      }
  
      public function get($id){
        $data = KmlContenedor::find($id);
        return response()->json($data, 200);
      }
}
