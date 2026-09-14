<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(
            'assembly_work_sessions',
            function (Blueprint $table) {

                $table->id();

                $table
                    ->foreignId('unit_id')
                    ->constrained('units')
                    ->cascadeOnDelete();

                $table
                    ->foreignId('unit_milestone_id')
                    ->unique()
                    ->constrained('unit_milestones')
                    ->cascadeOnDelete();

                $table
                    ->string('status', 30)
                    ->index();

                $table->timestamp(
                    'started_at'
                );

                $table
                    ->timestamp('completed_at')
                    ->nullable();


                /*
                 * Quién inició originalmente.
                 */
                $table
                    ->foreignId('started_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'started_by_name',
                        150
                    )
                    ->nullable();


                /*
                 * Quién está trabajando
                 * actualmente.
                 */
                $table
                    ->foreignId('current_worker_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'current_worker_name',
                        150
                    )
                    ->nullable();


                /*
                 * Quién finalizó.
                 */
                $table
                    ->foreignId('completed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'completed_by_name',
                        150
                    )
                    ->nullable();


                /*
                 * Totales expresados en segundos.
                 */
                $table
                    ->unsignedBigInteger(
                        'total_active_seconds'
                    )
                    ->default(0);

                $table
                    ->unsignedBigInteger(
                        'total_paused_seconds'
                    )
                    ->default(0);

                $table->timestamps();


                $table->index([
                    'current_worker_id',
                    'status',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'assembly_work_sessions'
        );
    }
};