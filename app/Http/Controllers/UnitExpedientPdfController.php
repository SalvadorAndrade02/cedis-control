<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Enums\UnitStatus;
use App\Models\DocumentUnit;
use App\Models\Unit;
use App\Services\Pdf\PdfEvidenceImageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class UnitExpedientPdfController extends Controller
{
    public function download(
        Unit $unit,
        PdfEvidenceImageService $imageService
    ): Response {

        /*
         * Sólo expedientes terminados.
         */
        abort_unless(
            $unit->status
            === UnitStatus::COMPLETED,
            422,
            'El expediente aún no está completo.'
        );


        /*
        |--------------------------------------------------------------------------
        | CARGAR EXPEDIENTE
        |--------------------------------------------------------------------------
        */

        $unit->load([
            'brand',

            'documents.supplier',
            'documents.invoiceData',

            'milestones.completedBy',
            'milestones.evidences',

            'milestones.carrierDelivery.carrier',

            'events.performedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DOCUMENTOS
        |--------------------------------------------------------------------------
        */

        $xmlDocument =
            $unit->documents
                ->first(
                    fn($document) =>
                    $document->document_type
                    === DocumentType::XML
                );


        $pdfDocument =
            $unit->documents
                ->first(
                    fn($document) =>
                    $document->document_type
                    === DocumentType::PDF
                );


        $invoice =
            $xmlDocument
                    ?->invoiceData;


        $supplier =
            $xmlDocument
                    ?->supplier;


        /*
        |--------------------------------------------------------------------------
        | DATOS DE IMPORTACIÓN
        |--------------------------------------------------------------------------
        */

        $documentUnit =
            DocumentUnit::query()
                ->where(
                    'unit_id',
                    $unit->id
                )
                ->whereNotNull(
                    'parsed_vehicle_data'
                )
                ->orderBy('id')
                ->first();


        $parsedVehicleData =
            $documentUnit
                    ?->parsed_vehicle_data
            ?? [];


        $manualReview =
            data_get(
                $parsedVehicleData,
                'manual_review',
                []
            );


        $vinSource =
            $documentUnit
                    ?->vin_source;


        if (
            $vinSource
            instanceof \BackedEnum
        ) {
            $vinSource =
                $vinSource->value;
        }


        /*
        |--------------------------------------------------------------------------
        | IMÁGENES
        |--------------------------------------------------------------------------
        |
        | No hacemos I/O dentro del Blade.
        |
        */

        $evidenceImages = [];


        foreach (
            $unit->milestones
            as $milestone
        ) {

            foreach (
                $milestone->evidences
                as $evidence
            ) {

                $evidenceImages[
                    $evidence->id
                ] =
                    $imageService
                        ->toDataUri(
                            $evidence
                        );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | GENERACIÓN
        |--------------------------------------------------------------------------
        */

        $generatedAt = now();

        $generatedBy =
            auth()->user()?->name
            ?? 'Sistema';


        $expedientNumber =
            'CEDIS-'
            . str_pad(
                (string) $unit->id,
                6,
                '0',
                STR_PAD_LEFT
            );


        $pdf = Pdf::loadView(
            'pdf.unit-expedient',
            compact(
                'unit',
                'xmlDocument',
                'pdfDocument',
                'invoice',
                'supplier',
                'documentUnit',
                'parsedVehicleData',
                'manualReview',
                'vinSource',
                'evidenceImages',
                'generatedAt',
                'generatedBy',
                'expedientNumber',
            )
        )
            ->setPaper(
                'a4',
                'portrait'
            )
            ->setOptions([
                'defaultFont' =>
                    'DejaVu Sans',

                'isRemoteEnabled' =>
                    false,

                'isHtml5ParserEnabled' =>
                    true,
            ]);


        /*
        |--------------------------------------------------------------------------
        | NÚMEROS DE PÁGINA
        |--------------------------------------------------------------------------
        */

        $dompdf =
            $pdf->getDomPDF();

        $dompdf->render();


        $canvas =
            $dompdf->getCanvas();


        $font =
            $dompdf
                ->getFontMetrics()
                ->getFont(
                    'DejaVu Sans',
                    'normal'
                );


        $canvas->page_text(
            465,
            818,
            'Página {PAGE_NUM} de {PAGE_COUNT}',
            $font,
            8,
            [
                0.40,
                0.40,
                0.40,
            ]
        );


        $filename =
            'Expediente_CEDIS_'
            . $unit->vin
            . '.pdf';


        return response(
            $dompdf->output(),
            200,
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'attachment; filename="'
                    . $filename
                    . '"',
            ]
        );
    }
}