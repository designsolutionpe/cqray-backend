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
        Schema::table('articulos', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_estado_paciente')->nullable();
            $table->foreign('id_estado_paciente')->references('id')->on('estado_pacientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            //
            $table->dropForeign(['id_estado_paciente']);
            $table->dropColumn('id_estado_paciente');
        });
    }
};
