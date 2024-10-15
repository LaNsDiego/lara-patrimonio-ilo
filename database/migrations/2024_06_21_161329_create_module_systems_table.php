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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id()->comment('Identificador del módulo del sistema.');
            $table->string('name')->comment('Nombre del módulo del sistema.');
            $table->string('description')->comment('Descripción del módulo del sistema.');
            $table->unsignedBigInteger('module_group_id')->comment('Identificador del grupo de los módulos del sistema.');
            $table->foreign('module_group_id')->references('id')->on('module_groups');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena los módulos del sistema.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
