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
        //
        Schema::table('detalle_comprobantes',function(Blueprint $table){
            $table->unsignedBigInteger('id_articulo')->nullable()->change();
        });
        Schema::table('detalle_notas', function (Blueprint $table) {
            // 1. Hacer el campo nullable
            $table->unsignedBigInteger('id_articulo')->nullable()->change();
        });
        Schema::table('historia_clinicas',function(Blueprint $table){
            $table->unsignedBigInteger('id_articulo')->nullable()->change();
        });
        Schema::table('detalle_comprobantes', function (Blueprint $table) {
            // 2. Eliminar la clave foránea actual
            $table->dropForeign(['id_articulo']);

            // 3. Crear nueva clave foránea con SET NULL
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('set null');
        });

        Schema::table('detalle_notas', function (Blueprint $table) {
            // 2. Eliminar la clave foránea actual
            $table->dropForeign(['id_articulo']);

            // 3. Crear nueva clave foránea con SET NULL
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('set null');
        });
        Schema::table('historia_clinicas', function (Blueprint $table) {
            // 2. Eliminar la clave foránea actual
            $table->dropForeign(['id_articulo']);

            // 3. Crear nueva clave foránea con SET NULL
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('detalle_comprobantes', function (Blueprint $table) {
            // Volver a eliminar la foreign key con set null
            $table->dropForeign(['id_articulo']);

            // Hacerla NOT NULL de nuevo
            $table->unsignedBigInteger('id_articulo')->nullable(false)->change();

            // Restaurar la foreign key con restrict
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('restrict');
        });
        Schema::table('detalle_notas', function (Blueprint $table) {
            // Volver a eliminar la foreign key con set null
            $table->dropForeign(['id_articulo']);

            // Hacerla NOT NULL de nuevo
            $table->unsignedBigInteger('id_articulo')->nullable(false)->change();

            // Restaurar la foreign key con restrict
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('restrict');
        });
        Schema::table('historia_clinicas', function (Blueprint $table) {
            // Volver a eliminar la foreign key con set null
            $table->dropForeign(['id_articulo']);

            // Hacerla NOT NULL de nuevo
            $table->unsignedBigInteger('id_articulo')->nullable(false)->change();

            // Restaurar la foreign key con restrict
            $table->foreign('id_articulo')
                ->references('id')
                ->on('articulos')
                ->onDelete('restrict');
        });
    }
};
