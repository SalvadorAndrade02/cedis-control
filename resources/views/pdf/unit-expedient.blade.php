<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Expediente {{ $unit->vin }}
    </title>

    <style>
        @page {
            margin:
                45px 38px 65px 38px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family:
                "DejaVu Sans",
                sans-serif;

            font-size: 10px;

            color: #1e293b;

            line-height: 1.45;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .muted {
            color: #64748b;
        }

        .small {
            font-size: 8px;
        }

        .section-title {
            margin-bottom: 15px;

            padding-bottom: 8px;

            border-bottom:
                2px solid #0f172a;

            font-size: 16px;

            font-weight: bold;

            color: #0f172a;
        }

        .sub-title {
            margin-bottom: 10px;

            font-size: 12px;

            font-weight: bold;

            color: #0f172a;
        }

        .data-table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;
        }

        .data-table td {
            width: 50%;

            padding: 8px;

            vertical-align: top;

            border: 1px solid #e2e8f0;
        }

        .label {
            margin-bottom: 3px;

            font-size: 8px;

            text-transform: uppercase;

            color: #64748b;
        }

        .value {
            font-size: 10px;

            font-weight: bold;

            color: #0f172a;
        }

        .box {
            padding: 12px;

            border: 1px solid #e2e8f0;

            background: #f8fafc;
        }

        .status-complete {
            display: inline-block;

            padding: 4px 8px;

            border-radius: 8px;

            background: #dcfce7;

            color: #166534;

            font-size: 8px;

            font-weight: bold;
        }

        .photo-table {
            width: 100%;

            margin-top: 12px;

            border-collapse: separate;

            border-spacing: 5px;
        }

        .photo-cell {
            width: 50%;

            vertical-align: top;

            border: 1px solid #e2e8f0;

            padding: 5px;
        }

        .photo {
            display: block;

            width: 100%;

            height: 205px;

            object-fit: contain;

            background: #f8fafc;
        }

        .photo-caption {
            margin-top: 5px;

            font-size: 7px;

            color: #64748b;
        }

        .timeline-table {
            width: 100%;

            border-collapse: collapse;
        }

        .timeline-table td {
            padding: 10px 8px;

            vertical-align: top;

            border-bottom: 1px solid #e2e8f0;
        }

        .footer {
            position: fixed;

            bottom: -45px;

            left: 0;

            right: 100px;

            border-top: 1px solid #e2e8f0;

            padding-top: 6px;

            font-size: 7px;

            color: #64748b;
        }
    </style>

</head>


