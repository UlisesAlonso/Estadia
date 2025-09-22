<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajustar el tipo del campo 'estado' para incluir 'completada' en el ENUM
        // Usamos SQL crudo para evitar dependencia de doctrine/dbal
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE `citas` MODIFY `estado` ENUM('pendiente','confirmada','completada','cancelada') NOT NULL DEFAULT 'pendiente'"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir: quitar 'completada' si fuera necesario
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE `citas` MODIFY `estado` ENUM('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente'"
        );
    }
};
