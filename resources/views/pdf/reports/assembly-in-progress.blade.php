<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Reporte de Armados en Proceso
    </title>


    <style>
        @page {
            margin: 20px 24px 30px 24px;
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            font-family:
                "DejaVu Sans",
                sans-serif;

            font-size: 7.5px;

            line-height: 1.25;

            color: #0f172a;

            background: #ffffff;
        }



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

            background: #2563eb;

            color: #ffffff;

            font-size: 6px;
            font-weight: bold;

            text-align: center;
        }



        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        .meta-table {
            width: 100%;

            margin-top: 8px;

            border-collapse: collapse;

            page-break-inside: avoid;
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



        /*
        |--------------------------------------------------------------------------
        | RESUMEN
        |--------------------------------------------------------------------------
        */

        .summary-table {
            width: 100%;

            margin-top: 7px;

            border-collapse: collapse;

            page-break-inside: avoid;
        }


        .summary-table td {
            width: 33.333%;

            border: 1px solid #e2e8f0;

            padding: 6px 8px;

            background: #ffffff;
        }


        .summary-table td.running {
            border-color: #bfdbfe;

            background: #eff6ff;
        }


        .summary-table td.paused {
            border-color: #fde68a;

            background: #fffbeb;
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


        .summary-value.blue {
            color: #1d4ed8;
        }


        .summary-value.amber {
            color: #b45309;
        }



        /*
        |--------------------------------------------------------------------------
        | TÍTULO DE SECCIÓN
        |--------------------------------------------------------------------------
        */

        .section-header {
            margin-top: 9px;

            margin-bottom: 5px;

            page-break-inside: avoid;
        }


        .section-eyebrow {
            font-size: 5.8px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            color: #2563eb;
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
        | TABLA
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

            border: 1px solid #1d4ed8;

            background: #2563eb;

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
        | ANCHOS
        |--------------------------------------------------------------------------
        */

        .col-status {
            width: 11%;
        }


        .col-worker {
            width: 18%;
        }


        .col-brand {
            width: 12%;
        }


        .col-model {
            width: 24%;
        }


        .col-vin {
            width: 22%;
        }


        .col-time {
            width: 13%;
        }



        /*
        |--------------------------------------------------------------------------
        | CELDAS
        |--------------------------------------------------------------------------
        */

        .status-running {
            display: inline-block;

            padding: 2px 4px;

            background: #dbeafe;

            color: #1d4ed8;

            font-size: 5.8px;

            font-weight: bold;
        }


        .status-paused {
            display: inline-block;

            padding: 2px 4px;

            background: #fef3c7;

            color: #b45309;

            font-size: 5.8px;

            font-weight: bold;
        }


        .worker {
            font-weight: 600;

            color: #1e293b;
        }


        .brand {
            font-weight: bold;

            color: #1d4ed8;
        }


        .model {
            color: #334155;

            word-wrap: break-word;
        }


        .vin {
            font-family:
                "DejaVu Sans Mono",
                monospace;

            font-size: 6px;

            color: #475569;

            word-wrap: break-word;
        }


        .duration {
            text-align: right;

            font-family:
                "DejaVu Sans Mono",
                monospace;

            font-size: 6.5px;

            font-weight: bold;

            color: #0f172a;
        }


        .pause-info {
            margin-top: 2px;

            font-size: 5.5px;

            color: #b45309;
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
    </style>

</head>


<body>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="report-header">

        <div class="rise-brand">
            GRUPO RISE
        </div>


        <div class="report-heading">

            <div class="header-title">
                Reporte de Armados en Proceso
            </div>

            <div class="header-subtitle">
                Grupo Rise · Control de Unidades CEDIS
            </div>

        </div>


        <div class="report-code">
            R002
        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}

    <table class="meta-table">

        <tr>

            <td>

                <div class="meta-label">
                    Estado
                </div>

                <div class="meta-value">
                    {{ $statusLabel }}
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

                    {{ $generatedAt->format(
    'd/m/Y H:i:s'
) }}

                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- RESUMEN --}}
    {{-- ========================================================= --}}

    <table class="summary-table">

        <tr>

            <td class="running">

                <div class="summary-label">
                    En armado
                </div>

                <div class="summary-value blue">
                    {{ $summary['running'] }}
                </div>

            </td>


            <td class="paused">

                <div class="summary-label">
                    Pausados
                </div>

                <div class="summary-value amber">
                    {{ $summary['paused'] }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total en proceso
                </div>

                <div class="summary-value">
                    {{ $summary['total'] }}
                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- SECCIÓN --}}
    {{-- ========================================================= --}}

    <div class="section-header">

        <div class="section-eyebrow">
            R002 · Estado operativo
        </div>

        <div class="section-title">
            Armados en proceso
        </div>

        <div class="section-description">

            {{ $sessions->count() }}
            registro(s) al momento de generar el reporte.

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- TABLA --}}
    {{-- ========================================================= --}}

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
                        Horas invertidas
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach ($sessions as $session)

                        <tr>

                            {{-- ESTADO --}}

                            <td>

                                @if (
                                        $session->status
                                        === \App\Enums\AssemblyWorkStatus::RUNNING
                                    )

                                    <span class="status-running">
                                        Proceso
                                    </span>

                                @else

                                    <span class="status-paused">
                                        Pausado
                                    </span>

                                    @if ($session->report_current_pause)

                                        <div class="pause-info">

                                            {{ $session
                                        ->report_current_pause
                                        ->reason
                                        ->label()
                                                            }}

                                        </div>

                                    @endif

                                @endif

                            </td>



                            {{-- ARMADOR --}}

                            <td class="worker">

                                {{ $session
                        ->report_worker_name
                                        }}

                            </td>



                            {{-- MARCA --}}

                            <td class="brand">

                                {{ $session
                        ->unit
                        ?->brand
                            ?->name
                        ?? '—'
                                        }}

                            </td>



                            {{-- MODELO --}}

                            <td class="model">

                                {{ $session
                        ->unit
                            ?->model
                        ?? '—'
                                        }}

                            </td>



                            {{-- VIN --}}

                            <td class="vin">

                                {{ $session
                        ->unit
                            ?->vin
                        ?? '—'
                                        }}

                            </td>



                            {{-- TIEMPO --}}

                            <td class="duration">

                                {{ \App\Support\DurationHelper::format(
                        $session
                            ->report_effective_seconds
                    ) }}

                            </td>

                        </tr>

                @endforeach

            </tbody>

        </table>


    @else

        <div class="empty-state">

            No existen armados en proceso
            para los filtros seleccionados.

        </div>

    @endif

</body>

</html>