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
        Schema::create('notas_creditos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tipo_comprobante'); // Tipo de comprobante (1: boleta, 2: factura)
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');
            $table->foreignId('id_comprobante')->constrained('comprobantes')->onDelete('cascade');
            $table->smallInteger('motivo'); 
            $table->text('comentario')->nullable(); // Campo de comentario para descripción adicional
            $table->date('fecha_emision');
            $table->enum('moneda', ['PEN', 'USD']);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('monto_igv', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_creditos');
    }
};
