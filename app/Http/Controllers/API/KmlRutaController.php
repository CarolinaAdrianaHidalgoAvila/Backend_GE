<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\KmlRuta;

class KmlRutaController extends Controller
{
    public function getAll(){
        $kmlContenedor= KmlRuta::get()->toArray();
        return response()->json($kmlContenedor, 200);
    }
    
    public function create(Request $request){
  
          if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Obtener el nombre y la ruta del archivo
            $nombreArchivo = $file->getClientOriginalName();
            $rutaArchivo = $file->store('kmls');
    
            // Crear un nuevo registro en la base de datos
            $kmlContenedor = new KmlRuta();
            $kmlContenedor->nombre_archivo = $nombreArchivo;
            $kmlContenedor->path = $rutaArchivo;
            $kmlContenedor->fecha_carga = now();
            $kmlContenedor->save();
    
            return response()->json([
                'message' => 'Archivo cargado .',
                'success' => true
            ], 200);
        }
    
        return response()->json([
            'message' => 'Ningún archivo cargado.',
            'success' => false
        ], 400);
        }
    
        public function delete($id)
        {
            $kmlContenedor = KmlRuta::find($id);
      
            if (!$kmlContenedor) {
                return response()->json([
                    'message' => "El registro no fue encontrado",
                    'success' => false
                ], 404);
            }
      
            $kmlContenedor->delete();
      
            return response()->json([
                'message' => "Eliminado con éxito",
                'success' => true
            ], 200);
        }
        public function get($id)
        {
          $kmlContenedor = KmlRuta::find($id);
      
          if (!$kmlContenedor) {
              return response()->json([
                  'message' => "El registro no fue encontrado",
                  'success' => false
              ], 404);
          }
      
          return response()->json($kmlContenedor, 200);
        }
      
}
