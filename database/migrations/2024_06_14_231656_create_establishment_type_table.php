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
        Schema::create('establishment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del tipo de establecimiento');
            $table->string('description')->nullable()->comment('Descripción del tipo de establecimiento');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de tipos de establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishment_types');
    }
};
