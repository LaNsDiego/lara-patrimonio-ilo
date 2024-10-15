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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('establishments')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('acronym')->unique();
            $table->string('name');
            $table->unsignedBigInteger('establishment_type_id')->comment('Identificador del tipo de establecimiento');
            $table->foreign('establishment_type_id')->references('id')->on('establishment_types')->comment('Clave foránea del tipo de establecimiento');
            $table->unsignedBigInteger('location_id')->nullable()->comment('Identificador de la ubicación');
            $table->foreign('location_id')->references('id')->on('locations')->comment('Clave foránea de la ubicación');
            $table->integer('responsible_id')->nullable()->comment('Identificador del responsable');

            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
