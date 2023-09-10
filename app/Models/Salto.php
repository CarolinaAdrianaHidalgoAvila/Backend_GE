<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salto extends Model
{
    use HasFactory;
    protected $table = "salto";
    protected $fillable = [
      'nombre_salto',
      'latitud_inicio',
      'longitud_inicio',
      'latitud_fin',
      'longitud_fin',
      'idRuta'
    ];
}
