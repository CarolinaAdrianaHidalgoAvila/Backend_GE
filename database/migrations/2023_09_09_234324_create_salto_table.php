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
            $table->string('nombre_salto');
            $table->double('latitud_inicio');
            $table->double('longitud_inicio');
            $table->double('latitud_fin');
            $table->double('longitud_fin');
            $table->unsignedBigInteger('idRuta')->nullable(); // Clave foránea
            $table->foreign('idRuta')->references('id')->on('ruta')->onDelete('cascade');
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
