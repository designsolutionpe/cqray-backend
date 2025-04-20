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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_sede')->constrained('sedes')->onDelete('cascade');

            $table->foreignId('id_categoria')->constrained('categoria_articulos')->onDelete('cascade');

            $table->tinyInteger('tipo_articulo'); // 1: Producto 2: Servicio
            
            $table->foreignId('id_unidad_medida')->constrained('unidad_medida_articulos')->onDelete('cascade');

            $table->string('nombre');
            $table->string('detalle');
            $table->integer('cantidad');
            $table->integer('limite_cantidad')->nullable();
            $table->decimal('precio_venta',10,2);
            $table->decimal('precio_mayor',10,2)->nullable();
            $table->decimal('precio_distribuidor',10,2)->nullable();
            $table->decimal('precio_compra',10,2)->nullable();

            $table->string('tipo_tributo')->nullable();
            $table->string('tributo')->nullable();
            $table->string('codigo_internacional')->nullable();
            $table->string('nombre_tributo')->nullable();

            $table->timestamps();
        });

        Schema::table('detalle_comprobantes', function(Blueprint $table){
            $table->foreignId('id_articulo')->constrained('articulos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
