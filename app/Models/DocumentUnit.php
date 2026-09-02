<?php

namespace App\Models;

use App\Enums\VinSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentUnit extends Model
{
    protected $fillable = [
        'document_id',
        'unit_id',
        'concept_index',
        'concept_identifier',
        'raw_description',
        'pedimento',
        'purchase_order',
        'vin_source',
        'parsed_vehicle_data',
    ];

    protected $casts = [
        'concept_index' => 'integer',

        'vin_source' => VinSource::class,

        'parsed_vehicle_data' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
