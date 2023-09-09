<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;
    protected $table = "ruta";
    protected $fillable = [
      'codigo_carro',
      'nombre_ruta',
      'latitud_inicio',
      'longitud_inicio',
      'latitud_fin',
      'longitud_fin',
      'tiene_saltos',
      'fecha_modificacion',
      'idKmlRuta'
    ];
}
