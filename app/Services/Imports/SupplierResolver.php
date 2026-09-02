<?php

namespace App\Services\Imports;

use App\Models\Supplier;
use App\Services\Imports\DTOs\CfdiContext;
use RuntimeException;

class SupplierResolver
{
    public function resolve(
        CfdiContext $context
    ): Supplier {
        $rfc = $context->data->issuerRfc;

        if ($rfc === null) {
            throw new RuntimeException(
                'El XML no contiene RFC del emisor.'
            );
        }

        $supplier = Supplier::query()
            ->where('rfc', strtoupper(trim($rfc)))
            ->where('active', true)
            ->first();

        if (! $supplier) {
            throw new RuntimeException(
                "Proveedor no registrado para RFC {$rfc}."
            );
        }

        return $supplier;
    }
}
