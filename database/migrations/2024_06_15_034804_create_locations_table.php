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
        Schema::create('locations', function (Blueprint $table) {
            $table->id()->comment('Identificador de la ubicación');
            $table->string('acronym')->nullable()->comment('Siglas del ubicacion.');
            $table->string('name')->comment('Nombre de la ubicación');
            $table->text('description')->comment('Descripción de la ubicación');
            $table->unsignedBigInteger('location_type_id')->comment('Identificador del tipo de ubicación');
            $table->string('geojson')->default('')->comment('GeoJSON de la ubicación');

            $table->foreign('location_type_id')->references('id')->on('location_types');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena las ubicaciones de los sectores.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
