<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmlRuta extends Model
{
    use HasFactory;
    protected $table = "kml_ruta";
    protected $fillable = [
      'nombre_archivo',
      'path',
      'fecha_carga'
    ];
}
