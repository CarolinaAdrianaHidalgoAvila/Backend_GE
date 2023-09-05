<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Salto;
use Illuminate\Http\Request;
Use App\Models\Ruta;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
Use App\Models\KmlRuta;

class RutaController extends Controller
{
    public function getAll($idKmlRuta){
        $data = Ruta::where('idKmlRuta', $idKmlRuta)->get();
        return response()->json($data, 200);
    }
    public function create(Request $request,$idKmlRuta)
      {
          $documentoKML = KmlRuta::find($idKmlRuta);
           if (!$documentoKML) {
              return response()->json(['message' => 'El documento KML no existe'], 404);
          }
          $contenidoKML = Storage::get($documentoKML->path);
          $xml = new SimpleXMLElement($contenidoKML);
          $namespaces = $xml->getNamespaces(true);
          // Iterar sobre los elementos del archivo KML y guardar los datos en la tabla "ruta"
          foreach ($xml->Document->Folder->Folder->Folder->Folder as $folder) {
            $codigo_carro = (string) $folder->name;
            $nombre_ruta = (string) $folder->Folder->name;
            // Verificar si existe el elemento <Point> dentro de <Placemark> para las coordenadas de inicio
if (isset($folder->Folder->Placemark->Point)) {
    $coordinatesInicio = explode(',', (string) $folder->Folder->Placemark->Point->coordinates);

    if (count($coordinatesInicio) >= 2) {
        $latitud_inicio = (float) $coordinatesInicio[1];
        $longitud_inicio = (float) $coordinatesInicio[0];
    } else {
        // Manejo de error o valor predeterminado en caso de que falten datos
        $latitud_inicio = 0.0; // Valor predeterminado
        $longitud_inicio = 0.0; // Valor predeterminado
    }
} else {
    // Manejo de error o valor predeterminado en caso de que no exista <Point>
    $latitud_inicio = 0.0; // Valor predeterminado
    $longitud_inicio = 0.0; // Valor predeterminado
}

// Verificar si existe el elemento <Point> dentro de <Placemark> para las coordenadas de fin
if (isset($folder->Folder->Placemark->Point)) {
    $coordinatesFin = explode(',', (string) $folder->Folder->Placemark->Point->coordinates);

    if (count($coordinatesFin) >= 2) {
        $latitud_fin = (float) $coordinatesFin[1];
        $longitud_fin = (float) $coordinatesFin[0];
    } else {
        // Manejo de error o valor predeterminado en caso de que falten datos
        $latitud_fin = 0.0; // Valor predeterminado
        $longitud_fin = 0.0; // Valor predeterminado
    }
} else {
    // Manejo de error o valor predeterminado en caso de que no exista <Point>
    $latitud_fin = 0.0; // Valor predeterminado
    $longitud_fin = 0.0; // Valor predeterminado
}

if (is_array($folder->Folder->Placemark) || is_countable($folder->Folder->Placemark)) {
    $tiene_saltos = count($folder->Folder->Placemark) > 3; // Si hay más de dos Placemark, hay saltos
} else {
    $tiene_saltos = false; // No hay Placemark o no es un array/countable
}


            Ruta::create([
                'codigo_carro' => $codigo_carro,
                'nombre_ruta' => $nombre_ruta,
                'latitud_inicio' => $latitud_inicio,
                'longitud_inicio' => $longitud_inicio,
                'latitud_fin' => $latitud_fin,
                'longitud_fin' => $longitud_fin,
                'tiene_saltos' => $tiene_saltos,
                'fecha_modificacion' => now(),
                'idKmlRuta' => $idKmlRuta,
                ]);

          }
          
          return response()->json([
              'message' => 'Datos del archivo KML procesados y guardados correctamente',
              'success' => true,
          ], 200);
      }
    public function delete($idKmlRuta,$id){
        $res = Ruta::where('id', $id)->where('idKmlRuta', $idKmlRuta)->delete();
        return response()->json([
            'message' => "Eliminado exitosamente",
            'success' => true
        ], 200);
      }
  
      public function get($idKmlRuta,$id){
        $data = Ruta::where('id', $id)->where('idKmlRuta', $idKmlRuta)->first();
        return response()->json($data, 200);
      }
    public function update(Request $request, $idKmlRuta, $id)
    {
        $data['nombre_ruta'] = $request['nombre_ruta'];
        $data['codigo_carro'] = $request['codigo_carro'];
        $data['latitud_inicio'] = $request['latitud_inicio'];
        $data['longitud_inicio'] = $request['longitud_inicio'];
        $data['latitud_fin'] = $request['latitud_fin'];
        $data['longitud_fin'] = $request['longitud_fin'];
        $data['fecha_modificacion'] = $request['fecha_modificacion'];
        $data['tiene_saltos'] = $request['tiene_saltos'];
    
        Ruta::where('id', $id)->where('idKmlRuta', $idKmlRuta)->update($data);
    
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
    public function updateSalto(Request $request, $idRuta,$id)
    {
        $data['nombre_salto'] = $request['nombre_salto'];
        $data['inicio_latitud'] = $request['inicio_latitud'];
        $data['inicio_longitud'] = $request['inicio_longitud'];
        $data['fin_latitud'] = $request['fin_latitud'];
        $data['fin_longitud'] = $request['fin_longitud'];
        Salto::where('id', $id)->where('idRuta', $idRuta)->update($data);
    
        return response()->json([
            'message' => "Successfully updated",
            'success' => true
        ], 200);
    }
}
