<?php

namespace App\Http\Controllers;

use App\Enums\MilestoneStage;
use App\Enums\UnitStatus;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;

class ShippingGuidePdfController extends Controller
{
    public function download(
        Unit $unit
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDAR ESTADO
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $unit->status === UnitStatus::COMPLETED,
            404,
            'La guía de envío sólo está disponible para unidades entregadas.'
        );


        /*
        |--------------------------------------------------------------------------
        | CARGAR INFORMACIÓN
        |--------------------------------------------------------------------------
        */

        $unit->load([
            'brand',

            'milestones' => fn($query) =>
                $query->where(
                    'stage',
                    MilestoneStage::CARRIER_DELIVERY->value
                ),

            'milestones.carrierDelivery.carrier',

            'milestones.evidences',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ETAPA DE ENTREGA
        |--------------------------------------------------------------------------
        */

        $milestone =
            $unit
                ->milestones
                ->first();


        abort_unless(
            $milestone,
            404,
            'No existe información de entrega para esta unidad.'
        );


        /*
        |--------------------------------------------------------------------------
        | DATOS DE TRANSPORTE
        |--------------------------------------------------------------------------
        */

        $delivery =
            $milestone
                ->carrierDelivery;


        abort_unless(
            $delivery,
            404,
            'No existe información de transportadora para esta unidad.'
        );


        /*
        |--------------------------------------------------------------------------
        | FOLIO DE GUÍA
        |--------------------------------------------------------------------------
        |
        | Ejemplo:
        |
        | delivery ID 12
        | GE-000012
        |
        */

        $guideNumber =
            'GE-'
            . str_pad(
                (string) $delivery->id,
                6,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | EVIDENCIAS
        |--------------------------------------------------------------------------
        */

        $evidenceCount =
            $milestone
                ->evidences
                ->count();


        /*
        |--------------------------------------------------------------------------
        | GENERACIÓN
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'pdf.shipping-guide',
                [
                    'unit' =>
                        $unit,

                    'milestone' =>
                        $milestone,

                    'delivery' =>
                        $delivery,

                    'guideNumber' =>
                        $guideNumber,

                    'evidenceCount' =>
                        $evidenceCount,
                ]
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
                ]);


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL ARCHIVO
        |--------------------------------------------------------------------------
        */

        $filename =
            'Guia_Envio_'
            . $guideNumber
            . '_'
            . $unit->vin
            . '.pdf';


        return $pdf->download(
            $filename
        );
    }
}