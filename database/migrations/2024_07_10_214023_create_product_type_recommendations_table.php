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
        Schema::create('product_type_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre de la recomendación');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de recomendaciones de tipos de productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_type_recommendations');
    }
};
