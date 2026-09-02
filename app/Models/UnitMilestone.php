<?php

namespace App\Models;

use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UnitMilestone extends Model
{
    protected $fillable = [
        'unit_id',
        'stage',
        'status',
        'occurred_at',
        'started_at',
        'completed_at',
        'completed_by',
        'observations',
        'metadata',
    ];

    protected $casts = [
        'stage' => MilestoneStage::class,
        'status' => MilestoneStatus::class,

        'occurred_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',

        'metadata' => 'array',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function carrierDelivery(): HasOne
    {
        return $this->hasOne(CarrierDelivery::class);
    }
}
