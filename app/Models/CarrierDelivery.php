<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarrierDelivery extends Model
{
    protected $fillable = [
        'unit_milestone_id',
        'carrier_id',
        'operator_name',
        'operator_identification',
        'operator_phone',
        'vehicle_plate',
        'vehicle_number',
        'transport_type',
        'delivered_at',
        'observations',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            UnitMilestone::class,
            'unit_milestone_id'
        );
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }
}
