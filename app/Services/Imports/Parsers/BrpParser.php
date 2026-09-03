<?php

namespace App\Services\Imports\Parsers;

use App\Enums\VinSource;
use App\Models\Supplier;
use App\Services\Imports\Contracts\VehicleXmlParser;
use App\Services\Imports\DTOs\CfdiContext;
use App\Services\Imports\DTOs\UnitImportData;
use DOMElement;
use RuntimeException;

class BrpParser implements VehicleXmlParser
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
                'El CFDI BRP no contiene conceptos.'
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

            $description = trim(
                $concept->getAttribute('Descripcion')
            );

            /*
             * En BRP el VIN no viene en NoIdentificacion.
             * Debemos localizarlo dentro de la Addenda.
             */
            $conceptEx = $this->findConceptEx(
                $context,
                $identifier
            );

            if (! $conceptEx instanceof DOMElement) {
                continue;
            }

            $vin = $this->extractTextNode(
                $context,
                $conceptEx,
                './fx:NumeroDeSerie'
            );

            if (
                $vin === null
                || ! $this->looksLikeVin($vin)
            ) {
                continue;
            }

            $engineNumber = $this->extractTextNode(
                $context,
                $conceptEx,
                './fx:CodigoSKU'
            );

            $purchaseOrder = $this->extractPurchaseOrder(
                $context,
                $conceptEx
            );

            $pedimento = $this->extractPedimento(
                $context,
                $concept
            );

            $modelCode = $this->extractTextNode(
                $context,
                $conceptEx,
                './fx:Modelo'
            );

            $year = $this->extractYear(
                $description
            );

            $model = $this->normalizeModel(
                $description
            );

            $units[] = new UnitImportData(
                vin: strtoupper($vin),

                brand: 'CAN-AM',

                model: $model,
                version: null,

                year: $year,

                exteriorColor: null,
                interiorColor: null,

                engineNumber: $engineNumber,

                pedimento: $pedimento,
                purchaseOrder: $purchaseOrder,

                conceptIdentifier: $identifier !== ''
                    ? $identifier
                    : null,

                conceptIndex: $index,

                rawDescription: $description,

                vinSource: VinSource::ADDENDA_SERIAL_NUMBER,

                extraData: [
                    'model_code' => $modelCode,
                    'supplier_description' => $description,
                ],

                /*
                 * La descripción BRP viene abreviada.
                 * Es mejor revisarla visualmente.
                 */
                requiresReview: true,
            );
        }

        if ($units === []) {
            throw new RuntimeException(
                'No se encontró ninguna unidad válida en el CFDI BRP.'
            );
        }

        return $units;
    }

    private function findConceptEx(
        CfdiContext $context,
        string $identifier
    ): ?DOMElement {
        $conceptExNodes = $context->xpath->query(
            '//fx:ConceptoEx'
        );

        if (
            $conceptExNodes === false
            || $conceptExNodes->length === 0
        ) {
            return null;
        }

        /*
         * En el XML real hay un ConceptoEx
         * asociado al concepto del vehículo.
         *
         * Para el MVP tomamos el primero que tenga
         * NumeroDeSerie válido.
         */
        foreach ($conceptExNodes as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $vin = $this->extractTextNode(
                $context,
                $node,
                './fx:NumeroDeSerie'
            );

            if (
                $vin !== null
                && $this->looksLikeVin($vin)
            ) {
                return $node;
            }
        }

        return null;
    }

    private function extractTextNode(
        CfdiContext $context,
        DOMElement $parent,
        string $expression
    ): ?string {
        $nodes = $context->xpath->query(
            $expression,
            $parent
        );

        $node = $nodes?->item(0);

        if (! $node) {
            return null;
        }

        $value = trim(
            $node->textContent
        );

        return $value !== ''
            ? $value
            : null;
    }

    private function extractPurchaseOrder(
        CfdiContext $context,
        DOMElement $conceptEx
    ): ?string {
        $nodes = $context->xpath->query(
            './fx:OrdenDeCompra/fx:Numero',
            $conceptEx
        );

        $node = $nodes?->item(0);

        if (! $node) {
            return null;
        }

        $value = trim(
            $node->textContent
        );

        return $value !== ''
            ? $value
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

    private function looksLikeVin(
        string $value
    ): bool {
        return preg_match(
            '/^[A-HJ-NPR-Z0-9]{17}$/i',
            $value
        ) === 1;
    }

    private function extractYear(
        string $description
    ): ?int {
        /*
         * El ejemplo BRP:
         * ATV OUTL MAX BAC 1000R CA INT 27
         *
         * No contiene "2026" explícitamente.
         * Por eso no inventamos año.
         */
        if (
            preg_match(
                '/\b(20\d{2})\b/',
                $description,
                $matches
            ) === 1
        ) {
            return (int) $matches[1];
        }

        return null;
    }

    private function normalizeModel(
        string $description
    ): ?string {
        $value = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $description
            )
        );

        return $value !== ''
            ? $value
            : null;
    }
}
