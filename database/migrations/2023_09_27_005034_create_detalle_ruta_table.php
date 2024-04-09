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
        Schema::create('detalle_ruta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_vehiculo');
            $table->string('nombre_ruta');
            $table->string('distrito');
            $table->string('hora_inicio');
            $table->string('hora_fin');
            $table->integer('peso')->nullable();
            $table->double('distancia')->nullable();
            $table->string('observacion')->nullable();
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
        Schema::dropIfExists('detalle_ruta');
    }
};
