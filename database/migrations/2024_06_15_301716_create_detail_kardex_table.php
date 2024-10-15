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
        Schema::create('detail_kardex', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kardex_id')->comment('Identificador del kardex');
            $table->unsignedBigInteger('product_id')->comment('Identificador del producto');
            $table->unsignedBigInteger('responsible_employee_id')->nullable()->comment('Identificador del empleado responsable del registro');
            $table->unsignedBigInteger('establishment_id')->nullable()->comment('Identificador del establecimiento');
            $table->unsignedBigInteger('movement_type_id')->comment('Identificador del tipo de movimiento');
            $table->integer('quantity')->comment('Cantidad de productos involucrados en el movimiento');
            $table->decimal('unit_price', 10, 2)->nullable()->comment('Precio unitario del producto en el movimiento');
            $table->decimal('total_price', 10, 2)->nullable()->comment('Precio total del producto en el movimiento');
            $table->string('comment')->nullable()->comment('Notas adicionales');
            $table->unsignedBigInteger('transfer_ticket_id')->nullable()->comment('Identificador de la papeleta de traslado');

            $table->foreign('kardex_id')->references('id')->on('kardex')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('responsible_employee_id')->references('id')->on('employees');
            $table->foreign('movement_type_id')->references('id')->on('movement_types');
            $table->foreign('establishment_id')->references('id')->on('establishments');

            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena los detalles de los movimientos de kardex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kardex');
    }
};
