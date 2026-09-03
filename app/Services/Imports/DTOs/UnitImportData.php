<?php

namespace App\Services\Imports\DTOs;

use App\Enums\VinSource;

final readonly class UnitImportData
{
    public function __construct(
        public string $vin,

        public ?string $brand,
        public ?string $model,
        public ?string $version,

        public ?int $year,

        public ?string $exteriorColor,
        public ?string $interiorColor,

        public ?string $engineNumber,

        public ?string $pedimento,
        public ?string $purchaseOrder,

        public ?string $conceptIdentifier,
        public ?int $conceptIndex,

        public ?string $rawDescription,

        public VinSource $vinSource,

        public array $extraData = [],

        /*
         * Algunos proveedores entregan datos menos estructurados.
         * Si necesitamos revisión humana:
         */
        public bool $requiresReview = false,
    ) {}
}
