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
        Schema::create('inventory_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->comment('Identificador de inventario');
            $table->unsignedBigInteger('headquarter_id')->comment('Identificador de la sede del establecimiento');
            $table->unsignedBigInteger('responsible_headquarter_id')->comment('Identificador del empleado responsable de la sede');
            $table->unsignedBigInteger('inventory_manager_id')->comment('Identificador del usuario encargado del inventario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_tasks');
    }
};
