<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(
            'unit_transfer_assignments',
            function (Blueprint $table) {

                $table->id();


                /*
                |--------------------------------------------------------------------------
                | UNIDAD
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId('unit_id')
                    ->constrained('units')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | TRASLADISTA
                |--------------------------------------------------------------------------
                |
                | ID actual + snapshot del nombre.
                |
                */

                $table
                    ->foreignId('transporter_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'transporter_name',
                        150
                    );


                /*
                |--------------------------------------------------------------------------
                | QUIÉN REALIZÓ LA ASIGNACIÓN
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId('assigned_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'assigned_by_name',
                        150
                    );


                /*
                |--------------------------------------------------------------------------
                | ESTADO
                |--------------------------------------------------------------------------
                |
                | ASSIGNED
                | COMPLETED
                | CANCELLED
                |
                */

                $table
                    ->string(
                        'status',
                        30
                    );


                /*
                |--------------------------------------------------------------------------
                | FECHAS
                |--------------------------------------------------------------------------
                */

                $table
                    ->timestamp(
                        'assigned_at'
                    );

                $table
                    ->timestamp(
                        'completed_at'
                    )
                    ->nullable();

                $table
                    ->timestamp(
                        'cancelled_at'
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | OBSERVACIONES
                |--------------------------------------------------------------------------
                */

                $table
                    ->text('notes')
                    ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | ÍNDICES
                |--------------------------------------------------------------------------
                */

                $table->index([
                    'unit_id',
                    'status',
                ]);

                $table->index([
                    'transporter_user_id',
                    'status',
                ]);

                $table->index(
                    'assigned_at'
                );
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'unit_transfer_assignments'
        );
    }
};