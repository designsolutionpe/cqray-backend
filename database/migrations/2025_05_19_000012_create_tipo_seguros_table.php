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
        Schema::create('tipo_seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo',['SNP','AFP']);
            $table->decimal('snp',5,2)->nullable();
            $table->decimal('aporte',5,2)->nullable();
            $table->decimal('invalidez',5,2)->nullable();
            $table->decimal('comision',5,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_seguros');
    }
};
