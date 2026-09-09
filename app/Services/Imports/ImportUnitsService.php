<?php

namespace App\Services\Imports;

use App\Enums\DocumentProcessingStatus;
use App\Enums\DocumentType;
use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\Brand;
use App\Models\Document;
use App\Models\DocumentUnit;
use App\Models\InvoiceData;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\UnitMilestone;
use App\Services\Imports\DTOs\ImportResult;
use App\Services\Imports\DTOs\UnitImportData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use App\Enums\VinSource;
use Throwable;
use App\Models\User;

class ImportUnitsService
{
    public function __construct(
        private readonly CfdiReader $cfdiReader,
        private readonly SupplierResolver $supplierResolver,
        private readonly VehicleParserResolver $vehicleParserResolver,
    ) {
    }

    private function nullableString(
        mixed $value
    ): ?string {

        if ($value === null) {
            return null;
        }


        $value =
            trim(
                (string) $value
            );


        return $value !== ''
            ? $value
            : null;
    }

    private function normalizeComparisonValue(
        mixed $value
    ): ?string {

        if ($value === null) {
            return null;
        }


        $value =
            trim(
                (string) $value
            );


        return $value !== ''
            ? $value
            : null;
    }

    public function import(
        string $xmlPath,
        ?string $pdfPath = null,
        ?int $userId = null,
        ?string $xmlOriginalFilename = null,
        ?string $pdfOriginalFilename = null,
        array $unitOverrides = [],
    ): ImportResult {
        if (!is_file($xmlPath)) {
            throw new RuntimeException(
                "El XML no existe: {$xmlPath}"
            );
        }

        if ($pdfPath !== null && !is_file($pdfPath)) {
            throw new RuntimeException(
                "El PDF no existe: {$pdfPath}"
            );
        }

        /*
         * Primero interpretamos todo.
         *
         * Todavía no escribimos nada en MySQL.
         */
        $context = $this->cfdiReader->read($xmlPath);

        $supplier = $this->supplierResolver
            ->resolve($context);

        $parser = $this->vehicleParserResolver
            ->resolve($supplier);

        $parsedUnits = $parser->parse(
            $context,
            $supplier
        );

        if ($parsedUnits === []) {
            throw new RuntimeException(
                'El XML no contiene unidades importables.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | APLICAR CORRECCIONES DE VISTA PREVIA
        |--------------------------------------------------------------------------
        |
        | Los parsers siguen siendo la fuente original.
        |
        | Si el usuario corrigió o completó información,
        | generamos nuevos UnitImportData con esos valores.
        |
        */

        $parsedUnits = $this->applyUnitOverrides(
            parsedUnits: $parsedUnits,
            unitOverrides: $unitOverrides,
        );


        /*
         * La validación final ocurre DESPUÉS
         * de aplicar las modificaciones.
         *
         * Por lo tanto también detectamos:
         *
         * - VIN editado que ya existe.
         * - VIN duplicados entre unidades.
         * - VIN vacío.
         */

        $this->validateUnitsBeforeImport(
            $parsedUnits
        );

        try {
            return DB::transaction(function () use ($xmlPath, $pdfPath, $userId, $context, $supplier, $parsedUnits, $xmlOriginalFilename, $pdfOriginalFilename) {
                /*
                 * La clave que relaciona XML + PDF.
                 *
                 * Ej:
                 *
                 * SIAA50536
                 * SA29952
                 * 0300054189
                 */
                $pairKey = $this->buildPairKey(
                    $context->data->series,
                    $context->data->folio,
                    $xmlOriginalFilename ?? basename($xmlPath)
                );

                $xmlDocument = $this->createDocument(
                    filePath: $xmlPath,
                    originalFilename: $xmlOriginalFilename ?? basename($xmlPath),
                    type: DocumentType::XML,
                    supplierId: $supplier->id,
                    pairKey: $pairKey,
                    userId: $userId,
                    processingStatus: $this->resolveProcessingStatus(
                        $parsedUnits
                    )
                );

                $invoiceData = InvoiceData::create([
                    'document_id' =>
                        $xmlDocument->id,

                    'cfdi_version' =>
                        $context->data->version,

                    'series' =>
                        $context->data->series,

                    'folio' =>
                        $context->data->folio,

                    'uuid' =>
                        $context->data->uuid,

                    'issued_at' =>
                        $context->data->issuedAt,

                    'certified_at' =>
                        $context->data->certifiedAt,

                    'issuer_rfc' =>
                        $context->data->issuerRfc,

                    'issuer_name' =>
                        $context->data->issuerName,

                    'receiver_rfc' =>
                        $context->data->receiverRfc,

                    'receiver_name' =>
                        $context->data->receiverName,

                    'currency' =>
                        $context->data->currency,

                    'payment_method' =>
                        $context->data->paymentMethod,

                    'payment_form' =>
                        $context->data->paymentForm,

                    'subtotal' =>
                        $context->data->subtotal,

                    'tax' =>
                        $context->data->tax,

                    'total' =>
                        $context->data->total,

                    'raw_data' => [
                        'parser_key' =>
                            $supplier->parser_key,

                        'source_file' =>
                            basename($xmlPath),
                    ],
                ]);

                $pdfDocument = null;

                if ($pdfPath !== null) {
                    $pdfDocument = $this->createDocument(
                        filePath: $pdfPath,
                        originalFilename: $pdfOriginalFilename ?? basename($pdfPath),
                        type: DocumentType::PDF,
                        supplierId: $supplier->id,
                        pairKey: $pairKey,
                        userId: $userId,
                        processingStatus: DocumentProcessingStatus::PROCESSED
                    );
                }

                $createdUnits = collect();

                foreach ($parsedUnits as $parsedUnit) {
                    $unit = $this->createUnit(
                        $parsedUnit
                    );

                    $this->attachDocumentToUnit(
                        document: $xmlDocument,
                        unit: $unit,
                        data: $parsedUnit
                    );

                    if ($pdfDocument !== null) {
                        $this->attachDocumentToUnit(
                            document: $pdfDocument,
                            unit: $unit,
                            data: $parsedUnit,
                            includeParsingMetadata: false
                        );
                    }

                    $this->createMilestones(
                        $unit
                    );

                    $this->createImportEvent(
                        unit: $unit,
                        document: $xmlDocument,
                        userId: $userId,
                        data: $parsedUnit
                    );

                    $createdUnits->push(
                        $unit
                    );
                }

                return new ImportResult(
                    supplier: $supplier,
                    xmlDocument: $xmlDocument,
                    pdfDocument: $pdfDocument,
                    units: $createdUnits,
                );
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param array<UnitImportData> $parsedUnits
     * @param array<int, array<string, mixed>> $unitOverrides
     *
     * @return array<UnitImportData>
     */
    private function applyUnitOverrides(
        array $parsedUnits,
        array $unitOverrides,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | SIN MODIFICACIONES
        |--------------------------------------------------------------------------
        */

        if ($unitOverrides === []) {
            return $parsedUnits;
        }


        /*
        |--------------------------------------------------------------------------
        | INDEXAR OVERRIDES
        |--------------------------------------------------------------------------
        |
        | Livewire envía:
        |
        | [
        |     'index' => 0,
        |     ...
        | ]
        |
        */

        $overridesByIndex = [];

        foreach ($unitOverrides as $override) {

            if (
                !array_key_exists(
                    'index',
                    $override
                )
            ) {
                continue;
            }

            $index = (int) $override['index'];

            $overridesByIndex[$index] =
                $override;
        }


        /*
        |--------------------------------------------------------------------------
        | CONSTRUIR UNIDADES DEFINITIVAS
        |--------------------------------------------------------------------------
        */

        $result = [];


        foreach (
            $parsedUnits
            as $index => $original
        ) {

            /*
             * Si no existe override para esta unidad,
             * conservamos exactamente el DTO original.
             */
            if (
                !isset(
                $overridesByIndex[$index]
            )
            ) {

                $result[] = $original;

                continue;
            }


            $override =
                $overridesByIndex[$index];


            /*
            |--------------------------------------------------------------------------
            | SEGURIDAD: VERIFICAR QUE EL PREVIEW
            | SIGUE CORRESPONDIENDO A ESTA UNIDAD
            |--------------------------------------------------------------------------
            */

            $originalVin =
                strtoupper(
                    trim(
                        $original->vin
                    )
                );


            $previewOriginalVin =
                strtoupper(
                    trim(
                        (string) (
                            $override['original_vin']
                            ?? ''
                        )
                    )
                );


            if (
                $previewOriginalVin !== ''
                && $previewOriginalVin !== $originalVin
            ) {

                throw new RuntimeException(
                    'La información de la vista previa ya no coincide con el XML. '
                    . 'Vuelve a analizar los documentos.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | VALORES FINALES
            |--------------------------------------------------------------------------
            */

            $finalVin =
                strtoupper(
                    trim(
                        (string) (
                            $override['vin']
                            ?? $original->vin
                        )
                    )
                );


            $finalBrand =
                strtoupper(
                    trim(
                        (string) (
                            $override['brand']
                            ?? $original->brand
                            ?? ''
                        )
                    )
                );


            $finalBrand =
                $finalBrand !== ''
                ? $finalBrand
                : null;


            $finalModel =
                $this->nullableString(
                    $override['model']
                    ?? $original->model
                );


            $finalVersion =
                $this->nullableString(
                    $override['version']
                    ?? $original->version
                );


            $finalYear =
                array_key_exists(
                    'year',
                    $override
                )
                ? (
                    $override['year'] !== null
                    && $override['year'] !== ''
                    ? (int) $override['year']
                    : null
                )
                : $original->year;


            $finalExteriorColor =
                $this->nullableString(
                    $override['exterior_color']
                    ?? $original->exteriorColor
                );


            $finalInteriorColor =
                $this->nullableString(
                    $override['interior_color']
                    ?? $original->interiorColor
                );


            $finalEngineNumber =
                $this->nullableString(
                    $override['engine_number']
                    ?? $original->engineNumber
                );


            $finalPedimento =
                $this->nullableString(
                    $override['pedimento']
                    ?? $original->pedimento
                );


            $finalPurchaseOrder =
                $this->nullableString(
                    $override['purchase_order']
                    ?? $original->purchaseOrder
                );


            /*
            |--------------------------------------------------------------------------
            | DETECTAR CAMBIOS MANUALES
            |--------------------------------------------------------------------------
            */

            $originalValues = [

                'vin' =>
                    $originalVin,

                'brand' =>
                    $original->brand,

                'model' =>
                    $original->model,

                'version' =>
                    $original->version,

                'year' =>
                    $original->year,

                'exterior_color' =>
                    $original->exteriorColor,

                'interior_color' =>
                    $original->interiorColor,

                'engine_number' =>
                    $original->engineNumber,

                'pedimento' =>
                    $original->pedimento,

                'purchase_order' =>
                    $original->purchaseOrder,
            ];


            $finalValues = [

                'vin' =>
                    $finalVin,

                'brand' =>
                    $finalBrand,

                'model' =>
                    $finalModel,

                'version' =>
                    $finalVersion,

                'year' =>
                    $finalYear,

                'exterior_color' =>
                    $finalExteriorColor,

                'interior_color' =>
                    $finalInteriorColor,

                'engine_number' =>
                    $finalEngineNumber,

                'pedimento' =>
                    $finalPedimento,

                'purchase_order' =>
                    $finalPurchaseOrder,
            ];


            /*
             * Sólo guardamos los campos realmente
             * modificados.
             */
            $manualOverrides = [];


            foreach (
                $finalValues
                as $field => $value
            ) {

                $originalValue =
                    $originalValues[$field]
                    ?? null;


                if (
                    $this->normalizeComparisonValue(
                        $originalValue
                    )
                    !==
                    $this->normalizeComparisonValue(
                        $value
                    )
                ) {

                    $manualOverrides[$field] = [
                        'original' =>
                            $originalValue,

                        'final' =>
                            $value,
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | METADATOS DE TRAZABILIDAD
            |--------------------------------------------------------------------------
            |
            | Esto terminará guardándose en:
            |
            | document_units.parsed_vehicle_data
            |
            | porque attachDocumentToUnit ya utiliza
            | UnitImportData->extraData.
            |
            */

            $extraData =
                $original->extraData;


            $extraData['manual_review'] = [

                'was_modified' =>
                    $manualOverrides !== [],

                'original_values' =>
                    $originalValues,

                'manual_overrides' =>
                    $manualOverrides,
            ];


            /*
            |--------------------------------------------------------------------------
            | ORIGEN DEL VIN
            |--------------------------------------------------------------------------
            |
            | Si el usuario corrigió el VIN,
            | el valor definitivo ya es MANUAL.
            |
            */

            $vinSource =
                $finalVin !== $originalVin
                ? VinSource::MANUAL
                : $original->vinSource;


            /*
            |--------------------------------------------------------------------------
            | NUEVO DTO
            |--------------------------------------------------------------------------
            */

            $result[] = new UnitImportData(

                vin:
                $finalVin,

                brand:
                $finalBrand,

                model:
                $finalModel,

                version:
                $finalVersion,

                year:
                $finalYear,

                exteriorColor:
                $finalExteriorColor,

                interiorColor:
                $finalInteriorColor,

                engineNumber:
                $finalEngineNumber,

                pedimento:
                $finalPedimento,

                purchaseOrder:
                $finalPurchaseOrder,

                /*
                 * Estos datos pertenecen al XML
                 * original y NO son editables.
                 */
                conceptIdentifier:
                $original->conceptIdentifier,

                conceptIndex:
                $original->conceptIndex,

                rawDescription:
                $original->rawDescription,

                vinSource:
                $vinSource,

                extraData:
                $extraData,

                requiresReview:
                $original->requiresReview,
            );
        }


        return $result;
    }

    /**
     * @param array<UnitImportData> $units
     */
    private function validateUnitsBeforeImport(
        array $units
    ): void {
        $vins = [];

        foreach ($units as $unit) {
            $vin = strtoupper(
                trim($unit->vin)
            );

            if ($vin === '') {
                throw new RuntimeException(
                    'Se detectó una unidad sin VIN.'
                );
            }

            if (in_array($vin, $vins, true)) {
                throw new RuntimeException(
                    "VIN duplicado dentro del mismo XML: {$vin}"
                );
            }

            $vins[] = $vin;
        }

        $existing = Unit::query()
            ->whereIn('vin', $vins)
            ->pluck('vin')
            ->all();

        if ($existing !== []) {
            throw new RuntimeException(
                'Ya existen unidades con VIN: '
                . implode(', ', $existing)
            );
        }
    }

    private function createUnit(
        UnitImportData $data
    ): Unit {
        $brand = Brand::query()
            ->where(
                'name',
                strtoupper(trim($data->brand ?? ''))
            )
            ->first();

        if (!$brand) {
            throw new RuntimeException(
                "Marca no registrada: {$data->brand}"
            );
        }

        return Unit::create([
            'vin' =>
                strtoupper(trim($data->vin)),

            'brand_id' =>
                $brand->id,

            'model' =>
                $data->model,

            'version' =>
                $data->version,

            'year' =>
                $data->year,

            'exterior_color' =>
                $data->exteriorColor,

            'interior_color' =>
                $data->interiorColor,

            'engine_number' =>
                $data->engineNumber,

            /*
             * Después de importar, el siguiente paso
             * operativo real es documentar llegada.
             */
            'status' =>
                UnitStatus::ARRIVAL_PENDING,
        ]);
    }

    private function createDocument(
        string $filePath,
        string $originalFilename,
        DocumentType $type,
        int $supplierId,
        string $pairKey,
        ?int $userId,
        DocumentProcessingStatus $processingStatus,
    ): Document {
        $hash = hash_file(
            'sha256',
            $filePath
        );

        if ($hash === false) {
            throw new RuntimeException(
                "No fue posible calcular hash de {$filePath}"
            );
        }

        if (
            Document::query()
                ->where('file_hash', $hash)
                ->exists()
        ) {
            throw new RuntimeException(
                'El archivo ya fue importado anteriormente: '
                . basename($filePath)
            );
        }

        $extension = strtolower(
            pathinfo(
                $originalFilename,
                PATHINFO_EXTENSION
            )
        );

        $targetDirectory =
            'cedis/documents/'
            . $supplierId
            . '/'
            . $pairKey;

        $targetFilename =
            $type->value
            . '.'
            . $extension;

        $targetPath =
            $targetDirectory
            . '/'
            . $targetFilename;

        $contents = file_get_contents(
            $filePath
        );

        if ($contents === false) {
            throw new RuntimeException(
                "No fue posible leer {$filePath}"
            );
        }

        $stored = Storage::disk('local')
            ->put(
                $targetPath,
                $contents
            );

        if (!$stored) {
            throw new RuntimeException(
                "No fue posible almacenar {$filePath}"
            );
        }

        return Document::create([
            'supplier_id' =>
                $supplierId,

            'document_type' =>
                $type,

            'original_filename' =>
                $originalFilename,

            'storage_disk' =>
                'local',

            'storage_path' =>
                $targetPath,

            'file_hash' =>
                $hash,

            'mime_type' =>
                mime_content_type($filePath)
                ?: null,

            'file_size' =>
                filesize($filePath)
                ?: null,

            'pair_key' =>
                $pairKey,

            'processing_status' =>
                $processingStatus,

            'processed_at' =>
                now(),

            'uploaded_by' =>
                $userId,
        ]);
    }

    private function attachDocumentToUnit(
        Document $document,
        Unit $unit,
        UnitImportData $data,
        bool $includeParsingMetadata = true,
    ): void {
        DocumentUnit::create([
            'document_id' =>
                $document->id,

            'unit_id' =>
                $unit->id,

            'concept_index' =>
                $includeParsingMetadata
                ? $data->conceptIndex
                : null,

            'concept_identifier' =>
                $includeParsingMetadata
                ? $data->conceptIdentifier
                : null,

            'raw_description' =>
                $includeParsingMetadata
                ? $data->rawDescription
                : null,

            'pedimento' =>
                $includeParsingMetadata
                ? $data->pedimento
                : null,

            'purchase_order' =>
                $includeParsingMetadata
                ? $data->purchaseOrder
                : null,

            'vin_source' =>
                $includeParsingMetadata
                ? $data->vinSource
                : null,

            'parsed_vehicle_data' =>
                $includeParsingMetadata
                ? $data->extraData
                : null,
        ]);
    }

    private function createMilestones(
        Unit $unit
    ): void {
        foreach (
            MilestoneStage::cases()
            as $stage
        ) {
            UnitMilestone::create([
                'unit_id' =>
                    $unit->id,

                'stage' =>
                    $stage,

                'status' =>
                    MilestoneStatus::PENDING,
            ]);
        }
    }

    private function createImportEvent(
        Unit $unit,
        Document $document,
        ?int $userId,
        UnitImportData $data,
    ): void {

        /*
         * Guardamos el nombre histórico del usuario
         * que realizó la importación.
         */
        $performedByName = $userId
            ? User::query()
                ->whereKey($userId)
                ->value('name')
            : null;


        UnitEvent::create([
            'unit_id' =>
                $unit->id,

            'event_type' =>
                UnitEventType::UNIT_IMPORTED,

            'title' =>
                'Unidad importada',

            'description' =>
                'La unidad fue registrada a partir de un CFDI.',

            'reference_type' =>
                Document::class,

            'reference_id' =>
                $document->id,

            'performed_by' =>
                $userId,

            'performed_by_name' =>
                $performedByName,

            'metadata' => [
                'vin_source' =>
                    $data->vinSource->value,

                'requires_review' =>
                    $data->requiresReview,
            ],
        ]);
    }

    /**
     * @param array<UnitImportData> $units
     */
    private function resolveProcessingStatus(
        array $units
    ): DocumentProcessingStatus {
        foreach ($units as $unit) {
            if ($unit->requiresReview) {
                return DocumentProcessingStatus::REVIEW_REQUIRED;
            }
        }

        return DocumentProcessingStatus::PROCESSED;
    }

    private function buildPairKey(
        ?string $series,
        ?string $folio,
        string $filePath,
    ): string {
        $series = trim(
            $series ?? ''
        );

        $folio = trim(
            $folio ?? ''
        );

        if ($series !== '' || $folio !== '') {
            return preg_replace(
                '/[^A-Za-z0-9_-]/',
                '',
                $series . $folio
            );
        }

        return pathinfo(
            $filePath,
            PATHINFO_FILENAME
        );
    }
}
