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
        Schema::create('document_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            /*
         * Posición del concepto dentro del CFDI.
         * Útil si una factura contiene varios conceptos.
         */
            $table->unsignedSmallInteger('concept_index')->nullable();

            /*
         * NoIdentificacion / código de producto / SKU original.
         */
            $table->string('concept_identifier', 100)->nullable();

            /*
         * Descripción exactamente como llegó en el XML.
         */
            $table->text('raw_description')->nullable();

            $table->string('pedimento', 100)->nullable();

            $table->string('purchase_order', 100)->nullable();

            /*
         * Nos permitirá saber cómo encontramos el VIN.
         *
         * Ejemplos:
         *
         * CONCEPT_NO_IDENTIFICATION
         * VEHICLE_COMPLEMENT_NIV
         * ADDENDA_SERIAL_NUMBER
         * DESCRIPTION_NIV
         */
            $table->string('vin_source', 100)->nullable();

            /*
         * Información específica de cada proveedor.
         */
            $table->json('parsed_vehicle_data')->nullable();

            $table->timestamps();

            $table->unique(['document_id', 'unit_id']);

            $table->index('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_units');
    }
};
