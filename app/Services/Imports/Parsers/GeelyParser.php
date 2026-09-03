<?php

namespace App\Services\Imports\Parsers;

use App\Enums\VinSource;
use App\Models\Supplier;
use App\Services\Imports\Contracts\VehicleXmlParser;
use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\UnitImportData;
use DOMElement;
use RuntimeException;

class GeelyParser implements VehicleXmlParser
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
                'El CFDI Geely no contiene conceptos.'
            );
        }

        $units = [];

        foreach ($concepts as $index => $concept) {
            if (! $concept instanceof DOMElement) {
                continue;
            }

            /*
             * Geely incorpora el complemento oficial
             * VentaVehiculos.
             */
            $vehicleNodes = $context->xpath->query(
                './/ventavehiculos:VentaVehiculos',
                $concept
            );

            $vehicleNode = $vehicleNodes?->item(0);

            if (! $vehicleNode instanceof DOMElement) {
                continue;
            }

            $vin = trim(
                $vehicleNode->getAttribute('Niv')
            );

            if (! $this->looksLikeVin($vin)) {
                continue;
            }

            $description = trim(
                $concept->getAttribute('Descripcion')
            );

            $identifier = trim(
                $concept->getAttribute('NoIdentificacion')
            );

            $year = $this->extractInteger(
                $description,
                '/Año\s+modelo\s*:\s*(20\d{2})/iu'
            );

            $exteriorColor = $this->extractText(
                $description,
                '/Color\s+Exterior\s*:\s*([^;]+)/iu'
            );

            $interiorColor = $this->extractText(
                $description,
                '/Color\s+Interior\s*:\s*([^;]+)/iu'
            );

            $engineNumber = $this->extractText(
                $description,
                '/Número\s+de\s+Motor\s*:\s*([^;]+)/iu'
            );

            $fuelType = $this->extractText(
                $description,
                '/Tipo\s+de\s+Combustible\s*:\s*([^;]+)/iu'
            );

            $model = $this->extractModel($description);

            $pedimento = $this->extractPedimento(
                $context,
                $concept
            );

            $claveVehicular = trim(
                $vehicleNode->getAttribute(
                    'ClaveVehicular'
                )
            );

            $customsData = $this->extractCustomsData(
                $context,
                $vehicleNode
            );

            $units[] = new UnitImportData(
                vin: strtoupper($vin),

                /*
                 * El PDF entregado identifica la marca
                 * como Geely aunque la descripción diga
                 * Lynk & Co 09 Pro.
                 */
                brand: 'GEELY',

                model: $model,
                version: null,

                year: $year,

                exteriorColor: $exteriorColor,
                interiorColor: $interiorColor,

                engineNumber: $engineNumber,

                pedimento: $pedimento,
                purchaseOrder: null,

                conceptIdentifier: $identifier !== ''
                    ? $identifier
                    : null,

                conceptIndex: $index,

                rawDescription: $description,

                vinSource: VinSource::VEHICLE_COMPLEMENT_NIV,

                extraData: [
                    'clave_vehicular' =>
                    $claveVehicular !== ''
                        ? $claveVehicular
                        : null,

                    'fuel_type' => $fuelType,

                    'customs' => $customsData,
                ],

                requiresReview: $model === null
                    || $year === null
            );
        }

        if ($units === []) {
            throw new RuntimeException(
                'No se encontró ninguna unidad válida en el CFDI Geely.'
            );
        }

        return $units;
    }

    private function looksLikeVin(string $value): bool
    {
        return preg_match(
            '/^[A-HJ-NPR-Z0-9]{17}$/i',
            $value
        ) === 1;
    }

    private function extractInteger(
        string $description,
        string $pattern
    ): ?int {
        if (
            preg_match(
                $pattern,
                $description,
                $matches
            ) !== 1
        ) {
            return null;
        }

        return (int) trim($matches[1]);
    }

    private function extractText(
        string $description,
        string $pattern
    ): ?string {
        if (
            preg_match(
                $pattern,
                $description,
                $matches
            ) !== 1
        ) {
            return null;
        }

        $value = trim($matches[1]);

        return $value !== ''
            ? $value
            : null;
    }

    private function extractModel(
        string $description
    ): ?string {
        /*
         * Ejemplo real:
         *
         * Lynk & Co 09 Pro, SUV MHEV, ...
         *
         * Conservamos "Lynk & Co 09 Pro"
         * como modelo comercial.
         */
        $parts = explode(',', $description, 2);

        $model = trim($parts[0] ?? '');

        return $model !== ''
            ? $model
            : null;
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
            $node->getAttribute(
                'NumeroPedimento'
            )
        );

        if ($value === '') {
            return null;
        }

        return preg_replace(
            '/\s+/',
            ' ',
            $value
        );
    }

    private function extractCustomsData(
        CfdiContext $context,
        DOMElement $vehicleNode
    ): array {
        $nodes = $context->xpath->query(
            './ventavehiculos:InformacionAduanera',
            $vehicleNode
        );

        $node = $nodes?->item(0);

        if (! $node instanceof DOMElement) {
            return [];
        }

        return [
            'aduana' =>
            $node->getAttribute('aduana')
                ?: null,

            'fecha' =>
            $node->getAttribute('fecha')
                ?: null,

            'numero' =>
            $node->getAttribute('numero')
                ?: null,
        ];
    }
}
