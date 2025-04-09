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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            // Relación con pacientes
            $table->foreignId('id_paciente')->constrained('pacientes')->onDelete('cascade');
            // Relación con quiropracticos
            //$table->foreignId('id_quiropractico')->constrained('quiropracticos')->onDelete('cascade');
            // Relación con detalles horarios
            //$table->foreignId('id_detalle_horario')->constrained('detalle_horarios')->onDelete('cascade');
            // Relación con sede
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');
        
            // Campos adicionales
            $table->date('fecha_cita'); // Solo la fecha (sin hora)
            $table->time('hora_cita');
            $table->tinyInteger('estado')->default(0); // 0 - Pendiente, 1 - Confirmado, 2 - Atendido, 9 - Cancelado
            $table->tinyInteger('tipo_paciente')->default(1); // 1 - Nuevo, 2 - Reporte, 3 - Plan, 4 - Mantenimiento

            // Campos adicionales para la atención
            $table->date('fecha_atencion')->nullable(); // Fecha de atención
            $table->time('hora_atencion')->nullable(); // Hora de atención
            $table->text('observaciones')->nullable(); // Observaciones

            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
