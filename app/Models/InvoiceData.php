<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceData extends Model
{
    protected $table = 'invoice_data';

    protected $fillable = [
        'document_id',
        'cfdi_version',
        'series',
        'folio',
        'uuid',
        'issued_at',
        'certified_at',
        'issuer_rfc',
        'issuer_name',
        'receiver_rfc',
        'receiver_name',
        'currency',
        'payment_method',
        'payment_form',
        'subtotal',
        'tax',
        'total',
        'raw_data',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'certified_at' => 'datetime',

        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',

        'raw_data' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
