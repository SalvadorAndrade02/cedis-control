<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(
            'unit_fuel_loads',
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
                | ASIGNACIÓN DE TRASLADO
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'transfer_assignment_id'
                    )
                    ->constrained(
                        'unit_transfer_assignments'
                    )
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | IMPORTE
                |--------------------------------------------------------------------------
                |
                | amount_option:
                |
                | FIXED
                | OTHER
                |
                | amount:
                |
                | 500.00
                | 300.00
                | 200.00
                | 150.00
                | cualquier otro importe
                | 0.00
                |
                */

                $table
                    ->string(
                        'amount_option',
                        20
                    );

                $table
                    ->decimal(
                        'amount',
                        10,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | SIN CARGA DE GASOLINA
                |--------------------------------------------------------------------------
                |
                | Sólo se utilizan cuando amount = 0.
                |
                */

                $table
                    ->string(
                        'no_fuel_reason',
                        50
                    )
                    ->nullable();

                $table
                    ->text(
                        'reason_notes'
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | FECHA
                |--------------------------------------------------------------------------
                */

                $table
                    ->timestamp(
                        'fueled_at'
                    );


                /*
                |--------------------------------------------------------------------------
                | RESPONSABLE
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'registered_by'
                    )
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->string(
                        'registered_by_name',
                        150
                    );


                /*
                |--------------------------------------------------------------------------
                | TICKET
                |--------------------------------------------------------------------------
                |
                | Si amount > 0:
                | ticket obligatorio.
                |
                | Si amount = 0:
                | ticket puede ser null.
                |
                */

                $table
                    ->string(
                        'ticket_storage_disk',
                        50
                    )
                    ->nullable();

                $table
                    ->string(
                        'ticket_storage_path',
                        1000
                    )
                    ->nullable();

                $table
                    ->string(
                        'ticket_original_filename',
                        255
                    )
                    ->nullable();

                $table
                    ->string(
                        'ticket_mime_type',
                        100
                    )
                    ->nullable();

                $table
                    ->unsignedBigInteger(
                        'ticket_file_size'
                    )
                    ->nullable();

                $table
                    ->string(
                        'ticket_file_hash',
                        64
                    )
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | OBSERVACIONES
                |--------------------------------------------------------------------------
                */

                $table
                    ->text(
                        'observations'
                    )
                    ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | ÍNDICES
                |--------------------------------------------------------------------------
                */

                $table->index([
                    'unit_id',
                    'fueled_at',
                ]);

                $table->index([
                    'transfer_assignment_id',
                    'fueled_at',
                ]);

                $table->index([
                    'registered_by',
                    'fueled_at',
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'unit_fuel_loads'
        );
    }
};