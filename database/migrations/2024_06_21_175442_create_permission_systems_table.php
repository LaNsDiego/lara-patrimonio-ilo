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
        Schema::create('permission_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_module_id')->comment('modulo al que se le asigna el permiso con su acción');
            $table->string('action')->comment('crear , actualizar , borrar y ver un recurso');
            $table->foreign('system_module_id')->references('id')->on('system_modules');
            $table->comment('permiso asignado al modulo');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_systems');
    }
};
