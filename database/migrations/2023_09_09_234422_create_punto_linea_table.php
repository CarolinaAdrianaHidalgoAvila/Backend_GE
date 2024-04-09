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
        Schema::create('punto_linea', function (Blueprint $table) {
            $table->id();
            $table->double('latitud');
            $table->double('longitud');
            $table->integer('orden');
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
        Schema::dropIfExists('punto_linea');
    }
};
