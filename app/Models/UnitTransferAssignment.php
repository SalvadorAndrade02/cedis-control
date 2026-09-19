<?php

namespace App\Models;

use App\Enums\TransferAssignmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitTransferAssignment extends Model
{
    protected $fillable = [
        'unit_id',

        'transporter_user_id',
        'transporter_name',

        'assigned_by',
        'assigned_by_name',

        'status',

        'origin_name',
        'destination_name',

        'assigned_at',
        'completed_at',
        'cancelled_at',

        'notes',
    ];


    protected function casts(): array
    {
        return [

            'status' =>
                TransferAssignmentStatus::class,

            'assigned_at' =>
                'datetime',

            'completed_at' =>
                'datetime',

            'cancelled_at' =>
                'datetime',
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
    | TRASLADISTA
    |--------------------------------------------------------------------------
    */

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'transporter_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUIÉN REALIZÓ LA ASIGNACIÓN
    |--------------------------------------------------------------------------
    */

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CARGAS DE GASOLINA
    |--------------------------------------------------------------------------
    */

    public function fuelLoads(): HasMany
    {
        return $this->hasMany(
            UnitFuelLoad::class,
            'transfer_assignment_id'
        );
    }
}