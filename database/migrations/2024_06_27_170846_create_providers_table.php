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
        Schema::create('providers', function (Blueprint $table) {
            $table->id()->comment('Identificador del proveedor.');
            $table->string('type')->comment('Tipo de proveedor.');
            $table->string('name')->comment('Nombre del proveedor.');
            $table->string('document_type')->comment('Tipo de documento del proveedor.');
            $table->string('document_number')->comment('Número de documento del proveedor.');
            $table->string('phone_number')->nullable()->comment('Número de teléfono del proveedor.');
            $table->string('email')->nullable()->comment('Correo electrónico del proveedor.');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
