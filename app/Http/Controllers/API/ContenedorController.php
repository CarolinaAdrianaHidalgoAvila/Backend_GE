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
        foreach ($xml->Document->Folder as $folder) {
            foreach ($folder->Placemark as $placemark) {
                $nombre_contenedor = (string) $placemark->name;
                $coordinates = explode(',', (string) $placemark->Point->coordinates);
                $longitud = (float) $coordinates[0];
                $latitud = (float) $coordinates[1];
        
                Contenedor::create([
                    'nombre_contenedor' => $nombre_contenedor,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'tipo' => 'contenedor',
                    'fecha_modificacion' => now(),
                    'idKmlContenedor' => $idKmlContenedor,
                ]);
            }
        
            foreach ($folder->Folder->Placemark as $nestedPlacemark) {
                $nombre_contenedor = (string) $nestedPlacemark->name;
                $coordinates = explode(',', (string) $nestedPlacemark->Point->coordinates);
                $longitud = (float) $coordinates[0];
                $latitud = (float) $coordinates[1];
        
                Contenedor::create([
                    'nombre_contenedor' => $nombre_contenedor,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'tipo' => 'contenedor',
                    'fecha_modificacion' => now(),
                    'idKmlContenedor' => $idKmlContenedor,
                ]);
            }
        }
        
        // Retornar una respuesta indicando que el procesamiento fue exitoso
        return response()->json([
            'message' => 'Datos del archivo KML procesados y guardados correctamente',
            'success' => true,
        ], 200);
    }

    public function delete($idKmlContenedor, $id)
    {
        $contenedor = Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->first();

    if (!$contenedor) {
        return response()->json([
            'message' => 'El contenedor no fue encontrado', 
            'success' => false], 
            404);
    }
    $res = $contenedor->delete();
    if (!$res) {
        return response()->json([
            'message' => 'Error al eliminar el contenedor', 
            'success' => false], 
        400);
    }

    return response()->json([
        'message' => 'Eliminado con éxito', 
        'success' => true], 
    200);
    }


    public function get($idKmlContenedor,$id){
      $data = Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->first();
      return response()->json($data, 200);
    }

    public function update(Request $request, $idKmlContenedor, $id)
    {
        $contenedor = Contenedor::where('id', $id)->where('idKmlContenedor', $idKmlContenedor)->first();
        if (!$contenedor) {
            return response()->json([
                'message' => 'El contenedor no fue encontrado',
                'success' => false
            ], 404);
        }
    
        $data['nombre_contenedor'] = $request['nombre_contenedor'];
        $data['latitud'] = $request['latitud'];
        $data['longitud'] = $request['longitud'];
        $data['fecha_modificacion'] = $request['fecha_modificacion'];
        $data['tipo'] = $request['tipo'];

        if (empty($data['nombre_contenedor']) || is_null($data['latitud']) || is_null($data['longitud'])) {
            return response()->json([
                'message' => 'Error al actualizar el contenedor', 
                'success' => false],
             400);
        }
    
        $contenedor->update($data);

    
        return response()->json([
            'message' => 'Successfully updated', 
            'success' => true], 200);
        }
    
}
