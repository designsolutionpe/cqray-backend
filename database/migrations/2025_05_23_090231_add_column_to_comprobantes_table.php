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
        Schema::table('comprobantes', function (Blueprint $table) {
            //
            $table->decimal("deuda",10,2)->nullable();
            $table->unsignedBigInteger("id_tipo_pago_secundario")->nullable();
            $table->decimal("pago_cliente_secundario",10,2)->nullable();
            $table->foreign("id_tipo_pago_secundario")->references("id")->on("tipo_pagos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            //
            $table->dropForeign(["id_tipo_pago_secundario"]);
            $table->dropColumn(["deuda","id_tipo_pago_secundario","pago_cliente_secundario"]);

        });
    }
};
