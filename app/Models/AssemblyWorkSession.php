<?php

namespace App\Models;

use App\Enums\AssemblyWorkStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssemblyWorkSession extends Model
{
    protected $fillable = [
        'unit_id',
        'unit_milestone_id',

        'status',

        'started_at',
        'completed_at',

        'started_by',
        'started_by_name',

        'current_worker_id',
        'current_worker_name',

        'completed_by',
        'completed_by_name',

        'total_active_seconds',
        'total_paused_seconds',
    ];


    protected function casts(): array
    {
        return [
            'status' =>
                AssemblyWorkStatus::class,

            'started_at' =>
                'datetime',

            'completed_at' =>
                'datetime',

            'total_active_seconds' =>
                'integer',

            'total_paused_seconds' =>
                'integer',
        ];
    }


    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class
        );
    }


    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            UnitMilestone::class,
            'unit_milestone_id'
        );
    }


    public function pauses(): HasMany
    {
        return $this->hasMany(
            AssemblyWorkPause::class
        );
    }


    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'started_by'
        );
    }


    public function currentWorker(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'current_worker_id'
        );
    }


    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }
}