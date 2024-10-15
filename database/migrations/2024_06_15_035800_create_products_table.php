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
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('Identificador del vehículo');

            $table->string('barcode')->comment('codigo de barras');
            $table->string('image')->nullable()->comment('Imagen del vehículo');
            $table->string('serial_number')->nullable()->comment('Número de serie');

            $table->unsignedBigInteger('product_type_id')->nullable()->comment('Identificador del tipo de producto');
            $table->unsignedBigInteger('employee_id')->nullable()->comment('Identificador de la empleado responsable  del producto');
            // $table->unsignedBigInteger('establishment_id')->nullable()->comment('Identificador del establecimiento');
            $table->unsignedBigInteger('responsible_employee_id')->nullable()->comment('Identificador de la persona de la accion');

            $table->decimal('acquisition_cost', 10, 2)->nullable()->comment('Costo de adquisición');
            $table->decimal('rental_price', 10, 2)->nullable()->comment('Costo de alquiler');
            $table->string('siga_code')->nullable()->comment('Código SIGA');
            $table->string('accounting_account')->nullable()->comment('Cuenta contable');
            $table->string('order_number')->nullable()->comment('Número de orden');
            $table->string('pecosa_number')->nullable()->comment('Número de PECOSA');
            $table->string('dimensions')->nullable()->comment('Medidas');
            $table->string('license_plate')->nullable()->comment('Placa');
            $table->string('manufacture_year')->nullable()->comment('Año de fabricación');
            $table->string('color')->nullable()->comment('Color');
            $table->string('chassis')->nullable()->comment('Chasis');
            $table->string('engine')->nullable()->comment('Motor');
            $table->decimal('historical_value', 10, 2)->nullable()->comment('Valor histórico');
            $table->string('status')->nullable()->comment('Estado');
            $table->enum('type_machinery',['MAQUINARIA PESADA', 'MAQUINARIA LIGERA','NO ASIGNADO'])->default('NO ASIGNADO')->comment('Tipo de maquinaria');
            $table->unsignedBigInteger('establishment_location_id')->comment('Ubicacion del bien');

            $table->foreign('establishment_location_id')->references('id')->on('establishment_locations');
            $table->foreign('product_type_id')->references('id')->on('product_types');
            $table->foreign('employee_id')->references('id')->on('employees');
            // $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('responsible_employee_id')->references('id')->on('employees');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);

            $table->comment('Esta tabla almacena los vehículos de la empresa.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
