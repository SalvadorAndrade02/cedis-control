<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Guía de Envío {{ $guideNumber }}
    </title>


    <style>
        @page {
            margin: 24px 30px 30px 30px;
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            font-family:
                "DejaVu Sans",
                sans-serif;

            font-size: 9px;

            line-height: 1.35;

            color: #0f172a;

            background: #ffffff;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {
            position: relative;

            height: 62px;

            background: #0b1220;

            color: #ffffff;

            overflow: hidden;
        }


        .brand {
            position: absolute;

            top: 20px;
            left: 16px;

            font-size: 13px;

            font-weight: bold;

            letter-spacing: 0.7px;
        }


        .heading {
            position: absolute;

            top: 13px;
            left: 170px;

            right: 110px;
        }


        .title {
            font-size: 14px;

            font-weight: bold;
        }


        .subtitle {
            margin-top: 4px;

            font-size: 7px;

            color: #94a3b8;
        }


        .folio {
            position: absolute;

            top: 13px;
            right: 14px;

            width: 90px;

            text-align: right;
        }


        .folio-label {
            font-size: 6px;

            text-transform: uppercase;

            letter-spacing: 0.6px;

            color: #94a3b8;
        }


        .folio-value {
            margin-top: 4px;

            font-size: 11px;

            font-weight: bold;

            color: #ffffff;
        }


        /*
        |--------------------------------------------------------------------------
        | SECCIONES
        |--------------------------------------------------------------------------
        */

        .section {
            margin-top: 14px;
        }


        .section-title {
            margin-bottom: 6px;

            padding-bottom: 4px;

            border-bottom: 2px solid #7c3aed;

            font-size: 8px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.6px;

            color: #6d28d9;
        }


        /*
        |--------------------------------------------------------------------------
        | TABLAS
        |--------------------------------------------------------------------------
        */

        .info-table {
            width: 100%;

            border-collapse: collapse;
        }


        .info-table td {
            padding: 5px 7px;

            border: 1px solid #e2e8f0;

            vertical-align: top;
        }


        .info-table .label {
            width: 20%;

            background: #f8fafc;

            font-size: 7px;

            font-weight: bold;

            text-transform: uppercase;

            color: #64748b;
        }


        .info-table .value {
            width: 30%;

            font-size: 8px;

            font-weight: 600;

            color: #1e293b;
        }


        .vin {
            font-family:
                "DejaVu Sans Mono",
                monospace;

            font-size: 7.5px;
        }


        /*
        |--------------------------------------------------------------------------
        | RESUMEN ENTREGA
        |--------------------------------------------------------------------------
        */

        .delivery-summary {
            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;
        }


        .delivery-summary td {
            width: 33.333%;

            padding: 7px;

            border: 1px solid #ddd6fe;

            background: #f5f3ff;

            vertical-align: top;
        }


        .summary-label {
            font-size: 6px;

            text-transform: uppercase;

            font-weight: bold;

            color: #7c3aed;
        }


        .summary-value {
            margin-top: 3px;

            font-size: 8px;

            font-weight: bold;

            color: #1e293b;
        }


        /*
        |--------------------------------------------------------------------------
        | OBSERVACIONES
        |--------------------------------------------------------------------------
        */

        .observations {
            min-height: 55px;

            padding: 8px;

            border: 1px solid #e2e8f0;

            background: #f8fafc;

            color: #334155;
        }


        /*
        |--------------------------------------------------------------------------
        | EVIDENCIAS
        |--------------------------------------------------------------------------
        */

        .evidence-box {
            padding: 8px 10px;

            border: 1px solid #bfdbfe;

            background: #eff6ff;
        }


        .evidence-title {
            font-weight: bold;

            color: #1d4ed8;
        }


        .evidence-text {
            margin-top: 3px;

            font-size: 7px;

            color: #475569;
        }


        /*
        |--------------------------------------------------------------------------
        | FIRMAS
        |--------------------------------------------------------------------------
        */

        .signatures {
            width: 100%;

            margin-top: 35px;

            border-collapse: collapse;
        }


        .signatures td {
            width: 50%;

            padding: 0 25px;

            text-align: center;

            vertical-align: bottom;
        }


        .signature-line {
            border-top: 1px solid #475569;

            padding-top: 6px;

            font-size: 7px;

            font-weight: bold;

            color: #334155;
        }


        .signature-name {
            margin-top: 3px;

            font-size: 7px;

            color: #64748b;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            position: fixed;

            bottom: -18px;

            left: 0;
            right: 0;

            border-top: 1px solid #e2e8f0;

            padding-top: 4px;

            font-size: 6px;

            color: #94a3b8;
        }


        .footer-left {
            float: left;
        }


        .footer-right {
            float: right;
        }
    </style>

