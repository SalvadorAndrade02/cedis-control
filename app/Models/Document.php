<?php

namespace App\Models;

use App\Enums\DocumentProcessingStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    protected $fillable = [
        'supplier_id',
        'document_type',
        'original_filename',
        'storage_disk',
        'storage_path',
        'file_hash',
        'mime_type',
        'file_size',
        'pair_key',
        'processing_status',
        'processed_at',
        'uploaded_by',
    ];

    protected $casts = [
        'document_type' => DocumentType::class,

        'processing_status' =>
        DocumentProcessingStatus::class,

        'processed_at' => 'datetime',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(
            Unit::class,
            'document_units'
        )->withTimestamps();
    }

    public function documentUnits(): HasMany
    {
        return $this->hasMany(DocumentUnit::class);
    }

    public function invoiceData(): HasOne
    {
        return $this->hasOne(InvoiceData::class);
    }
}
