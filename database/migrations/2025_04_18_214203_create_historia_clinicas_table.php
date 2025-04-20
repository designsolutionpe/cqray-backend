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
        Schema::create('historia_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_paciente')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('id_sede')->constrained('sedes');
            $table->foreignId('id_estado_cita')->constrained('estado_citas')->nullable();
            $table->foreignId('id_articulo')->constrained('articulos');
            $table->integer('estado_pago')->default(0); // 0 - pendiente | 1 - pagado | 2 - deuda | 3 - cancelado
            $table->tinyInteger('activo')->default(1); // 0 - inactivo | 1 - activo
            $table->date('fecha')->nullable()->default(null);
            $table->time('hora')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_clinicas');
    }
};
