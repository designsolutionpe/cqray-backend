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
        // Update data to correct format
        DB::statement("UPDATE caja_chicas set fecha = DATE_FORMAT(fecha,'%Y-%m-%d')");

        // Change column to string
        DB::statement("ALTER TABLE caja_chicas MODIFY fecha varchar(255)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE caja_chicas MODIFY fecha DATE");
    }
};
