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
        Schema::create('frecuencia', function (Blueprint $table) {
            $table->id();
            $table->string('dia');
            $table->boolean('estado')->default(false);
            $table->unsignedBigInteger('idDetalleRuta')->nullable(); // Clave foránea
            $table->foreign('idDetalleRuta')->references('id')->on('detalle_ruta')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frecuencia');
    }
};
