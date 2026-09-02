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
        Schema::create('evidence_requirements', function (Blueprint $table) {
            $table->id();

            /*
         * ARRIVAL
         * ASSEMBLY_COMPLETED
         * CARRIER_DELIVERY
         */
            $table->string('stage', 50);

            /*
         * Si es NULL aplica a todas las marcas.
         *
         * Si contiene brand_id puede existir,
         * por ejemplo, una evidencia específica
         * solamente para Polaris.
         */
            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            /*
         * Código técnico estable.
         *
         * FRONT
         * REAR
         * VIN
         * ODOMETER
         * LEFT_SIDE
         * etc.
         */
            $table->string('code', 100);

            /*
         * Nombre mostrado al operador.
         */
            $table->string('name', 150);

            $table->text('description')->nullable();

            /*
         * IMAGE
         * VIDEO
         * DOCUMENT
         * SIGNATURE
         */
            $table->string('evidence_type', 30)->default('IMAGE');

            $table->boolean('required')->default(true);

            $table->unsignedSmallInteger('minimum_quantity')->default(1);

            $table->unsignedSmallInteger('maximum_quantity')->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['stage', 'active']);
            $table->index('brand_id');

            $table->unique([
                'stage',
                'brand_id',
                'code'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence_requirements');
    }
};
