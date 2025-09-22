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
        Schema::table('citas', function (Blueprint $table) {
            $table->text('observaciones_clinicas')->nullable()->after('motivo');
            $table->string('especialidad_medica')->nullable()->after('observaciones_clinicas');
            $table->timestamp('created_at')->nullable()->after('estado');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['observaciones_clinicas', 'especialidad_medica', 'created_at', 'updated_at']);
        });
    }
};
