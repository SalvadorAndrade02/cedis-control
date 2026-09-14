<?php

namespace App\Models;

use App\Enums\AssemblyPauseReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssemblyWorkPause extends Model
{
    protected $fillable = [
        'assembly_work_session_id',

        'reason',
        'notes',

        'paused_at',
        'resumed_at',

        'duration_seconds',

        'paused_by',
        'paused_by_name',

        'resumed_by',
        'resumed_by_name',
    ];


    protected function casts(): array
    {
        return [
            'reason' =>
                AssemblyPauseReason::class,

            'paused_at' =>
                'datetime',

            'resumed_at' =>
                'datetime',

            'duration_seconds' =>
                'integer',
        ];
    }


    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AssemblyWorkSession::class,
            'assembly_work_session_id'
        );
    }


    public function pausedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paused_by'
        );
    }


    public function resumedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'resumed_by'
        );
    }
}