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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id()->comment('Identificador del trabajo');
            $table->string('queue')->index()->comment('Cola del trabajo');
            $table->longText('payload')->comment('Carga del trabajo');
            $table->unsignedTinyInteger('attempts')->comment('Intentos del trabajo');
            $table->unsignedInteger('reserved_at')->nullable()->comment('Reservado en');
            $table->unsignedInteger('available_at')->comment('Disponible en');
            $table->unsignedInteger('created_at')->comment('Creado en');
            $table->comment('Esta tabla almacena los trabajos de la cola');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary()->comment('Identificador del lote');
            $table->string('name')->comment('Nombre del lote');
            $table->integer('total_jobs')->comment('Total de trabajos');
            $table->integer('pending_jobs')->comment('Trabajos pendientes');
            $table->integer('failed_jobs')->comment('Trabajos fallidos');
            $table->longText('failed_job_ids')->comment('Identificadores de trabajos fallidos');
            $table->mediumText('options')->nullable()->comment('Opciones');
            $table->integer('cancelled_at')->nullable()->comment('Cancelado en');
            $table->integer('created_at')->comment('Creado en');
            $table->integer('finished_at')->nullable()->comment('Finalizado en');
            $table->comment('Esta tabla almacena los lotes de trabajos');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
            $table->comment('Esta tabla almacena los trabajos fallidos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
