<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRuta extends Model
{
    use HasFactory;

    protected $table = "detalle_ruta";

    protected $fillable = [
      'codigo_vehiculo',
      'nombre_ruta',
      'distrito',
      'hora_inicio',
      'hora_fin',
      'peso',
      'distancia',
      'observacion',
      'fecha_modificacion',
      'idRuta'
    ];
 
}
