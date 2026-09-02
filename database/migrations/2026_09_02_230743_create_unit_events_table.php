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
        Schema::create('unit_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            /*
         * UNIT_IMPORTED
         * ARRIVAL_STARTED
         * ARRIVAL_COMPLETED
         * ASSEMBLY_STARTED
         * ASSEMBLY_COMPLETED
         * DELIVERY_STARTED
         * DELIVERY_COMPLETED
         * EVIDENCE_ADDED
         * EVIDENCE_REMOVED
         */
            $table->string('event_type', 80);

            $table->string('title', 150)->nullable();

            $table->text('description')->nullable();

            /*
         * Permite relacionar el evento con algo concreto:
         *
         * UnitMilestone
         * Document
         * Evidence
         * CarrierDelivery
         */
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
         * Datos adicionales del evento.
         */
            $table->json('metadata')->nullable();

            /*
         * Los eventos son históricos.
         * No necesitamos updated_at.
         */
            $table->timestamp('created_at')->useCurrent();

            $table->index(['unit_id', 'created_at']);
            $table->index('event_type');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_events');
    }
};
