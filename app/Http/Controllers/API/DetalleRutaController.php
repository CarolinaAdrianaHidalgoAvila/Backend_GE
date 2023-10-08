<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleRuta;
use App\Models\Frecuencia;
use App\Models\Ruta;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DetalleRutaController extends Controller
{
    public function getAll(){
        $data = DetalleRuta::get();
        return response()->json($data, 200);
      }
    public function delete($id){
        $res = DetalleRuta::find($id)->delete();
        return response()->json([
            'message' => "Eliminado exitosamente",
            'success' => true
        ], 200);
      }
      public function get($idRuta){
        $data = DetalleRuta::where('idRuta', $idRuta)->first();
        return response()->json($data, 200);
      }
      public function getFrecuencias($idDetalleRuta) {
        $data = Frecuencia::where('idDetalleRuta', $idDetalleRuta)->get();
        return response()->json($data, 200);
    }
    function convertirFraccionDecimalAHora($fraccionDecimal) {
        $horas = (int) ($fraccionDecimal * 24); // Obtener las horas
        $minutos = (int) (($fraccionDecimal * 24 * 60) % 60); // Obtener los minutos
        $segundos = (int) (($fraccionDecimal * 24 * 3600) % 60); // Obtener los segundos
        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos); // Formatear como HH:MM:SS
    }
    
    public function import(Request $request)
    {
       // Validación del archivo Excel
       $request->validate([
        'file' => 'required|mimes:xlsx',
        ]);
    // Obtener el archivo Excel del formulario
         $file = $request->file('file');
    // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($file);
    // Obtener la hoja de trabajo (worksheet)
        $worksheet = $spreadsheet->getActiveSheet();
    // Recorrer las filas de datos (empezando desde la fila 2, asumiendo encabezados en la fila 1)
        foreach ($worksheet->getRowIterator(3) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            // Leer datos de cada columna en la fila
            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Buscar la ruta correspondiente usando código y nombre de ruta
            $ruta = Ruta::where('codigo_carro', $data[1])
                ->where('nombre_ruta', $data[2])
                ->first();

            if ($ruta) {
                // Crear una nueva entrada en la tabla DetalleRuta con el ID de la ruta
                $detalleRuta = new DetalleRuta([
                    'codigo_vehiculo' => $data[1],
                    'nombre_ruta' => $data[2],
                    'distrito' => $data[3],
                    'hora_inicio'  => $this->convertirFraccionDecimalAHora($data[4]),
                    'hora_fin'  => $this->convertirFraccionDecimalAHora($data[5]),
                    'peso' => $data[13],
                    'distancia' => $data[14],
                    'observacion' => $data[16],
                    'fecha_modificacion' => now(),
                    'idRuta' => $ruta->id, // Asignar el ID de la ruta encontrada
                ]);
                $detalleRuta->save();
                $diasDeLaSemana = [
                    'LUNES',
                    'MARTES',
                    'MIÉRCOLES',
                    'JUEVES',
                    'VIERNES',
                    'SÁBADO',
                    'DOMINGO',
                ];
                // Crear entradas en la tabla Frecuencia para los días de la semana (columnas 7 a 13)
                for ($i = 7; $i <= 13; $i++) {
                    $diaSemana = $diasDeLaSemana[$i - 7]; // Restar 7 para mapear correctamente al día correcto
                    $frecuencia = new Frecuencia([
                        'dia' => $diaSemana,
                        'estado' => ($data[$i] == 'VERDADERO'), // Asumiendo que 'VERDADERO' significa verdadero
                        'idDetalleRuta' => $detalleRuta->id, // Asignar el ID del detalle de ruta
                    ]);
                    $frecuencia->save();
                }
            } else {
                $detalleRuta = new DetalleRuta([
                    'codigo_vehiculo' => $data[1],
                    'nombre_ruta' => $data[2],
                    'distrito' => 'No hay este dato en KML',
                    'hora_inicio' => '00:00:00',
                    'hora_fin' => '00:00:00',
                    'peso' => 0, 
                    'distancia' => 0, 
                    'fecha_modificacion' => now(),
                    'observacion' => 'No hay este dato en KML',
                    'idRuta'=> null
                ]);
            
                $detalleRuta->save();
            }
        }

        // Retornar una respuesta indicando que el procesamiento fue exitoso
        return response()->json([
            'message' => 'Datos del archivo procesados y guardados correctamente',
            'success' => true,
        ], 200);

    }
    public function updateDetalleRuta(Request $request, $idRuta, $id)
{
    $data['codigo_vehiculo'] = $request['codigo_vehiculo'];
    $data['nombre_ruta'] = $request['nombre_ruta'];
    $data['distrito'] = $request['distrito'];
    $data['hora_inicio'] = $request['hora_inicio'];
    $data['hora_fin'] = $request['hora_fin'];
    $data['peso'] = $request['peso'];
    $data['distancia'] = $request['distancia'];
    $data['observacion'] = $request['observacion'];
    $data['fecha_modificacion'] = $request['fecha_modificacion'];

    // Buscar el registro en la tabla "contenedor" por su id y actualizar los campos
    DetalleRuta::where('id', $id)->where('idRuta', $idRuta)->update($data);

    return response()->json([
        'message' => 'Detalle de ruta actualizado correctamente',
        'success' => true
    ], 200);
}

public function updateFrecuencias(Request $request, $idDetalleRuta)
{
    if ($request->has('frecuencias')) {
        $frecuencias = $request->input('frecuencias');

        foreach ($frecuencias as $frecuenciaData) {
            // Aquí asumimos que las frecuencias están relacionadas con el detalle de ruta mediante el campo idDetalleRuta
            $frecuencia = Frecuencia::where('id', $frecuenciaData['id'])->where('idDetalleRuta', $idDetalleRuta)->first();

            if ($frecuencia) {
                $frecuencia->estado = $frecuenciaData['estado'];
                $frecuencia->save();
            }
        }

        return response()->json([
            'message' => 'Estados de frecuencias actualizados correctamente',
            'success' => true
        ], 200);
    }

    return response()->json([
        'message' => 'No se proporcionaron frecuencias para actualizar',
        'success' => false
    ], 400);
}


}
