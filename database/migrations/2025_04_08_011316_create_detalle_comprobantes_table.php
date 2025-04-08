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
        Schema::create('detalle_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_comprobante')->constrained('comprobantes')->onDelete('cascade');
            $table->foreignId('id_producto')->constrained('items')->onDelete('restrict');
            $table->integer('cantidad');
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('total_producto', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_comprobantes');
    }
};
