<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_carro');
            $table->string('nombre_ruta');
            $table->double('latitud_inicio');
            $table->double('longitud_inicio');
            $table->double('latitud_fin');
            $table->double('longitud_fin');
            $table->boolean('tiene_saltos')->default(false);
            $table->timestamp('fecha_modificacion')->nullable();
            $table->unsignedBigInteger('idKmlRuta')->nullable(); // Clave foránea
            $table->foreign('idKmlRuta')->references('id')->on('kml_ruta')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruta');
    }
}
