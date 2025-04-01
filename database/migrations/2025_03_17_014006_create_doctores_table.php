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
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_persona')->constrained('personas')->onDelete('cascade');
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');
            $table->string('numero_colegiatura')->unique()->nullable();
            $table->string('especialidad')->nullable();
            $table->string('datos_contacto')->nullable();
            $table->tinyInteger('estado')->default(1); // 1: Activo, 0: Inactivo, 2: Vacaciones
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctores');
    }
};
