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
        Schema::table('pacientes', function (Blueprint $table) {
            $table->integer('deuda')->nullable();
            $table->unsignedBigInteger('id_articulo_deuda')->nullable();
            $table->foreign('id_articulo_deuda')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('deuda');
            $table->dropForeign(['id_articulo_deuda']);
            $table->dropColumn('id_articulo_deuda');
        });
    }
};
