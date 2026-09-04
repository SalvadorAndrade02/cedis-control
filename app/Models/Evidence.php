<?php

namespace App\Models;

use App\Enums\EvidenceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{
    use SoftDeletes;

    protected $table = 'evidences';

    protected $fillable = [
        'unit_milestone_id',
        'evidence_requirement_id',
        'type',
        'storage_disk',
        'storage_path',
        'original_filename',
        'mime_type',
        'file_size',
        'file_hash',
        'captured_at',
        'description',
        'uploaded_by',
        'deleted_by',
        'deletion_reason',
    ];

    protected $casts = [
        'type' => EvidenceType::class,
        'captured_at' => 'datetime',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(
            UnitMilestone::class,
            'unit_milestone_id'
        );
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            EvidenceRequirement::class,
            'evidence_requirement_id'
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }
}
