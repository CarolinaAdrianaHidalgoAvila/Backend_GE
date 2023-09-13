<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoLinea extends Model
{
    use HasFactory;

    protected $table = "punto_linea";

    protected $fillable = [
      'longitud',
      'latitud',
      'orden',
      'idRuta'
    ];
 
}
