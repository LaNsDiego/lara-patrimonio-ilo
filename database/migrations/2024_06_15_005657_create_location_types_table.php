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
        Schema::create('location_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del tipo de ubicación');
            $table->text('image')->nullable()->comment('Imagen del tipo de ubicación');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de tipos de ubicaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_types');
    }
};
