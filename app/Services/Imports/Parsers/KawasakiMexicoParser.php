<?php

namespace App\Services\Imports\Parsers;

use App\Enums\VinSource;
use App\Models\Supplier;
use App\Services\Imports\Contracts\VehicleXmlParser;
use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\UnitImportData;
use DOMElement;
use RuntimeException;

class KawasakiMexicoParser implements VehicleXmlParser
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
                'El CFDI Kawasaki México no contiene conceptos.'
            );
        }

        $units = [];

        foreach ($concepts as $index => $concept) {
            if (! $concept instanceof DOMElement) {
                continue;
            }

            $description = trim(
                $concept->getAttribute('Descripcion')
            );

            /*
             * Queremos ignorar:
             * - seguro
             * - manejo
             * - otros conceptos
             */
            if (! $this->looksLikeVehicle($description)) {
                continue;
            }

            $vin = $this->extractText(
                $description,
                '/\bNIV\s+([A-HJ-NPR-Z0-9]{17})\b/i'
            );

            if (
                $vin === null
                || ! $this->looksLikeVin($vin)
            ) {
                continue;
            }

            $brand = $this->extractText(
                $description,
                '/MARCA\s+([A-Z0-9\-& ]+?)\s+\d+/i'
            );

            /*
             * Por este proveedor sabemos que
             * en el ejemplo es Triumph.
             */
            if (
                $brand !== null
                && str_contains(
                    strtoupper($brand),
                    'TRIUMPH'
                )
            ) {
                $brand = 'TRIUMPH';
            }

            $year = $this->extractInteger(
                $description,
                '/MODELO\s+(20\d{2})/i'
            );

            $engineNumber = $this->extractText(
                $description,
                '/MOTOR\s+([A-Z0-9\-]+)/i'
            );

            $color = $this->extractText(
                $description,
                '/COLOR\s+([A-Z0-9\-]+)/i'
            );

            $model = $this->extractModel(
                $description
            );

            $pedimento = $this->extractPedimento(
                $context,
                $concept
            );

            $identifier = trim(
                $concept->getAttribute(
                    'NoIdentificacion'
                )
            );

            $units[] = new UnitImportData(
                vin: strtoupper($vin),

                brand: $brand ?? 'TRIUMPH',

                model: $model,
                version: null,

                year: $year,

                exteriorColor: $color,
                interiorColor: null,

                engineNumber: $engineNumber,

                pedimento: $pedimento,
                purchaseOrder: null,

                conceptIdentifier: $identifier !== ''
                    ? $identifier
                    : null,

                conceptIndex: $index,

                rawDescription: $description,

                vinSource: VinSource::DESCRIPTION_NIV,

                extraData: [],

                requiresReview: $model === null
            );
        }

        if ($units === []) {
            throw new RuntimeException(
                'No se encontró ninguna unidad válida en el CFDI Kawasaki/Triumph.'
            );
        }

        return $units;
    }

    private function looksLikeVehicle(
        string $description
    ): bool {
        return preg_match(
            '/\b(MOTOCICLETA|NIV|MARCA\s+TRIUMPH)\b/i',
            $description
        ) === 1;
    }

    private function looksLikeVin(
        string $value
    ): bool {
        return preg_match(
            '/^[A-HJ-NPR-Z0-9]{17}$/i',
            $value
        ) === 1;
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

        $value = trim(
            $matches[1]
        );

        return $value !== ''
            ? $value
            : null;
    }

    private function extractInteger(
        string $description,
        string $pattern
    ): ?int {
        $value = $this->extractText(
            $description,
            $pattern
        );

        return $value !== null
            ? (int) $value
            : null;
    }

    private function extractModel(
        string $description
    ): ?string {
        /*
         * Ejemplo:
         *
         * YN1 / ROCKET 3 STORM R MODELO 2026
         */
        if (
            preg_match(
                '/\/\s*(.+?)\s+MODELO\s+20\d{2}/i',
                $description,
                $matches
            ) === 1
        ) {
            $value = trim(
                $matches[1]
            );

            return $value !== ''
                ? $value
                : null;
        }

        return null;
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
}
