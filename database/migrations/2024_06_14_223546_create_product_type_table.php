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
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del tipo de producto');
            $table->text('description')->nullable()->comment('Descripción');
            $table->string('model')->nullable()->comment('Modelo');
            $table->json('tags')->nullable()->comment('Etiquetas');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('Identificador de la marca');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('measurement_unit_id')->comment('Identificador de la unidad de medida');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de tipos de productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};