</head>


<body>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="header">

        <div class="brand">
            GRUPO RISE
        </div>


        <div class="heading">

            <div class="title">
                Guía de Envío
            </div>

            <div class="subtitle">
                Control de Unidades CEDIS · Entrega a Transportadora
            </div>

        </div>


        <div class="folio">

            <div class="folio-label">
                Folio
            </div>

            <div class="folio-value">
                {{ $guideNumber }}
            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- RESUMEN --}}
    {{-- ========================================================= --}}

    <table class="delivery-summary">

        <tr>

            <td>

                <div class="summary-label">
                    Fecha de salida
                </div>

                <div class="summary-value">

                    {{ \App\Support\DateHelper::format(
    $delivery->delivered_at
) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Transportadora
                </div>

                <div class="summary-value">

                    {{ $delivery
    ->carrier
        ?->name
    ?? '—'
                    }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Responsable CEDIS
                </div>

                <div class="summary-value">

                    {{ $milestone
    ->completed_by_name
    ?? $milestone
        ->completedBy
            ?->name
    ?? '—'
                    }}

                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- UNIDAD --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Datos de la unidad
        </div>


        <table class="info-table">

            <tr>

                <td class="label">
                    Marca
                </td>

                <td class="value">
                    {{ $unit->brand?->name ?? '—' }}
                </td>

                <td class="label">
                    Modelo
                </td>

                <td class="value">
                    {{ $unit->model ?? '—' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Año
                </td>

                <td class="value">
                    {{ $unit->year ?? '—' }}
                </td>

                <td class="label">
                    Color exterior
                </td>

                <td class="value">
                    {{ $unit->exterior_color ?? '—' }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    VIN
                </td>

                <td class="value vin" colspan="3">
                    {{ $unit->vin }}
                </td>

            </tr>


            @if ($unit->engine_number)

                <tr>

                    <td class="label">
                        Número de motor
                    </td>

                    <td class="value" colspan="3">
                        {{ $unit->engine_number }}
                    </td>

                </tr>

            @endif

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- TRANSPORTADORA / OPERADOR --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Datos de transportadora y operador
        </div>


        <table class="info-table">

            <tr>

                <td class="label">
                    Transportadora
                </td>

                <td class="value">

                    {{ $delivery
    ->carrier
        ?->name
    ?? '—'
                    }}

                </td>

                <td class="label">
                    Operador
                </td>

                <td class="value">
                    {{ $delivery->operator_name }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Identificación
                </td>

                <td class="value">

                    {{ $delivery
    ->operator_identification
    ?: '—'
                    }}

                </td>

                <td class="label">
                    Teléfono
                </td>

                <td class="value">

                    {{ $delivery
    ->operator_phone
    ?: '—'
                    }}

                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- TRANSPORTE --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Datos del transporte
        </div>


        <table class="info-table">

            <tr>

                <td class="label">
                    Placas
                </td>

                <td class="value">
                    {{ $delivery->vehicle_plate }}
                </td>

                <td class="label">
                    Número económico
                </td>

                <td class="value">

                    {{ $delivery
    ->vehicle_number
    ?: '—'
                    }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Tipo de transporte
                </td>

                <td class="value" colspan="3">

                    {{ $delivery
    ->transport_type
    ?: '—'
                    }}

                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- OBSERVACIONES --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Observaciones de entrega
        </div>


        <div class="observations">

            {{ $delivery->observations
    ?: 'Sin observaciones registradas.'
            }}

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- EVIDENCIAS --}}
    {{-- ========================================================= --}}

    <div class="section">

        <div class="section-title">
            Evidencia documental
        </div>


        <div class="evidence-box">

            <div class="evidence-title">

                {{ $evidenceCount }}
                evidencia(s) fotográfica(s) registrada(s)

            </div>


            <div class="evidence-text">

                Las fotografías asociadas a esta entrega
                permanecen almacenadas dentro del expediente
                digital de la unidad en Control de Unidades CEDIS.

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FIRMAS --}}
    {{-- ========================================================= --}}

    <table class="signatures">

        <tr>

            <td>

                <div class="signature-line">
                    Entrega CEDIS
                </div>

                <div class="signature-name">

                    {{ $milestone
    ->completed_by_name
    ?? 'Nombre / Firma'
                    }}

                </div>

            </td>


            <td>

                <div class="signature-line">
                    Recibe Transportista
                </div>

                <div class="signature-name">

                    {{ $delivery
    ->operator_name
    ?: 'Nombre / Firma'
                    }}

                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <div class="footer">

        <span class="footer-left">
            Grupo Rise · Control de Unidades CEDIS
        </span>

        <span class="footer-right">
            {{ $guideNumber }}
        </span>

    </div>

</body>

</html>