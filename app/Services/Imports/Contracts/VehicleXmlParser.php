<?php

namespace App\Services\Imports\Contracts;

use App\Models\Supplier;
use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\UnitImportData;

interface VehicleXmlParser
{
    /**
     * @return array<UnitImportData>
     */
    public function parse(
        CfdiContext $context,
        Supplier $supplier
    ): array;
}
