<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    use HasFactory;

    protected $table = "contenedor";
    protected $fillable = [
      'nombre_contenedor',
      'latitud',
      'longitud',
      'fecha_modificacion',
      'tipo',
      'idKmlContenedor'
    ];
 
}
