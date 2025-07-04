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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tipo_comprobante');
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');
            $table->smallInteger('tipo'); // TIPO ARTICULO (1 producto 2 servicio)
            $table->foreignId('id_persona')->constrained('personas')->onDelete('cascade');
            $table->string('serie');
            $table->string('numero');
            $table->date('fecha_emision');
            $table->enum('moneda', ['PEN', 'USD']);
            $table->decimal('tipo_cambio', 10, 2)->nullable();
            $table->boolean('igv')->default(false);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('monto_igv', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('pago_cliente', 10, 2)->default(0);
            $table->decimal('vuelto', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
