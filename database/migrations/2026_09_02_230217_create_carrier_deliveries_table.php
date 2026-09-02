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
        Schema::create('carrier_deliveries', function (Blueprint $table) {
            $table->id();

            /*
         * Debe apuntar a un milestone
         * de tipo CARRIER_DELIVERY.
         */
            $table->foreignId('unit_milestone_id')
                ->unique()
                ->constrained('unit_milestones')
                ->cascadeOnDelete();

            $table->foreignId('carrier_id')
                ->nullable()
                ->constrained('carriers')
                ->nullOnDelete();

            $table->string('operator_name', 150)->nullable();

            $table->string('operator_identification', 100)->nullable();

            $table->string('operator_phone', 30)->nullable();

            $table->string('vehicle_plate', 30)->nullable();

            $table->string('vehicle_number', 100)->nullable();

            $table->string('transport_type', 100)->nullable();

            $table->dateTime('delivered_at')->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();

            $table->index('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_deliveries');
    }
};
