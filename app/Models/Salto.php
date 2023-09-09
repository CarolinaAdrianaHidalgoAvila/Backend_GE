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
      'inicio_latitud',
      'inicio_longitud',
      'fin_latitud',
      'fin_longitud',
      'idRuta'
    ];
}
