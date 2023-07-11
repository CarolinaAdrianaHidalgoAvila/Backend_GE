<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmlContenedor extends Model
{
    use HasFactory;
    protected $table = "kml_contenedor";
    protected $fillable = [
      'nombre_archivo',
      'path',
      'fecha_carga'
    ];
}
