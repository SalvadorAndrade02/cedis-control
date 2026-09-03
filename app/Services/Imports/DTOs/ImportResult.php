<?php

namespace App\Services\Imports\DTOs;

use App\Models\Document;
use App\Models\Supplier;
use Illuminate\Support\Collection;

final readonly class ImportResult
{
    public function __construct(
        public Supplier $supplier,
        public Document $xmlDocument,
        public ?Document $pdfDocument,
        public Collection $units,
    ) {}
}
