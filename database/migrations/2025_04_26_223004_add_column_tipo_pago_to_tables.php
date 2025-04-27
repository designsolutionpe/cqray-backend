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
            $table->unsignedBigInteger('id_tipo_pago')->nullable();
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pagos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            //
            $table->dropForeign(['id_tipo_pago']);
            $table->dropColumn('id_tipo_pago');
        });
    }
};
