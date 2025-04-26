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
        Schema::table('sedes', function (Blueprint $table) {
            //
            $table->string('ruc')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('direccion_fiscal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            //
            $table->dropColumn('ruc');
            $table->dropColumn('razon_social');
            $table->dropColumn('direccion_fiscal');
        });
    }
};
