<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContenedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contenedor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_contenedor');
            $table->double('latitud');
            $table->double('longitud');
            $table->timestamp('fecha_modificacion')->nullable();
            $table->string('tipo');
            $table->unsignedBigInteger('idKmlContenedor')->nullable(); // Clave foránea
            $table->foreign('idKmlContenedor')->references('id')->on('kml_contenedor')->onDelete('cascade');
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
        Schema::dropIfExists('contenedor');
    }
}