<body>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <div class="footer">

        Control de Unidades CEDIS
        &nbsp; | &nbsp;
        VIN:
        {{ $unit->vin }}

    </div>



    {{-- ========================================================= --}}
    {{-- PORTADA --}}
    {{-- ========================================================= --}}

    <div style="
        padding-top: 100px;
        text-align: center;
    ">

        <p style="
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #2563eb;
        ">
            CONTROL DE UNIDADES CEDIS
        </p>


        <h1 style="
            margin-top: 20px;
            font-size: 28px;
            color: #0f172a;
        ">
            EXPEDIENTE COMPLETO
        </h1>


        <div style="
            margin: 55px auto 0 auto;
            width: 85%;
            border: 1px solid #cbd5e1;
            padding: 25px;
        ">

            <p style="
                font-size: 20px;
                font-weight: bold;
            ">
                {{ $unit->brand?->name ?? '—' }}
            </p>

            <p style="
                margin-top: 5px;
                font-size: 16px;
            ">
                {{ $unit->model ?? '—' }}
            </p>


            <p style="
                margin-top: 25px;
                font-size: 9px;
                color: #64748b;
            ">
                VIN
            </p>

            <p style="
                margin-top: 5px;
                font-size: 18px;
                font-weight: bold;
                letter-spacing: 1px;
            ">
                {{ $unit->vin }}
            </p>


            <p style="
                margin-top: 25px;
            ">

                <span class="status-complete">
                    EXPEDIENTE COMPLETO
                </span>

            </p>

        </div>


        <div style="
            margin-top: 75px;
            color: #64748b;
        ">

            <p>
                Expediente:
                <strong>
                    {{ $expedientNumber }}
                </strong>
            </p>

            <p style="margin-top: 5px;">
                Generado:
                {{ \App\Support\DateHelper::format(
    $generatedAt
) }}
            </p>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- 1. IDENTIFICACIÓN --}}
    {{-- ========================================================= --}}

    <div class="page-break">

        <h2 class="section-title">
            1. Identificación de la unidad
        </h2>


        <table class="data-table">

            <tr>

                <td>
                    <div class="label">
                        VIN
                    </div>

                    <div class="value">
                        {{ $unit->vin }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Marca
                    </div>

                    <div class="value">
                        {{ $unit->brand?->name ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Modelo
                    </div>

                    <div class="value">
                        {{ $unit->model ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Versión
                    </div>

                    <div class="value">
                        {{ $unit->version ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Año
                    </div>

                    <div class="value">
                        {{ $unit->year ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Número de motor
                    </div>

                    <div class="value">
                        {{ $unit->engine_number ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Color exterior
                    </div>

                    <div class="value">
                        {{ $unit->exterior_color ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Color interior
                    </div>

                    <div class="value">
                        {{ $unit->interior_color ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Fecha de registro
                    </div>

                    <div class="value">
                        {{ \App\Support\DateHelper::format(
    $unit->created_at
) }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Estado final
                    </div>

                    <div class="value">
                        {{ $unit->status->label() }}
                    </div>
                </td>

            </tr>

        </table>



        {{-- ===================================================== --}}
        {{-- 2. DOCUMENTOS --}}
        {{-- ===================================================== --}}

        <h2 class="section-title" style="margin-top: 35px;">
            2. Documentos de origen
        </h2>


        <table class="data-table">

            <tr>

                <td>
                    <div class="label">
                        Proveedor
                    </div>

                    <div class="value">
                        {{ $supplier?->name ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        RFC proveedor
                    </div>

                    <div class="value">
                        {{ $supplier?->rfc ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Factura
                    </div>

                    <div class="value">
                        {{ $invoice?->series ?? '' }}
                        {{ $invoice?->folio ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Moneda
                    </div>

                    <div class="value">
                        {{ $invoice?->currency ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td colspan="2">

                    <div class="label">
                        UUID
                    </div>

                    <div class="value">
                        {{ $invoice?->uuid ?? '—' }}
                    </div>

                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Subtotal
                    </div>

                    <div class="value">

                        @if ($invoice?->subtotal !== null)

                                                {{ $invoice->currency }}
                                                {{ number_format(
                                (float) $invoice->subtotal,
                                2
                            ) }}

                        @else
                            —
                        @endif

                    </div>
                </td>

                <td>
                    <div class="label">
                        Impuestos
                    </div>

                    <div class="value">

                        @if ($invoice?->tax !== null)

                                                {{ $invoice->currency }}
                                                {{ number_format(
                                (float) $invoice->tax,
                                2
                            ) }}

                        @else
                            —
                        @endif

                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Total
                    </div>

                    <div class="value">

                        @if ($invoice?->total !== null)

                                                {{ $invoice->currency }}
                                                {{ number_format(
                                (float) $invoice->total,
                                2
                            ) }}

                        @else
                            —
                        @endif

                    </div>
                </td>

                <td>
                    <div class="label">
                        Archivos
                    </div>

                    <div class="value">

                        XML:
                        {{ $xmlDocument?->original_filename ?? '—' }}

                        <br>

                        PDF:
                        {{ $pdfDocument?->original_filename ?? '—' }}

                    </div>
                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- 3. IMPORTACIÓN --}}
    {{-- ========================================================= --}}

    <div class="page-break">

        <h2 class="section-title">
            3. Información de importación
        </h2>


        <table class="data-table">

            <tr>

                <td>
                    <div class="label">
                        Método de registro
                    </div>

                    <div class="value">
                        Importación XML / PDF
                    </div>
                </td>

                <td>
                    <div class="label">
                        Parser
                    </div>

                    <div class="value">
                        {{ $supplier?->parser_key ?? '—' }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Fuente del VIN
                    </div>

                    <div class="value">
                        {{ $vinSource ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Revisión manual
                    </div>

                    <div class="value">

                        {{ data_get(
    $manualReview,
    'was_modified',
    false
)
    ? 'Sí'
    : 'No'
                    }}

                    </div>
                </td>

            </tr>

        </table>


        @php

            $manualOverrides =
                data_get(
                    $manualReview,
                    'manual_overrides',
                    []
                );

            $fieldLabels = [
                'vin' => 'VIN',
                'brand' => 'Marca',
                'model' => 'Modelo',
                'version' => 'Versión',
                'year' => 'Año',
                'exterior_color' =>
                    'Color exterior',
                'interior_color' =>
                    'Color interior',
                'engine_number' =>
                    'Número de motor',
                'pedimento' =>
                    'Pedimento',
                'purchase_order' =>
                    'Orden de compra',
            ];

        @endphp


        @if (!empty($manualOverrides))

            <h3 class="sub-title" style="margin-top: 25px;">
                Correcciones realizadas
            </h3>


            <table class="data-table">

                @foreach (
                        $manualOverrides
                        as $field => $change
                    )

                    <tr>

                        <td>
                            <div class="label">
                                Campo
                            </div>

                            <div class="value">
                                {{ $fieldLabels[$field]
                    ?? $field
                                                                                            }}
                            </div>
                        </td>


                        <td>

                            <div class="small muted">
                                Detectado
                            </div>

                            <div>
                                {{ $change['original']
                    ?? '—'
                                                                                            }}
                            </div>

                            <div class="small muted" style="margin-top: 5px;">
                                Confirmado
                            </div>

                            <div class="value">
                                {{ $change['final']
                    ?? '—'
                                                                                            }}
                            </div>

                        </td>

                    </tr>

                @endforeach

            </table>

        @endif



        {{-- ===================================================== --}}
        {{-- 4. TRAZABILIDAD --}}
        {{-- ===================================================== --}}

        <h2 class="section-title" style="margin-top: 35px;">
            4. Trazabilidad del proceso
        </h2>


        <table class="timeline-table">

            @foreach (
                            $unit->events
                                ->sortBy('created_at')
                            as $event
                        )

                        <tr>

                            <td style="width: 25%;">

                                <strong>
                                    {{ \App\Support\DateHelper::format(
                    $event->created_at
                ) }}
                                </strong>

                            </td>


                            <td style="width: 75%;">

                                <strong>
                                    {{ $event->title }}
                                </strong>

                                <br>

                                <span class="muted">

                                    {{ $event->performed_by_name
                    ?? $event->performedBy?->name
                    ?? 'Sistema'
                                                                                                                        }}

                                </span>

                            </td>

                        </tr>

            @endforeach

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- 5, 6 Y 7. ETAPAS --}}
    {{-- ========================================================= --}}

    @php

        $stageConfig = [

            \App\Enums\MilestoneStage::ARRIVAL->value => [
                'number' => 5,
                'title' => 'Llegada al CEDIS',
            ],

            \App\Enums\MilestoneStage::ASSEMBLY_COMPLETED->value => [
                'number' => 6,
                'title' => 'Armado finalizado',
            ],

            \App\Enums\MilestoneStage::CARRIER_DELIVERY->value => [
                'number' => 7,
                'title' => 'Entrega a transportadora',
            ],
        ];

    @endphp


    @foreach (
            $unit->milestones
                ->sortBy('id')
            as $milestone
        )

        @php

            $config =
                $stageConfig[
                    $milestone->stage->value
                ] ?? null;

        @endphp


        @if ($config)

            <div class="page-break">

                <h2 class="section-title">

                    {{ $config['number'] }}.
                    {{ $config['title'] }}

                </h2>


                <table class="data-table">

                    <tr>

                        <td>
                            <div class="label">
                                Estado
                            </div>

                            <div class="value">
                                {{ $milestone->status->label() }}
                            </div>
                        </td>

                        <td>
                            <div class="label">
                                Fecha y hora
                            </div>

                            <div class="value">

                                {{ $milestone->completed_at
                    ? \App\Support\DateHelper::format(
                        $milestone->completed_at
                    )
                    : '—'
                                                                                            }}

                            </div>
                        </td>

                    </tr>


                    <tr>

                        <td>

                            <div class="label">
                                Realizado por
                            </div>

                            <div class="value">

                                {{ $milestone->completed_by_name
                    ?? $milestone->completedBy?->name
                    ?? '—'
                                                                                            }}

                            </div>

                        </td>

                        <td>

                            <div class="label">
                                Evidencias
                            </div>

                            <div class="value">
                                {{ $milestone
                    ->evidences
                    ->count()
                                                                                            }}
                            </div>

                        </td>

                    </tr>

                </table>

                {{-- ===================================================== --}}
                {{-- CONTROL DE TIEMPO DE ARMADO --}}
                {{-- ===================================================== --}}

                @if (
                        $milestone->stage
                        === \App\Enums\MilestoneStage::ASSEMBLY_COMPLETED
                        && $milestone->assemblyWorkSession
                    )

                    @php

                        $assemblySession =
                            $milestone->assemblyWorkSession;


                        /*
                         * En expediente COMPLETED normalmente
                         * siempre tendremos completed_at.
                         */
                        $assemblyElapsedSeconds = 0;


                        if (
                            $assemblySession->started_at
                            && $assemblySession->completed_at
                        ) {

                            $assemblyElapsedSeconds =
                                $assemblySession
                                    ->started_at
                                    ->diffInSeconds(
                                        $assemblySession
                                            ->completed_at
                                    );
                        }


                        $assemblyEffectiveSeconds =
                            (int) $assemblySession
                                ->total_active_seconds;


                        $assemblyPausedSeconds =
                            (int) $assemblySession
                                ->total_paused_seconds;

                    @endphp


                    <h3 class="sub-title" style="margin-top: 25px;">
                        Control de tiempo de armado
                    </h3>


                    <table class="data-table">

                        <tr>

                            <td>

                                <div class="label">
                                    Inicio del armado
                                </div>

                                <div class="value">

                                    {{ $assemblySession->started_at
                            ? \App\Support\DateHelper::format(
                                $assemblySession->started_at
                            )
                            : '—'
                                                                                                        }}

                                </div>

                            </td>


                            <td>

                                <div class="label">
                                    Finalización del armado
                                </div>

                                <div class="value">

                                    {{ $assemblySession->completed_at
                            ? \App\Support\DateHelper::format(
                                $assemblySession->completed_at
                            )
                            : '—'
                                                                                                        }}

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="label">
                                    Tiempo efectivo
                                </div>

                                <div class="value" style="
                                                                                                            font-size: 14px;
                                                                                                            color: #1d4ed8;
                                                                                                        ">
                                    {{ \App\Support\DurationHelper::format(
                            $assemblyEffectiveSeconds
                        ) }}
                                </div>

                            </td>


                            <td>

                                <div class="label">
                                    Tiempo pausado
                                </div>

                                <div class="value" style="
                                                                                                            font-size: 14px;
                                                                                                            color: #b45309;
                                                                                                        ">
                                    {{ \App\Support\DurationHelper::format(
                            $assemblyPausedSeconds
                        ) }}
                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="label">
                                    Tiempo transcurrido
                                </div>

                                <div class="value">
                                    {{ \App\Support\DurationHelper::format(
                            $assemblyElapsedSeconds
                        ) }}
                                </div>

                            </td>


                            <td>

                                <div class="label">
                                    Estado del control de tiempo
                                </div>

                                <div class="value">
                                    {{ $assemblySession
                            ->status
                            ->label()
                                                                                                        }}
                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="label">
                                    Iniciado por
                                </div>

                                <div class="value">
                                    {{ $assemblySession
                            ->started_by_name
                            ?? '—'
                                                                                                        }}
                                </div>

                            </td>


                            <td>

                                <div class="label">
                                    Finalizado por
                                </div>

                                <div class="value">
                                    {{ $assemblySession
                            ->completed_by_name
                            ?? '—'
                                                                                                        }}
                                </div>

                            </td>

                        </tr>

                    </table>

                    {{-- ================================================= --}}
                    {{-- HISTORIAL DE PAUSAS --}}
                    {{-- ================================================= --}}

                    <h3 class="sub-title" style="margin-top: 25px;">
                        Historial de pausas
                    </h3>


                    @forelse (
                            $assemblySession
                                ->pauses
                                ->sortBy('paused_at')
                            as $pause
                        )

                        @php

                            /*
                             * Una sesión ya terminada debería tener
                             * todas sus pausas cerradas.
                             *
                             * Dejamos fallback por seguridad.
                             */
                            $pauseDurationSeconds =
                                $pause->duration_seconds;


                            if (
                                !$pauseDurationSeconds
                                && $pause->paused_at
                                && $pause->resumed_at
                            ) {

                                $pauseDurationSeconds =
                                    $pause
                                        ->paused_at
                                        ->diffInSeconds(
                                            $pause->resumed_at
                                        );
                            }

                        @endphp


                        <div class="avoid-break" style="
                                                                                                                                margin-bottom: 12px;
                                                                                                                                border: 1px solid #e2e8f0;
                                                                                                                                padding: 12px;
                                                                                                                                background: #f8fafc;
                                                                                                                            ">

                            <table style="
                                                                                                                                    width: 100%;
                                                                                                                                    border-collapse: collapse;
                                                                                                                                ">

                                <tr>

                                    <td colspan="2"
                                        style="
                                                                                                                                            padding-bottom: 9px;
                                                                                                                                        ">

                                        <strong
                                            style="
                                                                                                                                                font-size: 11px;
                                                                                                                                                color: #0f172a;
                                                                                                                                            ">
                                            {{ $pause
                                    ->reason
                                    ->label()
                                                                                                                                            }}
                                        </strong>


                                        <span
                                            style="
                                                                                                                                                float: right;
                                                                                                                                                font-family: monospace;
                                                                                                                                                font-size: 10px;
                                                                                                                                                font-weight: bold;
                                                                                                                                                color: #92400e;
                                                                                                                                            ">
                                            {{ \App\Support\DurationHelper::format(
                                    (int) $pauseDurationSeconds
                                ) }}
                                        </span>

                                    </td>

                                </tr>


                                <tr>

                                    <td
                                        style="
                                                                                                                                            width: 50%;
                                                                                                                                            padding-right: 8px;
                                                                                                                                            vertical-align: top;
                                                                                                                                        ">

                                        <div class="label">
                                            Inicio de pausa
                                        </div>

                                        <div>
                                            {{ \App\Support\DateHelper::format(
                                    $pause->paused_at
                                ) }}
                                        </div>

                                    </td>


                                    <td
                                        style="
                                                                                                                                            width: 50%;
                                                                                                                                            vertical-align: top;
                                                                                                                                        ">

                                        <div class="label">
                                            Reanudación
                                        </div>

                                        <div>

                                            @if ($pause->resumed_at)

                                                        {{ \App\Support\DateHelper::format(
                                                    $pause->resumed_at
                                                ) }}

                                            @else

                                                —

                                            @endif

                                        </div>

                                    </td>

                                </tr>


                                <tr>

                                    <td
                                        style="
                                                                                                                                            padding-top: 10px;
                                                                                                                                            padding-right: 8px;
                                                                                                                                            vertical-align: top;
                                                                                                                                        ">

                                        <div class="label">
                                            Pausado por
                                        </div>

                                        <div>
                                            {{ $pause
                                    ->paused_by_name
                                    ?? '—'
                                                                                                                                            }}
                                        </div>

                                    </td>


                                    <td
                                        style="
                                                                                                                                            padding-top: 10px;
                                                                                                                                            vertical-align: top;
                                                                                                                                        ">

                                        <div class="label">
                                            Reanudado por
                                        </div>

                                        <div>
                                            {{ $pause
                                    ->resumed_by_name
                                    ?? '—'
                                                                                                                                            }}
                                        </div>

                                    </td>

                                </tr>


                                @if ($pause->notes)

                                    <tr>

                                        <td colspan="2"
                                            style="
                                                                                                                                                                            padding-top: 10px;
                                                                                                                                                                        ">

                                            <div class="label">
                                                Observaciones de la pausa
                                            </div>

                                            <div>
                                                {{ $pause->notes }}
                                            </div>

                                        </td>

                                    </tr>

                                @endif

                            </table>

                        </div>

                    @empty

                        <div class="box">
                            No se registraron pausas durante el armado.
                        </div>

                    @endforelse


                @endif


                @if (
                        $milestone->stage
                        === \App\Enums\MilestoneStage::CARRIER_DELIVERY
                        && $milestone->carrierDelivery
                    )

                    <h3 class="sub-title" style="margin-top: 25px;">
                        Datos de transporte
                    </h3>


                    <table class="data-table">

                        <tr>

                            <td>
                                <div class="label">
                                    Transportadora
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->carrier
                                ?->name
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                            <td>
                                <div class="label">
                                    Operador
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->operator_name
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <div class="label">
                                    Placas
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->vehicle_plate
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                            <td>
                                <div class="label">
                                    Número económico
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->vehicle_number
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <div class="label">
                                    Teléfono
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->operator_phone
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                            <td>
                                <div class="label">
                                    Tipo transporte
                                </div>

                                <div class="value">
                                    {{ $milestone
                            ->carrierDelivery
                            ->transport_type
                            ?? '—'
                                                                                                                                }}
                                </div>
                            </td>

                        </tr>

                    </table>

                @endif


                @if ($milestone->observations)

                    <h3 class="sub-title" style="margin-top: 25px;">
                        Observaciones
                    </h3>

                    <div class="box">
                        {{ $milestone->observations }}
                    </div>

                @endif


                <h3 class="sub-title" style="margin-top: 25px;">
                    Evidencias fotográficas
                </h3>


                @forelse (
                        $milestone
                            ->evidences
                            ->chunk(2)
                        as $evidenceRow
                    )

                    <table class="photo-table">

                        <tr>

                            @foreach (
                                    $evidenceRow
                                    as $evidence
                                )

                                <td class="photo-cell">

                                    @if (
                                            $evidenceImages[
                                                $evidence->id
                                            ] ?? null
                                        )

                                        <img src="{{
                                            $evidenceImages[
                                                $evidence->id
                                            ]
                                                                                                                                                                                                        }}"
                                            class="photo">

                                    @else

                                        <div class="box"
                                            style="
                                                                                                                                                                                                            height: 205px;
                                                                                                                                                                                                            text-align: center;
                                                                                                                                                                                                            padding-top: 85px;
                                                                                                                                                                                                        ">
                                            Evidencia no disponible
                                        </div>

                                    @endif


                                    <div class="photo-caption">

                                        Evidencia
                                        #{{ $evidence->id }}

                                        @if ($evidence->captured_at)

                                                <br>

                                                {{ \App\Support\DateHelper::format(
                                                $evidence->captured_at
                                            ) }}

                                        @endif

                                    </div>

                                </td>

                            @endforeach


                            @if (
                                    $evidenceRow->count() === 1
                                )

                                <td style="width: 50%;">
                                </td>

                            @endif

                        </tr>

                    </table>

                @empty

                    <div class="box">
                        No existen evidencias fotográficas.
                    </div>

                @endforelse

            </div>

        @endif

    @endforeach

    {{-- ========================================================= --}}
    {{-- 8. TRASLADO Y COMBUSTIBLE --}}
    {{-- ========================================================= --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | TRASLADO FINAL
        |--------------------------------------------------------------------------
        |
        | Al generar el expediente la unidad ya está COMPLETED,
        | por lo que activeTransferAssignment ya no existe.
        |
        | Buscamos la asignación que realmente terminó el traslado.
        |
        */

        $transferAssignment =
            $unit
                ->transferAssignments()
                ->where(
                    'status',
                    \App\Enums\TransferAssignmentStatus::COMPLETED->value
                )
                ->latest('completed_at')
                ->latest('id')
                ->first();


        /*
         * Compatibilidad con expedientes cerrados antes
         * de incorporar el cierre automático del traslado.
         */
        if (!$transferAssignment) {

            $transferAssignment =
                $unit
                    ->transferAssignments()
                    ->where(
                        'status',
                        '!=',
                        \App\Enums\TransferAssignmentStatus::CANCELLED->value
                    )
                    ->latest('id')
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | HISTORIAL DE ASIGNACIONES
        |--------------------------------------------------------------------------
        */

        $transferHistory =
            $unit
                ->transferAssignments()
                ->orderBy('assigned_at')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | COMBUSTIBLE
        |--------------------------------------------------------------------------
        |
        | Preferimos la carga perteneciente al traslado final.
        |
        */

        $fuelLoad =
            $transferAssignment
                    ?->fuelLoads()
                ->latest('fueled_at')
                ->first();


        /*
         * Fallback para registros históricos.
         */
        if (!$fuelLoad) {

            $fuelLoad =
                $unit
                    ->fuelLoads()
                    ->latest('fueled_at')
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGEN DEL TICKET
        |--------------------------------------------------------------------------
        |
        | DomPDF puede trabajar con Data URI.
        |
        */

        $fuelTicketImage = null;


        if (
            $fuelLoad
            && $fuelLoad->ticket_storage_disk
            && $fuelLoad->ticket_storage_path
        ) {

            try {

                $ticketDisk =
                    \Illuminate\Support\Facades\Storage::disk(
                        $fuelLoad->ticket_storage_disk
                    );


                if (
                    $ticketDisk->exists(
                        $fuelLoad->ticket_storage_path
                    )
                ) {

                    $ticketMime =
                        $fuelLoad->ticket_mime_type
                        ?: 'image/jpeg';


                    /*
                     * HEIC / HEIF normalmente no pueden ser renderizados
                     * directamente por DomPDF.
                     */
                    $renderableTicketMimeTypes = [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                        'image/webp',
                    ];


                    if (
                        in_array(
                            $ticketMime,
                            $renderableTicketMimeTypes,
                            true
                        )
                    ) {

                        $ticketContents =
                            $ticketDisk->get(
                                $fuelLoad->ticket_storage_path
                            );


                        $fuelTicketImage =
                            'data:'
                            . $ticketMime
                            . ';base64,'
                            . base64_encode(
                                $ticketContents
                            );
                    }
                }

            } catch (\Throwable $exception) {

                /*
                 * El PDF debe seguir generándose aunque
                 * la evidencia física no esté disponible.
                 */
                $fuelTicketImage = null;
            }
        }

    @endphp


    <div class="page-break">

        <h2 class="section-title">
            8. Traslado y combustible
        </h2>


        {{-- ===================================================== --}}
        {{-- TRASLADO --}}
        {{-- ===================================================== --}}

        <h3 class="sub-title">
            Responsable de traslado
        </h3>


        @if ($transferAssignment)

            <table class="data-table">

                <tr>

                    <td>

                        <div class="label">
                            Trasladista
                        </div>

                        <div class="value">
                            {{ $transferAssignment->transporter_name }}
                        </div>

                    </td>


                    <td>

                        <div class="label">
                            Estado del traslado
                        </div>

                        <div class="value">
                            {{ $transferAssignment->status->label() }}
                        </div>

                    </td>

                </tr>


                <tr>

                    <td>

                        <div class="label">
                            Origen
                        </div>

                        <div class="value">
                            {{ $transferAssignment->origin_name ?: 'CEDIS' }}
                        </div>

                    </td>


                    <td>

                        <div class="label">
                            Destino
                        </div>

                        <div class="value">
                            {{ $transferAssignment->destination_name ?: '—' }}
                        </div>

                    </td>

                </tr>


                <tr>

                    <td>

                        <div class="label">
                            Fecha de asignación
                        </div>

                        <div class="value">

                            {{ $transferAssignment->assigned_at
            ? \App\Support\DateHelper::format(
                $transferAssignment->assigned_at
            )
            : '—'
                                    }}

                        </div>

                    </td>


                    <td>

                        <div class="label">
                            Finalización del traslado
                        </div>

                        <div class="value">

                            {{ $transferAssignment->completed_at
            ? \App\Support\DateHelper::format(
                $transferAssignment->completed_at
            )
            : '—'
                                    }}

                        </div>

                    </td>

                </tr>


                <tr>

                    <td>

                        <div class="label">
                            Asignado por
                        </div>

                        <div class="value">
                            {{ $transferAssignment->assigned_by_name ?: '—' }}
                        </div>

                    </td>


                    <td>

                        <div class="label">
                            Responsable registrado
                        </div>

                        <div class="value">
                            {{ $transferAssignment->transporter_name }}
                        </div>

                    </td>

                </tr>

            </table>


            @if ($transferAssignment->notes)

                <div class="box" style="
                                            margin-top: 15px;
                                            line-height: 1.6;
                                        ">

                    <div class="label">
                        Observaciones del traslado
                    </div>

                    {{ $transferAssignment->notes }}

                </div>

            @endif


        @else

            <div class="box">
                No existe información de asignación de traslado para esta unidad.
            </div>

        @endif



        {{-- ===================================================== --}}
        {{-- HISTORIAL DE REASIGNACIONES --}}
        {{-- ===================================================== --}}

        @if ($transferHistory->count() > 1)

            <h3 class="sub-title" style="margin-top: 25px;">
                Historial de responsables
            </h3>


            <table class="timeline-table">

                @foreach ($transferHistory as $transfer)

                    <tr>

                        <td style="width: 25%;">

                            {{ $transfer->assigned_at
                    ? \App\Support\DateHelper::format(
                        $transfer->assigned_at
                    )
                    : '—'
                                                }}

                        </td>


                        <td style="width: 45%;">

                            <strong>
                                {{ $transfer->transporter_name }}
                            </strong>

                            <br>

                            <span class="muted">

                                {{ $transfer->origin_name ?: 'CEDIS' }}

                                →

                                {{ $transfer->destination_name ?: '—' }}

                            </span>

                        </td>


                        <td style="width: 30%;">

                            <strong>
                                {{ $transfer->status->label() }}
                            </strong>


                            @if ($transfer->cancelled_at)

                                        <br>

                                        <span class="muted small">
                                            Cancelado:
                                            {{ \App\Support\DateHelper::format(
                                    $transfer->cancelled_at
                                ) }}
                                        </span>

                            @endif


                            @if ($transfer->completed_at)

                                        <br>

                                        <span class="muted small">
                                            Finalizado:
                                            {{ \App\Support\DateHelper::format(
                                    $transfer->completed_at
                                ) }}
                                        </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </table>

        @endif



        {{-- ===================================================== --}}
        {{-- COMBUSTIBLE --}}
        {{-- ===================================================== --}}

        <h3 class="sub-title" style="margin-top: 30px;">
            Registro de combustible
        </h3>


        @if ($fuelLoad)

                @php

                    $fuelWasLoaded =
                        (float) $fuelLoad->amount > 0;

                @endphp


                <table class="data-table">

                    <tr>

                        <td>

                            <div class="label">
                                Resultado
                            </div>

                            <div class="value">

                                {{ $fuelWasLoaded
                ? 'Carga de gasolina realizada'
                : 'Sin carga de gasolina'
                                                }}

                            </div>

                        </td>


                        <td>

                            <div class="label">
                                Importe
                            </div>

                            <div class="value" style="
                                                    font-size: 14px;
                                                    color:
                                                        {{ $fuelWasLoaded
                ? '#047857'
                : '#b45309'
                                                        }};
                                                ">
                                ${{ number_format(
                (float) $fuelLoad->amount,
                2
            ) }}
                            </div>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <div class="label">
                                Registrado por
                            </div>

                            <div class="value">
                                {{ $fuelLoad->registered_by_name ?: '—' }}
                            </div>

                        </td>


                        <td>

                            <div class="label">
                                Fecha y hora
                            </div>

                            <div class="value">

                                {{ $fuelLoad->fueled_at
                ? \App\Support\DateHelper::format(
                    $fuelLoad->fueled_at
                )
                : '—'
                                                }}

                            </div>

                        </td>

                    </tr>


                    @if (
                            !$fuelWasLoaded
                            && $fuelLoad->no_fuel_reason
                        )

                        <tr>

                            <td colspan="2">

                                <div class="label">
                                    Motivo de no carga
                                </div>

                                <div class="value">
                                    {{ $fuelLoad
                        ->no_fuel_reason
                        ->label()
                                                                }}
                                </div>


                                @if ($fuelLoad->reason_notes)

                                    <div style="
                                                                                    margin-top: 6px;
                                                                                    color: #64748b;
                                                                                ">
                                        {{ $fuelLoad->reason_notes }}
                                    </div>

                                @endif

                            </td>

                        </tr>

                    @endif


                    @if ($fuelLoad->observations)

                        <tr>

                            <td colspan="2">

                                <div class="label">
                                    Observaciones
                                </div>

                                <div>
                                    {{ $fuelLoad->observations }}
                                </div>

                            </td>

                        </tr>

                    @endif

                </table>



                {{-- ================================================= --}}
                {{-- TICKET --}}
                {{-- ================================================= --}}

                @if ($fuelLoad->ticket_storage_path)

                    <h3 class="sub-title" style="margin-top: 25px;">
                        Ticket de gasolina
                    </h3>


                    @if ($fuelTicketImage)

                        <div class="avoid-break" style="
                                                                        width: 100%;
                                                                        border: 1px solid #e2e8f0;
                                                                        padding: 10px;
                                                                        background: #f8fafc;
                                                                    ">

                            <img src="{{ $fuelTicketImage }}" style="
                                                                            display: block;
                                                                            width: 100%;
                                                                            max-height: 430px;
                                                                            object-fit: contain;
                                                                        ">


                            <div class="photo-caption">

                                {{ $fuelLoad->ticket_original_filename
                                ?: 'Ticket de gasolina'
                                                                        }}

                                @if ($fuelLoad->fueled_at)

                                    <br>

                                    {{ \App\Support\DateHelper::format(
                                        $fuelLoad->fueled_at
                                    ) }}

                                @endif

                            </div>

                        </div>


                    @else

                        <div class="box">

                            Ticket registrado:

                            <strong>
                                {{ $fuelLoad->ticket_original_filename
                                ?: 'Archivo de evidencia'
                                                                        }}
                            </strong>

                            <br>

                            <span class="muted small">

                                El archivo se encuentra registrado
                                en el expediente, pero su formato
                                no pudo incrustarse en el PDF.

                            </span>

                        </div>

                    @endif

                @endif


        @else

            <div class="box">
                No existe información de combustible registrada para esta unidad.
            </div>

        @endif

    </div>

    {{-- ========================================================= --}}
    {{-- 9. HISTORIAL --}}
    {{-- ========================================================= --}}

    <div class="page-break">

        <h2 class="section-title">
            9. Historial del expediente
        </h2>


        <table class="timeline-table">

            @foreach (
                            $unit->events
                                ->sortBy('created_at')
                            as $event
                        )

                        <tr>

                            <td style="width: 25%;">

                                {{ \App\Support\DateHelper::format(
                    $event->created_at
                ) }}

                            </td>


                            <td>

                                <strong>
                                    {{ $event->title }}
                                </strong>

                                <br>

                                {{ $event->performed_by_name
                    ?? $event->performedBy?->name
                    ?? 'Sistema'
                                                                                                                    }}

                                @if ($event->description)

                                    <div class="muted" style="margin-top: 5px;">
                                        {{ $event->description }}
                                    </div>

                                @endif

                            </td>

                        </tr>

            @endforeach

        </table>



        {{-- ===================================================== --}}
        {{-- 10. CIERRE --}}
        {{-- ===================================================== --}}

        <h2 class="section-title" style="margin-top: 40px;">
            10. Cierre del expediente
        </h2>


        @php

            $totalEvidence =
                $unit->milestones
                    ->sum(
                        fn($milestone) =>
                            $milestone
                                ->evidences
                                ->count()
                    );


            $deliveryMilestone =
                $unit->milestones
                    ->first(
                        fn($milestone) =>
                            $milestone->stage
                            ===
                            \App\Enums\MilestoneStage::CARRIER_DELIVERY
                    );

            $assemblyMilestoneForSummary =
                $unit->milestones
                    ->first(
                        fn($milestone) =>
                            $milestone->stage
                            ===
                            \App\Enums\MilestoneStage::ASSEMBLY_COMPLETED
                    );


            $assemblySessionForSummary =
                $assemblyMilestoneForSummary
                        ?->assemblyWorkSession;

        @endphp

        <table class="data-table">

            <tr>

                <td>
                    <div class="label">
                        Estado
                    </div>

                    <div class="value">
                        EXPEDIENTE COMPLETO
                    </div>
                </td>

                <td>
                    <div class="label">
                        VIN
                    </div>

                    <div class="value">
                        {{ $unit->vin }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Total de evidencias
                    </div>

                    <div class="value">
                        {{ $totalEvidence }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Fecha de cierre
                    </div>

                    <div class="value">

                        {{ $deliveryMilestone?->completed_at
    ? \App\Support\DateHelper::format(
        $deliveryMilestone->completed_at
    )
    : '—'
                }}

                    </div>
                </td>

            </tr>


            {{-- RESUMEN DE TIEMPO DE ARMADO --}}

            @if ($assemblySessionForSummary)

                        <table class="data-table">

                            <tr>

                                <td>

                                    <div class="label">
                                        Tiempo efectivo de armado
                                    </div>

                                    <div class="value">

                                        {{ \App\Support\DurationHelper::format(
                    (int) $assemblySessionForSummary
                        ->total_active_seconds
                ) }}

                                    </div>

                                </td>


                                <td>

                                    <div class="label">
                                        Pausas registradas
                                    </div>

                                    <div class="value">

                                        {{ $assemblySessionForSummary
                    ->pauses
                    ->count()
                                }}
                                        pausa(s)

                                    </div>

                                </td>

                            </tr>

                        </table>

            @endif

        </table>


        <div class="box" style="
            margin-top: 30px;
            line-height: 1.7;
        ">

            Este documento corresponde al expediente
            digital registrado en el sistema Control
            de Unidades CEDIS.

            <br><br>

            La información presentada fue obtenida
            de los documentos de origen y de los
            registros operativos capturados durante
            el procesamiento de la unidad.

            <br><br>

            Las fotografías mostradas corresponden
            a las evidencias asociadas al expediente
            al momento de generar este documento.

        </div>



        {{-- ===================================================== --}}
        {{-- 11. GENERACIÓN --}}
        {{-- ===================================================== --}}

        <h2 class="section-title" style="margin-top: 40px;">
            11. Información de generación
        </h2>


        <table class="data-table">

            <tr>

                <td>
                    <div class="label">
                        Expediente
                    </div>

                    <div class="value">
                        {{ $expedientNumber }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        VIN
                    </div>

                    <div class="value">
                        {{ $unit->vin }}
                    </div>
                </td>

            </tr>


            <tr>

                <td>
                    <div class="label">
                        Generado
                    </div>

                    <div class="value">
                        {{ \App\Support\DateHelper::format(
    $generatedAt
) }}
                    </div>
                </td>

                <td>
                    <div class="label">
                        Generado por
                    </div>

                    <div class="value">
                        {{ $generatedBy }}
                    </div>
                </td>

            </tr>

        </table>

    </div>


</body>

</html>