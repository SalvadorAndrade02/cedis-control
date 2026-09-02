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
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            // Identificador principal de la unidad.
            $table->string('vin', 50)->unique();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->string('model', 150)->nullable();
            $table->string('version', 150)->nullable();

            $table->unsignedSmallInteger('year')->nullable();

            $table->string('exterior_color', 100)->nullable();
            $table->string('interior_color', 100)->nullable();

            $table->string('engine_number', 100)->nullable();

            /*
         * Estado resumido del expediente.
         *
         * Ejemplos:
         * IMPORTED
         * ARRIVAL_PENDING
         * ARRIVAL_COMPLETED
         * ASSEMBLY_PENDING
         * ASSEMBLY_COMPLETED
         * DELIVERY_PENDING
         * COMPLETED
         */
            $table->string('status', 50)->default('IMPORTED');

            $table->timestamps();

            $table->index('status');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
