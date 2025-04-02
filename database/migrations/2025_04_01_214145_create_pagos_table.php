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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');
            $table->enum('metodo_pago', ['Transferencia', 'Efectivo', 'Plin', 'Yape']); // Métodos de pago
            $table->enum('moneda', ['PEN', 'USD']); // Moneda
            $table->text('detalle_concepto')->nullable(); // Descripción del concepto de pago
            $table->string('numero_cuenta')->nullable(); // Número de cuenta asociada (si aplica)
            $table->tinyInteger('estado')->default(1); // 1 - ACTIVO, 0 - INACTIVO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
