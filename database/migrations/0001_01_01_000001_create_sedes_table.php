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
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la sede
            $table->string('direccion')->nullable(); // Dirección (opcional)
            $table->string('telefono', 20)->nullable(); // Teléfono (opcional)
            $table->string('email')->nullable(); // Correo electrónico (opcional)
            $table->string('foto')->nullable(); // Ruta de la imagen de la sede (opcional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
