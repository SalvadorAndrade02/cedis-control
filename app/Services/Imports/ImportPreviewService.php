<?php

namespace App\Services\Imports;

use App\Models\Unit;

class ImportPreviewService
{
    public function __construct(
        private readonly CfdiReader $cfdiReader,
        private readonly SupplierResolver $supplierResolver,
        private readonly VehicleParserResolver $vehicleParserResolver,
    ) {
    }

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


        /*
        |--------------------------------------------------------------------------
        | VIN DETECTADOS
        |--------------------------------------------------------------------------
        |
        | Normalizamos antes de comparar.
        |
        */

        $vins = collect($parsedUnits)
            ->pluck('vin')
            ->filter()
            ->map(
                fn($vin) =>
                strtoupper(
                    trim(
                        (string) $vin
                    )
                )
            )
            ->values();


        /*
        |--------------------------------------------------------------------------
        | VIN YA EXISTENTES
        |--------------------------------------------------------------------------
        */

        $existingVins = Unit::query()
            ->whereIn(
                'vin',
                $vins->all()
            )
            ->pluck('vin')
            ->map(
                fn($vin) =>
                strtoupper(
                    trim(
                        (string) $vin
                    )
                )
            )
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | UNIDADES PARA PREVIEW
        |--------------------------------------------------------------------------
        */

        $units = collect($parsedUnits)
            ->map(
                function ($unit) use ($existingVins) {

                    $normalizedVin =
                        strtoupper(
                            trim(
                                (string) $unit->vin
                            )
                        );


                    return [

                        'vin' =>
                            $normalizedVin,

                        'brand' =>
                            strtoupper(
                                trim(
                                    (string) $unit->brand
                                )
                            ),

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

                        /*
                         * Datos informativos del parser.
                         *
                         * Estos no son editables desde
                         * la pantalla.
                         */
                        'vin_source' =>
                            $unit->vinSource->value,

                        'requires_review' =>
                            $unit->requiresReview,

                        /*
                         * Importante:
                         *
                         * Este duplicate describe el VIN
                         * ORIGINAL detectado por el parser.
                         *
                         * Si el usuario lo corrige después,
                         * ImportUnits.php hará nuevamente
                         * la validación con el VIN editable.
                         */
                        'duplicate' =>
                            in_array(
                                $normalizedVin,
                                $existingVins,
                                true
                            ),
                    ];
                }
            )
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | DATOS FACTURA
        |--------------------------------------------------------------------------
        */

        $series =
            $context->data->series;

        $folio =
            $context->data->folio;


        $pairKey = trim(
            ($series ?? '')
            . ($folio ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN NOMBRES XML / PDF
        |--------------------------------------------------------------------------
        */

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
         * Es solamente una advertencia.
         *
         * No bloqueamos porque los proveedores
         * pueden utilizar convenciones distintas
         * para nombrar XML y PDF.
         */
        $fileNamesMatch =
            $pdfBase === null
            || strcasecmp(
                $xmlBase,
                $pdfBase
            ) === 0;


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return [

            'supplier' => [

                'id' =>
                    $supplier->id,

                'name' =>
                    $supplier->name,

                'rfc' =>
                    $supplier->rfc,

                'parser_key' =>
                    $supplier->parser_key,
            ],


            'invoice' => [

                'series' =>
                    $series,

                'folio' =>
                    $folio,

                'pair_key' =>
                    $pairKey,

                'uuid' =>
                    $context->data->uuid,

                'issued_at' =>
                    $context
                        ->data
                        ->issuedAt
                            ?->format(
                            'Y-m-d H:i:s'
                        ),

                'receiver_name' =>
                    $context
                        ->data
                        ->receiverName,

                'receiver_rfc' =>
                    $context
                        ->data
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


            'units' =>
                $units,


            /*
             * Esto sigue siendo útil únicamente
             * como advertencia del análisis original.
             *
             * Ya NO debe utilizarse para bloquear
             * directamente el botón de importación.
             */
            'has_duplicates' =>
                $existingVins !== [],


            'requires_review' =>
                collect(
                    $parsedUnits
                )->contains(
                        fn($unit) =>
                        $unit->requiresReview
                    ),
        ];
    }
}