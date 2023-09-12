<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Salto;
use Illuminate\Http\Request;
Use App\Models\Ruta;
Use App\Models\PuntoLinea;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
Use App\Models\KmlRuta;

class RutaController extends Controller
{
    public function getAll($idKmlRuta){
        $data = Ruta::where('idKmlRuta', $idKmlRuta)->get();
        return response()->json($data, 200);
    }

    private function savePointsToPuntoLinea($idRuta, $coordinates)
{
    // Dividir las coordenadas en un array
    $coordinatesArray = explode(' ', trim($coordinates));
    $orden = 1; // Inicializar el orden en 1

    // Iterar a través de las coordenadas y crear los puntos de la línea en la tabla PuntoLinea
    foreach ($coordinatesArray as $coord) {
        list($longitude, $latitude, $altitude) = explode(',', $coord);

        // Crear un nuevo punto de línea en la tabla PuntoLinea
        PuntoLinea::create([
            'idRuta' => $idRuta, // ID de la Ruta
            'longitud' => $longitude,
            'latitud' => $latitude,
            'orden' => $orden,
        ]);

        $orden++; // Incrementar el orden para el siguiente punto
    }
}
private function exploreLineStringsInFolders($folders, $idRuta)
{
    foreach ($folders as $folder) {
        // Verificar si el elemento actual es un Folder
        if ($folder->getName() == 'Folder') {
            // Llamar recursivamente a la función para explorar subfolders
            $this->exploreLineStringsInFolders($folder->Folder, $idRuta);
        }
        // Verificar si el elemento actual tiene un LineString
        elseif ($folder->getName() == 'Placemark' && isset($folder->LineString->coordinates)) {
            $coordinates = (string) $folder->LineString->coordinates;
            
            // Llamar a la función para guardar los puntos en la tabla PuntoLinea
            $this->savePointsToPuntoLinea($idRuta, $coordinates);
        }
    }
}
    public function create($idKmlRuta)
{
    $documentoKML = KmlRuta::find($idKmlRuta);
    if (!$documentoKML) {
        return response()->json(['message' => 'El documento KML no existe'], 404);
    }
    $contenidoKML = Storage::get($documentoKML->path);
    $xml = new SimpleXMLElement($contenidoKML);
    $namespaces = $xml->getNamespaces(true);

    // Iterar sobre los elementos del archivo KML y guardar los datos en la tabla "ruta"
    foreach ($xml->Document->Folder->Folder->Folder->Folder as $codigoCarroFolder) {
        $codigo_carro = (string) $codigoCarroFolder->name;

        // Verificar si hay más niveles de carpetas dentro del código de carro
        if (isset($codigoCarroFolder->Folder)) {
            foreach ($codigoCarroFolder->Folder as $rutaFolder) {
                // Obtener los datos de la ruta
                $rutaData = $this->extractRutaData($rutaFolder);

                // Crear una nueva ruta en la base de datos
                $ruta = Ruta::create([
                    'codigo_carro' => $codigo_carro,
                    'nombre_ruta' => $rutaData['nombre_ruta'],
                    'latitud_inicio' => $rutaData['latitud_inicio'],
                    'longitud_inicio' => $rutaData['longitud_inicio'],
                    'latitud_fin' => $rutaData['latitud_fin'],
                    'longitud_fin' => $rutaData['longitud_fin'],
                    'tiene_saltos' => $rutaData['tiene_saltos'],
                    'fecha_modificacion' => now(),
                    'idKmlRuta' => $idKmlRuta,
                ]);
                $this->exploreLineStringsInFolders($rutaFolder, $ruta->id);
            }
        }
    }

    return response()->json([
        'message' => 'Datos del archivo KML procesados y guardados correctamente',
        'success' => true,
    ], 200);
}

// Función para extraer los datos de la ruta de un folder
private function extractRutaData($rutaFolder)
{
    $nombre_ruta = (string) $rutaFolder->name;

    // Verificar si existe el elemento Placemark y Point
    if (isset($rutaFolder->Placemark) && isset($rutaFolder->Placemark->Point)) {
        $coordinatesInicio = $this->getCoordinates($rutaFolder->Placemark->Point);
        list($latitud_inicio, $longitud_inicio) = $coordinatesInicio;

        $coordinatesFin = $this->getCoordinates($rutaFolder->Placemark->Point);
        list($latitud_fin, $longitud_fin) = $coordinatesFin;
    } else {
        // Manejo de error o valor predeterminado en caso de que falten datos
        $latitud_inicio = 0.0; // Valor predeterminado
        $longitud_inicio = 0.0; // Valor predeterminado

        $latitud_fin = 0.0; // Valor predeterminado
        $longitud_fin = 0.0; // Valor predeterminado
    }

    $tiene_saltos = is_array($rutaFolder->Placemark) || is_countable($rutaFolder->Placemark) ? count($rutaFolder->Placemark) > 3 : false;

    return [
        'nombre_ruta' => $nombre_ruta,
        'latitud_inicio' => $latitud_inicio,
        'longitud_inicio' => $longitud_inicio,
        'latitud_fin' => $latitud_fin,
        'longitud_fin' => $longitud_fin,
        'tiene_saltos' => $tiene_saltos,
    ];
}


// Función para obtener las coordenadas de un punto
private function getCoordinates($point)
{
    if (isset($point)) {
        $coordinates = explode(',', (string) $point->coordinates);

        if (count($coordinates) >= 2) {
            $latitud = (float) $coordinates[1];
            $longitud = (float) $coordinates[0];
        } else {
            // Manejo de error o valor predeterminado en caso de que falten datos
            $latitud = 0.0; // Valor predeterminado
            $longitud = 0.0; // Valor predeterminado
        }
    } else {
        // Manejo de error o valor predeterminado en caso de que no exista <Point>
        $latitud = 0.0; // Valor predeterminado
        $longitud = 0.0; // Valor predeterminado
    }

    return [$latitud, $longitud];
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
