<?php

namespace App\Services\Imports\Parsers;

use App\Enums\VinSource;
use App\Models\Supplier;
use App\Services\Imports\Contracts\VehicleXmlParser;
use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\UnitImportData;
use DOMElement;
use RuntimeException;

class PolarisParser implements VehicleXmlParser
{
    public function parse(
        CfdiContext $context,
        Supplier $supplier
    ): array {
        $concepts = $context->xpath->query(
            '/cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto'
        );

        if ($concepts === false || $concepts->length === 0) {
            throw new RuntimeException(
                'El CFDI no contiene conceptos.'
            );
        }

        $units = [];

        foreach ($concepts as $index => $concept) {
            if (! $concept instanceof DOMElement) {
                continue;
            }

            $identifier = trim(
                $concept->getAttribute('NoIdentificacion')
            );

            /*
             * Para los CFDI Polaris/Indian recibidos,
             * NoIdentificacion contiene directamente el VIN.
             */
            if (! $this->looksLikeVin($identifier)) {
                continue;
            }

            $description = trim(
                $concept->getAttribute('Descripcion')
            );

            $year = $this->extractYear($description);

            $brand = $this->extractBrand($description);

            $pedimento = $this->extractPedimento(
                $context,
                $concept
            );

            $vehicleDescription = $this
                ->cleanVehicleDescription(
                    $description,
                    $identifier,
                    $year
                );

            /*
             * Inicialmente NO queremos inventar una separación
             * modelo/color cuando no sea inequívoca.
             *
             * Para Polaris podemos mejorarla después con
             * reglas controladas.
             */
            [$model, $color] = $this
                ->extractModelAndColor(
                    $vehicleDescription,
                    $brand
                );

            $units[] = new UnitImportData(
                vin: strtoupper($identifier),

                brand: $brand,
                model: $model,
                version: null,

                year: $year,

                exteriorColor: $color,
                interiorColor: null,

                /*
                 * El XML Polaris que analizamos no contiene
                 * el motor como campo estructurado.
                 */
                engineNumber: null,

                pedimento: $pedimento,
                purchaseOrder: null,

                conceptIdentifier: $identifier,
                conceptIndex: $index,

                rawDescription: $description,

                vinSource: VinSource::CONCEPT_NO_IDENTIFICATION,

                extraData: [
                    'vehicle_description' =>
                    $vehicleDescription,
                ],

                requiresReview: $model === null
            );
        }

        if ($units === []) {
            throw new RuntimeException(
                'No se encontró ninguna unidad válida en el CFDI Polaris.'
            );
        }

        return $units;
    }

    private function looksLikeVin(string $value): bool
    {
        /*
         * VIN convencional:
         * 17 caracteres.
         * I, O y Q normalmente no se utilizan.
         */
        return preg_match(
            '/^[A-HJ-NPR-Z0-9]{17}$/i',
            $value
        ) === 1;
    }

    private function extractYear(
        string $description
    ): ?int {
        if (
            preg_match(
                '/^\s*(20\d{2})\b/',
                $description,
                $matches
            ) === 1
        ) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractBrand(
        string $description
    ): string {
        /*
         * Polaris Sales México también factura Indian.
         */
        if (
            preg_match(
                '/\bIndian\b/i',
                $description
            ) === 1
        ) {
            return 'INDIAN';
        }

        return 'POLARIS';
    }

    private function extractPedimento(
        CfdiContext $context,
        DOMElement $concept
    ): ?string {
        $nodes = $context->xpath->query(
            './cfdi:InformacionAduanera',
            $concept
        );

        $node = $nodes?->item(0);

        if (! $node instanceof DOMElement) {
            return null;
        }

        $value = trim(
            $node->getAttribute('NumeroPedimento')
        );

        return $value !== ''
            ? preg_replace('/\s+/', ' ', $value)
            : null;
    }

    private function cleanVehicleDescription(
        string $description,
        string $vin,
        ?int $year
    ): string {
        $value = $description;

        /*
         * Quitamos:
         * VIN:XXXX
         * Fecha de pedimento:...
         */
        $value = preg_replace(
            '/VIN\s*:\s*' .
                preg_quote($vin, '/') .
                '/i',
            '',
            $value
        );

        $value = preg_replace(
            '/Fecha\s+de\s+pedimento\s*:.+$/i',
            '',
            $value
        );

        if ($year !== null) {
            $value = preg_replace(
                '/^\s*' . $year . '\s*/',
                '',
                $value
            );
        }

        return trim(
            preg_replace('/\s+/', ' ', $value)
        );
    }

    private function extractModelAndColor(
        string $description,
        string $brand
    ): array {
        /*
         * Primera regla basada únicamente en el patrón
         * Polaris que ya tenemos confirmado:
         *
         * RANGER 500 - Stealth Gray
         */
        if (
            $brand === 'POLARIS'
            && str_contains($description, ' - ')
        ) {
            [$model, $color] = array_map(
                'trim',
                explode(' - ', $description, 2)
            );

            return [
                $model !== '' ? $model : null,
                $color !== '' ? $color : null,
            ];
        }

        /*
         * Indian viene con otro formato:
         * Indian Scout Bobber Black Metallic
         *
         * No vamos a adivinar todavía dónde termina
         * modelo y comienza color.
         */
        if ($brand === 'INDIAN') {
            $value = preg_replace(
                '/^Indian\s+/i',
                '',
                $description
            );

            return [
                trim($value) ?: null,
                null,
            ];
        }

        return [
            $description !== ''
                ? $description
                : null,
            null,
        ];
    }
}
