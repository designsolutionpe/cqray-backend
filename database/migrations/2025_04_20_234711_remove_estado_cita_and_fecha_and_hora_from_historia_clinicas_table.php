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
        Schema::table('historia_clinicas', function (Blueprint $table) {
            //
            $table->dropForeign('historia_clinicas_id_estado_cita_foreign');
            $table->dropColumn(['id_estado_cita','fecha','hora']);
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->foreign('id_cita')->references('id')->on('citas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historia_clinicas', function (Blueprint $table) {
            //
            $table->foreignId('id_estado_cita')->constrained('estado_citas')->nullable();
            $table->date('fecha')->nullable()->default(null);
            $table->time('hora')->nullable()->default(null);
            $table->dropColumn('id_cita');
        });
    }
};
