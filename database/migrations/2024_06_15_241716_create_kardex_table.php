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
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Fecha');
            $table->unsignedBigInteger('product_type_id')->comment('Identificador del tipo de producto');
            $table->foreign('product_type_id')->references('id')->on('product_types')->comment('Clave foránea del tipo de producto');
            $table->integer('quantity')->nullable()->comment('Cantidad');
            $table->double('unit_cost')->nullable()->comment('Costo unitario');
            $table->double('total_cost')->nullable()->comment('Costo total');
            $table->unsignedBigInteger('establishment_id')->comment('Identificador del establecimiento');
            $table->foreign('establishment_id')->references('id')->on('establishments')->comment('Clave foránea del establecimiento');
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('Identificador del proveedor');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->comment('Clave foránea del proveedor');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de kardex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardex');
    }
};
