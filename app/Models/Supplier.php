<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'rfc',
        'parser_key',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(
            Brand::class,
            'supplier_brands'
        )->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
