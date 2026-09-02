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
        Schema::create('unit_milestones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            /*
         * ARRIVAL
         * ASSEMBLY_COMPLETED
         * CARRIER_DELIVERY
         */
            $table->string('stage', 50);

            /*
         * PENDING
         * IN_PROGRESS
         * COMPLETED
         */
            $table->string('status', 30)->default('PENDING');

            /*
         * Fecha real del evento.
         *
         * Ej:
         * cuándo llegó físicamente
         * cuándo terminó armado
         * cuándo se entregó
         */
            $table->dateTime('occurred_at')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('observations')->nullable();

            /*
         * Datos adicionales futuros que no ameriten
         * todavía una columna propia.
         */
            $table->json('metadata')->nullable();

            $table->timestamps();

            /*
         * Una unidad solamente tendrá una etapa
         * ARRIVAL, una ASSEMBLY_COMPLETED,
         * y una CARRIER_DELIVERY.
         */
            $table->unique(['unit_id', 'stage']);

            $table->index('stage');
            $table->index('status');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_milestones');
    }
};
