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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary()->comment('llave de la cache');
            $table->mediumText('value')->comment('valor de la cache');
            $table->integer('expiration')->comment('tiempo de expiración');
            $table->comment('Esta tabla almacena la cache');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary()->comment('llave de la cache');
            $table->string('owner')->comment('propietario del lock');
            $table->integer('expiration')->comment('tiempo de expiración');
            $table->comment('Esta tabla almacena los locks de la cache');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
