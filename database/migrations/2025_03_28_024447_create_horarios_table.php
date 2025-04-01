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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_doctor')->constrained('doctores')->onDelete('cascade');
            $table->tinyInteger('dia'); // 0 = Lunes, 6 = Domingo
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('duracion'); // En minutos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
