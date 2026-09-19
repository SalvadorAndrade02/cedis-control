<?php

namespace App\Models;

use App\Enums\FuelAmountOption;
use App\Enums\NoFuelReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitFuelLoad extends Model
{
    protected $fillable = [

        'unit_id',

        'transfer_assignment_id',

        'amount_option',
        'amount',

        'no_fuel_reason',
        'reason_notes',

        'fueled_at',

        'registered_by',
        'registered_by_name',

        'ticket_storage_disk',
        'ticket_storage_path',
        'ticket_original_filename',
        'ticket_mime_type',
        'ticket_file_size',
        'ticket_file_hash',

        'observations',
    ];


    protected function casts(): array
    {
        return [

            'amount_option' =>
                FuelAmountOption::class,

            'no_fuel_reason' =>
                NoFuelReason::class,

            /*
             * Lo manejaremos como string decimal
             * para evitar problemas de precisión
             * con dinero y floats.
             */
            'amount' =>
                'decimal:2',

            'fueled_at' =>
                'datetime',

            'ticket_file_size' =>
                'integer',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | UNIDAD
    |--------------------------------------------------------------------------
    */

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASIGNACIÓN
    |--------------------------------------------------------------------------
    */

    public function transferAssignment(): BelongsTo
    {
        return $this->belongsTo(
            UnitTransferAssignment::class,
            'transfer_assignment_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USUARIO QUE REGISTRÓ LA GASOLINA
    |--------------------------------------------------------------------------
    */

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'registered_by'
        );
    }
}