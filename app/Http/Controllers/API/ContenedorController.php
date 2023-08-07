<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Contenedor;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
Use App\Models\KmlContenedor;
Use Log;

class ContenedorController extends Controller
{
    // https://carbon.now.sh/
    public function getAll($idKmlContenedor){
      $data = Contenedor::where('idKmlContenedor', $idKmlContenedor)->get();
      return response()->json($data, 200);
    }
    public function create(Request $request,$idKmlContenedor)
    {
        // Obtener el registro del documento KML desde la base de datos
        $documentoKML = KmlContenedor::find($idKmlContenedor);
         // Validar que el documento KML existe
         if (!$documentoKML) {
            return response()->json(['message' => 'El documento KML no existe'], 404);
        }
        // Obtener el contenido del archivo KML desde el almacenamiento (por ejemplo, en el disco público)
        $contenidoKML = Storage::get($documentoKML->path);
        // Procesar el contenido del archivo KML con SimpleXMLElement
        $xml = new SimpleXMLElement($contenidoKML);
        $namespaces = $xml->getNamespaces(true);
        // Iterar sobre los elementos del archivo KML y guardar los datos en la tabla "contenedor"
        foreach ($xml->Document->Folder->Folder->Placemark as $placemark){
            $nombre_contenedor = (string) $placemark->name;
            $coordinates = explode(',', (string) $placemark->Point->coordinates);
            $longitud = (float) $coordinates[0];
            $latitud = (float) $coordinates[1];
            // Guardar los datos en la tabla "contenedor" usando el modelo Eloquent
            Contenedor::create([
                'nombre_contenedor' => $nombre_contenedor,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'tipo' => 'contenedor',
                'fecha_modificacion' => now(),
                'idKmlContenedor' => $idKmlContenedor,
            ]);
        }
        // Retornar una respuesta indicando que el procesamiento fue exitoso
        return response()->json([
            'message' => 'Datos del archivo KML procesados y guardados correctamente',
            'success' => true,
        ], 200);
    }

    public function delete($idKmlContenedor,$id){
      $res = Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->delete();
      return response()->json([
          'message' => "Successfully deleted",
          'success' => true
      ], 200);
    }

    public function get($idKmlContenedor,$id){
      $data = Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->first();
      return response()->json($data, 200);
    }

    public function update(Request $request, $idKmlContenedor, $id)
    {
        $data['nombre_contenedor'] = $request['nombre_contenedor'];
        $data['latitud'] = $request['latitud'];
        $data['longitud'] = $request['longitud'];
        $data['fecha_modificacion'] = $request['fecha_modificacion'];
        $data['tipo'] = $request['tipo'];
    
        // Buscar el registro en la tabla "contenedor" por su id y actualizar los campos
        Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->update($data);
    
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
}
