<?php

namespace App\Services\Imports;

use App\Models\Unit;

class ImportPreviewService
{
    public function __construct(
        private readonly CfdiReader $cfdiReader,
        private readonly SupplierResolver $supplierResolver,
        private readonly VehicleParserResolver $vehicleParserResolver,
    ) {}

    public function preview(
        string $xmlPath,
        string $xmlOriginalFilename,
        ?string $pdfOriginalFilename = null,
    ): array {
        $context = $this->cfdiReader->read(
            $xmlPath
        );

        $supplier = $this
            ->supplierResolver
            ->resolve($context);

        $parser = $this
            ->vehicleParserResolver
            ->resolve($supplier);

        $parsedUnits = $parser->parse(
            $context,
            $supplier
        );

        $vins = collect($parsedUnits)
            ->pluck('vin')
            ->map(
                fn($vin) =>
                strtoupper(trim($vin))
            )
            ->values();

        $existingVins = Unit::query()
            ->whereIn('vin', $vins)
            ->pluck('vin')
            ->all();

        $units = collect($parsedUnits)
            ->map(function ($unit) use (
                $existingVins
            ) {
                return [
                    'vin' =>
                    $unit->vin,

                    'brand' =>
                    $unit->brand,

                    'model' =>
                    $unit->model,

                    'version' =>
                    $unit->version,

                    'year' =>
                    $unit->year,

                    'exterior_color' =>
                    $unit->exteriorColor,

                    'interior_color' =>
                    $unit->interiorColor,

                    'engine_number' =>
                    $unit->engineNumber,

                    'pedimento' =>
                    $unit->pedimento,

                    'purchase_order' =>
                    $unit->purchaseOrder,

                    'vin_source' =>
                    $unit->vinSource->value,

                    'requires_review' =>
                    $unit->requiresReview,

                    'duplicate' =>
                    in_array(
                        $unit->vin,
                        $existingVins,
                        true
                    ),
                ];
            })
            ->values()
            ->all();

        $series = $context->data->series;
        $folio = $context->data->folio;

        $pairKey = trim(
            ($series ?? '')
                . ($folio ?? '')
        );

        $xmlBase = pathinfo(
            $xmlOriginalFilename,
            PATHINFO_FILENAME
        );

        $pdfBase = $pdfOriginalFilename
            ? pathinfo(
                $pdfOriginalFilename,
                PATHINFO_FILENAME
            )
            : null;

        /*
         * Es una advertencia, no bloqueo.
         *
         * Algunos proveedores podrían cambiar
         * convenciones de nombres.
         */
        $fileNamesMatch =
            $pdfBase === null
            || strcasecmp(
                $xmlBase,
                $pdfBase
            ) === 0;

        return [
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'rfc' => $supplier->rfc,
                'parser_key' =>
                $supplier->parser_key,
            ],

            'invoice' => [
                'series' => $series,
                'folio' => $folio,
                'pair_key' => $pairKey,

                'uuid' =>
                $context->data->uuid,

                'issued_at' =>
                $context->data
                    ->issuedAt
                    ?->format(
                        'Y-m-d H:i:s'
                    ),

                'receiver_name' =>
                $context->data
                    ->receiverName,

                'receiver_rfc' =>
                $context->data
                    ->receiverRfc,

                'currency' =>
                $context->data->currency,

                'total' =>
                $context->data->total,
            ],

            'files' => [
                'xml' =>
                $xmlOriginalFilename,

                'pdf' =>
                $pdfOriginalFilename,

                'names_match' =>
                $fileNamesMatch,
            ],

            'units' => $units,

            'has_duplicates' =>
            $existingVins !== [],

            'requires_review' =>
            collect($parsedUnits)
                ->contains(
                    fn($unit) =>
                    $unit->requiresReview
                ),
        ];
    }
}
