<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Reporte de Armados Finalizados</title>

    <style>
        @page {
            margin: 20px 24px 30px 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 7.5px;
            line-height: 1.25;
            color: #0f172a;
            background: #ffffff;
        }


        /*
/*
|--------------------------------------------------------------------------
| HEADER CORPORATIVO
|--------------------------------------------------------------------------
*/

        .report-header {
            position: relative;

            width: 100%;
            height: 48px;

            overflow: hidden;

            background: #0b1220;

            page-break-inside: avoid;
            page-break-after: avoid;
        }


        .rise-brand {
            position: absolute;

            top: 16px;
            left: 14px;

            width: 125px;

            color: #ffffff;

            font-size: 11px;
            font-weight: bold;

            letter-spacing: 0.6px;
        }


        .report-heading {
            position: absolute;

            top: 10px;
            left: 155px;
            right: 70px;
        }


        .header-title {
            color: #ffffff;

            font-size: 11px;
            font-weight: bold;

            line-height: 1.2;
        }


        .header-subtitle {
            margin-top: 3px;

            color: #94a3b8;

            font-size: 6px;
        }


        .report-code {
            position: absolute;

            top: 13px;
            right: 14px;

            width: 42px;

            padding: 4px 0;

            background: #f59e0b;

            color: #ffffff;

            font-size: 6px;
            font-weight: bold;

            text-align: center;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL REPORTE
        |--------------------------------------------------------------------------
        */

        .meta-table {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        .meta-table td {
            width: 25%;
            border: 1px solid #e2e8f0;
            padding: 5px 7px;
            background: #f8fafc;
            vertical-align: top;
        }

        .meta-label {
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #94a3b8;
        }

        .meta-value {
            margin-top: 2px;
            font-size: 7px;
            font-weight: 600;
            color: #1e293b;
        }

        .meta-table,
        .summary-table,
        .section-header {
            page-break-inside: avoid;
        }


        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */

        .summary-table {
            width: 100%;
            margin-top: 7px;
            border-collapse: collapse;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            background: #ffffff;
        }

        .summary-table td.highlight {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .summary-label {
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
        }

        .summary-value {
            margin-top: 2px;
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-value.amber {
            color: #b45309;
        }


        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        .section-header {
            margin-top: 9px;
            margin-bottom: 5px;
        }

        .section-eyebrow {
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #d97706;
        }

        .section-title {
            margin-top: 1px;
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
        }

        .section-description {
            margin-top: 1px;
            font-size: 6.5px;
            color: #64748b;
        }


        /*
        |--------------------------------------------------------------------------
        | TABLA PRINCIPAL
        |--------------------------------------------------------------------------
        */

        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table th {
            padding: 5px 4px;
            border: 1px solid #d97706;
            background: #f59e0b;
            color: #ffffff;
            font-size: 5.8px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 5px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 6.5px;
        }

        .data-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .data-table tr {
            page-break-inside: avoid;
        }


        /*
        |--------------------------------------------------------------------------
        | COLUMNAS
        |--------------------------------------------------------------------------
        */

        .col-status {
            width: 8%;
        }

        .col-worker {
            width: 15%;
        }

        .col-date {
            width: 15%;
        }

        .col-brand {
            width: 10%;
        }

        .col-model {
            width: 21%;
        }

        .col-vin {
            width: 20%;
        }

        .col-time {
            width: 11%;
        }


        /*
        |--------------------------------------------------------------------------
        | CELDAS
        |--------------------------------------------------------------------------
        */

        .status-badge {
            display: inline-block;
            padding: 2px 4px;
            background: #dcfce7;
            color: #15803d;
            font-size: 5.8px;
            font-weight: bold;
        }

        .worker {
            font-weight: 600;
        }

        .brand {
            font-weight: bold;
            color: #b45309;
        }

        .model {
            color: #334155;
            word-wrap: break-word;
        }

        .vin {
            font-family: "DejaVu Sans Mono", monospace;
            font-size: 6px;
            color: #475569;
            word-wrap: break-word;
        }

        .duration {
            text-align: right;
            font-family: "DejaVu Sans Mono", monospace;
            font-size: 6.5px;
            font-weight: bold;
            color: #92400e;
        }


        /*
        |--------------------------------------------------------------------------
        | VACÍO
        |--------------------------------------------------------------------------
        */

        .empty-state {
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
            background: #f8fafc;
            color: #64748b;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            position: fixed;
            right: 0;
            bottom: -18px;
            left: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 5.8px;
            color: #94a3b8;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
            margin-right: 90px;
        }
    </style>

</head>


<body>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    {{-- ========================================================= --}}
    {{-- HEADER CORPORATIVO --}}
    {{-- ========================================================= --}}

    <div class="report-header">

        <div class="rise-brand">
            GRUPO RISE
        </div>


        <div class="report-heading">

            <div class="header-title">
                Reporte de Armados Finalizados
            </div>

            <div class="header-subtitle">
                Control de Unidades CEDIS
            </div>

        </div>


        <div class="report-code">
            R001
        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}

    <table class="meta-table">

        <tr>

            <td>

                <div class="meta-label">
                    Periodo
                </div>

                <div class="meta-value">
                    {{ $periodLabel }}
                </div>

            </td>


            <td>

                <div class="meta-label">
                    Armador
                </div>

                <div class="meta-value">
                    {{ $workerLabel }}
                </div>

            </td>


            <td>

                <div class="meta-label">
                    Búsqueda
                </div>

                <div class="meta-value">
                    {{ $searchLabel }}
                </div>

            </td>


            <td>

                <div class="meta-label">
                    Generado
                </div>

                <div class="meta-value">
                    {{ $generatedAt->format('d/m/Y H:i') }}
                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- RESUMEN --}}
    {{-- ========================================================= --}}

    <table class="summary-table">

        <tr>

            <td class="highlight">

                <div class="summary-label">
                    Armados
                </div>

                <div class="summary-value amber">
                    {{ $summary['total'] }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Tiempo efectivo
                </div>

                <div class="summary-value">
                    {{ \App\Support\DurationHelper::format(
    $summary['total_seconds']
) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Promedio
                </div>

                <div class="summary-value">
                    {{ \App\Support\DurationHelper::format(
    $summary['average_seconds']
) }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Armadores
                </div>

                <div class="summary-value">
                    {{ $summary['workers'] }}
                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- DETALLE --}}
    {{-- ========================================================= --}}

    <div class="section-header">

        <div class="section-eyebrow">
            R001 · Detalle operativo
        </div>

        <div class="section-title">
            Armados finalizados
        </div>

        <div class="section-description">
            {{ $sessions->count() }} registro(s)
        </div>

    </div>



    @if ($sessions->isNotEmpty())

        <table class="data-table">

            <thead>

                <tr>

                    <th class="col-status">
                        Estatus
                    </th>

                    <th class="col-worker">
                        Armador
                    </th>

                    <th class="col-date">
                        Finalización
                    </th>

                    <th class="col-brand">
                        Marca
                    </th>

                    <th class="col-model">
                        Modelo
                    </th>

                    <th class="col-vin">
                        VIN
                    </th>

                    <th class="col-time">
                        Tiempo
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach ($sessions as $session)

                        <tr>

                            <td>
                                <span class="status-badge">
                                    Armado
                                </span>
                            </td>


                            <td class="worker">
                                {{ $session->report_worker_name }}
                            </td>


                            <td>
                                {{ \App\Support\DateHelper::format(
                        $session->completed_at
                    ) }}
                            </td>


                            <td class="brand">
                                {{ $session->unit?->brand?->name ?? '—' }}
                            </td>


                            <td class="model">
                                {{ $session->unit?->model ?? '—' }}
                            </td>


                            <td class="vin">
                                {{ $session->unit?->vin ?? '—' }}
                            </td>


                            <td class="duration">
                                {{ \App\Support\DurationHelper::format(
                        $session->report_effective_seconds
                    ) }}
                            </td>

                        </tr>

                @endforeach

            </tbody>

        </table>


    @else

        <div class="empty-state">
            No se encontraron registros.
        </div>

    @endif



    <div class="footer">

        <span class="footer-left">
            Grupo Rise · Control de Unidades CEDIS
        </span>

        <span class="footer-right">
            R001
        </span>

    </div>

</body>

</html>