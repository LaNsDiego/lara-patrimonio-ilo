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
        Schema::create('surplus_assets', function (Blueprint $table) {
            $table->id();
            $table->string('type_asset')->comment('Nombre del tipo de bien');
            $table->string('image')->comment('Imagen del bien');
            $table->string('serial_number')->nullable()->comment('Número de serie');
            $table->string('dimensions')->nullable()->comment('Medidas');
            $table->string('license_plate')->nullable()->comment('Placa');
            $table->string('manufacture_year')->nullable()->comment('Año de fabricación');
            $table->string('color')->nullable()->comment('Color');
            $table->string('chassis')->nullable()->comment('Chasis');
            $table->string('engine')->nullable()->comment('Motor');
            $table->unsignedBigInteger('inventory_task_id')->comment('Identificador de la tarea del inventario');
            $table->unsignedBigInteger('inventory_manager_id')->comment('Identificador del usuario que escaneo el bien sobrante');

            $table->foreign('inventory_task_id')->references('id')->on('inventory_tasks');
            $table->foreign('inventory_manager_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surplus_assets');
    }
};
