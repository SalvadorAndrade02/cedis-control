<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Models\Unit;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function show(Unit $unit): View
    {
        $unit->load([
            'brand',

            'documents.supplier',
            'documents.invoiceData',

            'milestones.completedBy',
            'milestones.evidences.uploader',

            'milestones.carrierDelivery.carrier',

            'events.performedBy',
        ]);

        $xmlDocument = $unit->documents
            ->first(
                fn($document) =>
                $document->document_type
                    === DocumentType::XML
            );

        $pdfDocument = $unit->documents
            ->first(
                fn($document) =>
                $document->document_type
                    === DocumentType::PDF
            );

        $invoice = $xmlDocument?->invoiceData;

        $milestones = $unit
            ->milestones
            ->keyBy(
                fn($milestone) =>
                $milestone->stage->value
            );

        return view(
            'units.show',
            compact(
                'unit',
                'xmlDocument',
                'pdfDocument',
                'invoice',
                'milestones',
            )
        );
    }
}
