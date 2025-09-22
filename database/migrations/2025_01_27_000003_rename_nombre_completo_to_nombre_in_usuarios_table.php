<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('nombre', 100)->nullable()->after('id_usuario');
        });
        
        // Copiar datos de nombre_completo a nombre
        DB::statement('UPDATE usuarios SET nombre = nombre_completo');
        
        // Eliminar la columna antigua
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('nombre_completo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('nombre_completo', 100)->nullable()->after('id_usuario');
        });
        
        // Copiar datos de nombre a nombre_completo
        DB::statement('UPDATE usuarios SET nombre_completo = nombre');
        
        // Eliminar la columna nueva
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
};
