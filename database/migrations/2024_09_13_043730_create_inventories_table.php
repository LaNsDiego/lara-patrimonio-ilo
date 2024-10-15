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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['INOPINIONADO', 'ANUAL'])->comment('Tipo de inventario');
            $table->date('start_date')->comment('Fecha inicio del inventario');
            $table->date('end_date')->comment('Fecha fin del inventario');
            $table->string('description')->comment('Descripción del inventario');
            $table->enum('state',['NO CUMPLIO' , 'CUMPLIO','CUMPLIO PARCIALMENTE'])->comment('Estado cumplimiento');
            $table->unsignedInteger('progress')->comment('Progreso del inventario');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
