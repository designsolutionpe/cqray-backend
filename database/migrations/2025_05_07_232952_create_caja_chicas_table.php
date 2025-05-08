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
        Schema::create('caja_chicas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo',['Ingreso','Egreso']);
            $table->decimal('balance',10,2);
            $table->unsignedBigInteger('id_sede')->nullable();
            $table->foreign('id_sede')->references('id')->on('sedes');
            $table->date('fecha')->default(now());
            $table->tinyInteger('flg_inicial')->default(0);
            $table->tinyInteger('flg_terminal')->default(0);
            $table->timestamps(); // created_at == caja abierta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_chicas');
    }
};
