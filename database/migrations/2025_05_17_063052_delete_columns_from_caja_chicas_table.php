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
        Schema::table('caja_chicas', function (Blueprint $table) {
            //
            $table->enum('tipo',['Inicial','Ingreso','Egreso','Terminal'])->change();
            $table->dropColumn('flg_inicial');
            $table->dropColumn('flg_terminal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caja_chicas', function (Blueprint $table) {
            //
            $table->enum('tipo',['Ingreso','Egreso'])->change();
            $table->tinyInteger('flg_inicial')->default(0);
            $table->tinyInteger('flg_terminal')->default(0);
        });
    }
};
