<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'vin',
        'brand_id',
        'model',
        'version',
        'year',
        'exterior_color',
        'interior_color',
        'engine_number',
        'status',
        'deleted_by',
        'deletion_reason',
    ];

    protected $casts = [
        'year' => 'integer',
        'status' => UnitStatus::class,
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_units'
        )
            ->withPivot([
                'concept_index',
                'concept_identifier',
                'raw_description',
                'pedimento',
                'purchase_order',
                'vin_source',
                'parsed_vehicle_data',
            ])
            ->withTimestamps();
    }

    public function documentUnits(): HasMany
    {
        return $this->hasMany(DocumentUnit::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(UnitMilestone::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(UnitEvent::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }
}
