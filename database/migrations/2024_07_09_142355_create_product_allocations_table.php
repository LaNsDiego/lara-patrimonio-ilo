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
        Schema::create('product_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programation_schedule_id')->comment('Llave foránea a la programación de horarios');
            $table->unsignedBigInteger('employee_id')->comment('Llave foránea al empleado');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de asignación de productos a empleados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_allocations');
    }
};
