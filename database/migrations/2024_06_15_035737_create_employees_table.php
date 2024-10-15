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
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->comment('Identificador de empleado');
            $table->string('document_type')->comment('Tipo de documento');
            $table->string('document_number')->comment('Número de documento');
            $table->string('name')->comment('Nombre de la empleado');
            $table->unsignedBigInteger('job_title_id')->comment('Identificador del cargo');
            $table->foreign('job_title_id')->references('id')->on('job_titles')->comment('Clave foránea del cargo');
            $table->unsignedBigInteger('establishment_id')->comment('Identificador del establecimiento');
            $table->foreign('establishment_id')->references('id')->on('establishments')->comment('Clave foránea del establecimiento');
            $table->string('email')->nullable()->comment('Correo electrónico');
            $table->string('phone_number')->nullable()->comment('Número de teléfono o celular');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena los empleados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
