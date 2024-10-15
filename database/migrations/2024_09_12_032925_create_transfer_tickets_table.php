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
        Schema::create('transfer_tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->comment('Fecha de la papeleta de traslado');
            $table->timestamp('start_date')->comment('Fecha inicio de la papeleta de traslado');
            $table->timestamp('end_date')->comment('Fecha de termino de la papeleta de traslado');
            $table->string('reason')->comment('Motivo de la papeleta de traslado');
            $table->text('observation')->nullable()->comment('Observaciones adicionales');
            $table->unsignedBigInteger('approver_employee_id')->nullable()->comment('Identificador del empleado que aprueba la papeleta de traslado');
            $table->unsignedBigInteger('requester_employee_id')->comment('Identificador del empleado que solicita la papeleta de traslado');
            $table->unsignedBigInteger('origin_establishment_id')->comment('Identificador del centro de costo origen');
            $table->unsignedBigInteger('destination_establishment_id')->comment('Identificador del centro de costo destino');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_tickets');
    }
};
