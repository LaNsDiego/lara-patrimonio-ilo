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
        Schema::create('user_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Identificador del usuario');
            $table->unsignedBigInteger('employee_id')->comment('Identificador del empleado');
            $table->unique(['user_id', 'employee_id'])->comment('Clave única de usuario y empleado');
            $table->foreign('user_id')->references('id')->on('users')->comment('Clave foránea del usuario');
            $table->foreign('employee_id')->references('id')->on('employees')->commment('Clave foránea del empleado');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena la relación entre usuarios y empleados.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_employees');
    }
};
