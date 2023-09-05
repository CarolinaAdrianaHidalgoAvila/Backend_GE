<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idRuta');
            $table->foreign('idRuta')->references('id')->on('ruta')->onDelete('cascade');
            $table->string('nombre_salto');
            $table->double('inicio_latitud');
            $table->double('inicio_longitud');
            $table->double('fin_latitud')->nullable();
            $table->double('fin_longitud')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salto');
    }
};
