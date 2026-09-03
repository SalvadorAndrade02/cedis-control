<?php

namespace App\Models;

use App\Enums\UnitEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'unit_id',
        'event_type',
        'title',
        'description',
        'reference_type',
        'reference_id',
        'performed_by',
        'metadata',
    ];

    protected $casts = [
        'event_type' => UnitEventType::class,
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}
