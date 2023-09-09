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
        Schema::create('carro', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_vehiculo');
            $table->string('distrito');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->double('distancia');
            $table->string('observacion');
            $table->timestamp('fecha_modificacion')->nullable();
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
        Schema::dropIfExists('carro');
    }
};
