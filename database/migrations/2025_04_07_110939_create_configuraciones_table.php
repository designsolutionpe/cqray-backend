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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la configuración
            $table->string('ruc'); // RUC de la empresa
            $table->integer('numero_sucursales'); // Número de sucursales
            $table->string('imagen1')->nullable(); // Imagen 1 (opcional)
            $table->string('imagen2')->nullable(); // Imagen 2 (opcional)
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
