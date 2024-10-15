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
        Schema::create('inventory_task_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->comment('Identificador del activo');
            $table->unsignedBigInteger('inventory_task_id')->comment('Identificador de la tarea de inventario');
            $table->unsignedBigInteger('inventory_task_referencial_id')->comment('Identificador de la tarea de inventario referencial');
            $table->unsignedBigInteger('real_inventory_manager_id')->comment('Identificador del usuario que escaneo el activo');

            $table->foreign('asset_id')->references('id')->on('products');
            $table->foreign('inventory_task_id')->references('id')->on('inventory_tasks');
            $table->foreign('inventory_task_referencial_id')->references('id')->on('inventory_tasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_task_details');
    }
};
