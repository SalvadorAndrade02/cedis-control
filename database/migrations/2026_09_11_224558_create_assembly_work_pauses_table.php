<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(
            'assembly_work_pauses',
            function (Blueprint $table) {

                $table->id();

                $table
                    ->foreignId(
                        'assembly_work_session_id'
                    )
                    ->constrained(
                        'assembly_work_sessions'
                    )
                    ->cascadeOnDelete();

                $table
                    ->string('reason', 50)
                    ->index();

                $table->text('notes')
                    ->nullable();

                $table->timestamp(
                    'paused_at'
                );

                $table
                    ->timestamp('resumed_at')
                    ->nullable();

                $table
                    ->unsignedBigInteger(
                        'duration_seconds'
                    )
                    ->default(0);


                /*
                 * Quién pausó.
                 */
                $table
                    ->foreignId('paused_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'paused_by_name',
                        150
                    )
                    ->nullable();


                /*
                 * Quién reanudó.
                 *
                 * Puede ser diferente persona.
                 */
                $table
                    ->foreignId('resumed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'resumed_by_name',
                        150
                    )
                    ->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'assembly_work_pauses'
        );
    }
};