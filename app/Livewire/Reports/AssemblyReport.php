<?php

namespace App\Livewire\Reports;

use App\Enums\AssemblyWorkStatus;
use App\Models\AssemblyWorkSession;
use App\Services\Reports\AssemblyReportService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\Reports\AssemblyCompletedExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Reports\AssemblyInProgressExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AssemblyReport extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PESTAÑA
    |--------------------------------------------------------------------------
    */

    public string $tab = 'completed';


    /*
    |--------------------------------------------------------------------------
    | FINALIZADOS
    |--------------------------------------------------------------------------
    */

    public string $startDate = '';

    public string $endDate = '';

    public string $completedSearch = '';

    public string $completedWorker = '';


    /*
    |--------------------------------------------------------------------------
    | EN PROCESO
    |--------------------------------------------------------------------------
    */

    public string $progressSearch = '';

    public string $progressWorker = '';

    public string $progressStatus = '';


    /*
    |--------------------------------------------------------------------------
    | INICIALIZACIÓN
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $timezone = config(
            'cedis.display_timezone',
            'America/Monterrey'
        );

        $today = Carbon::now(
            $timezone
        );

        /*
         * Por defecto mostramos el mes actual.
         */
        $this->startDate =
            $today
                ->copy()
                ->startOfMonth()
                ->toDateString();

        $this->endDate =
            $today
                ->toDateString();
    }


    /*
    |--------------------------------------------------------------------------
    | CAMBIO DE PESTAÑA
    |--------------------------------------------------------------------------
    */

    public function selectTab(
        string $tab
    ): void {

        if (
            !in_array(
                $tab,
                [
                    'completed',
                    'in_progress',
                ],
                true
            )
        ) {
            return;
        }

        $this->tab = $tab;
    }


    /*
    |--------------------------------------------------------------------------
    | RESETEAR PAGINACIÓN
    |--------------------------------------------------------------------------
    */

    public function updatedStartDate(): void
    {
        /*
        |--------------------------------------------------------------------------
        | AJUSTAR FECHA FINAL
        |--------------------------------------------------------------------------
        |
        | Si el usuario mueve la fecha inicial
        | después de la fecha final actual,
        | movemos también la fecha final.
        |
        */

        if (
            $this->startDate
            && $this->endDate
            && $this->startDate > $this->endDate
        ) {
            $this->endDate =
                $this->startDate;
        }


        $this->resetPage(
            'completedPage'
        );
    }


    public function updatedEndDate(): void
    {
        /*
        |--------------------------------------------------------------------------
        | AJUSTAR FECHA INICIAL
        |--------------------------------------------------------------------------
        |
        | Si el usuario mueve la fecha final
        | antes de la fecha inicial actual,
        | movemos también la fecha inicial.
        |
        */

        if (
            $this->startDate
            && $this->endDate
            && $this->endDate < $this->startDate
        ) {
            $this->startDate =
                $this->endDate;
        }


        $this->resetPage(
            'completedPage'
        );
    }

    public function updatedCompletedSearch(): void
    {
        $this->resetPage(
            'completedPage'
        );
    }

    public function updatedCompletedWorker(): void
    {
        $this->resetPage(
            'completedPage'
        );
    }

    public function updatedProgressSearch(): void
    {
        $this->resetPage(
            'progressPage'
        );
    }

    public function updatedProgressWorker(): void
    {
        $this->resetPage(
            'progressPage'
        );
    }

    public function updatedProgressStatus(): void
    {
        $this->resetPage(
            'progressPage'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LIMPIAR FILTROS
    |--------------------------------------------------------------------------
    */

    public function resetCompletedFilters(): void
    {
        $timezone = config(
            'cedis.display_timezone',
            'America/Monterrey'
        );

        $today = Carbon::now(
            $timezone
        );

        $this->startDate =
            $today
                ->copy()
                ->startOfMonth()
                ->toDateString();

        $this->endDate =
            $today
                ->toDateString();

        $this->completedSearch = '';

        $this->completedWorker = '';

        $this->resetPage(
            'completedPage'
        );
    }


    public function resetProgressFilters(): void
    {
        $this->progressSearch = '';

        $this->progressWorker = '';

        $this->progressStatus = '';

        $this->resetPage(
            'progressPage'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $service = app(
            AssemblyReportService::class
        );


        /*
        |--------------------------------------------------------------------------
        | FINALIZADOS
        |--------------------------------------------------------------------------
        */

        $completedSessions =
            $service
                ->completedQuery(
                    startDate:
                    $this->startDate ?: null,

                    endDate:
                    $this->endDate ?: null,

                    search:
                    $this->completedSearch ?: null,

                    worker:
                    $this->completedWorker ?: null,
                )
                ->paginate(
                    10,
                    ['*'],
                    'completedPage'
                );


        /*
         * Agregamos datos únicamente
         * para presentación.
         */
        $completedSessions
            ->getCollection()
            ->transform(
                function ($session) use ($service) {

                    $session->report_worker_name =
                        $service->workerName(
                            $session
                        );

                    $session->report_effective_seconds =
                        $service->effectiveSeconds(
                            $session
                        );

                    return $session;
                }
            );


        $completedSummary =
            $service->completedSummary(
                startDate:
                $this->startDate ?: null,

                endDate:
                $this->endDate ?: null,

                search:
                $this->completedSearch ?: null,

                worker:
                $this->completedWorker ?: null,
            );


        /*
        |--------------------------------------------------------------------------
        | ARMADORES FINALIZADOS
        |--------------------------------------------------------------------------
        */

        $completedWorkers =
            AssemblyWorkSession::query()
                ->where(
                    'status',
                    AssemblyWorkStatus::COMPLETED->value
                )
                ->whereNotNull(
                    'completed_by_name'
                )
                ->where(
                    'completed_by_name',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'completed_by_name'
                )
                ->pluck(
                    'completed_by_name'
                );


        /*
        |--------------------------------------------------------------------------
        | EN PROCESO
        |--------------------------------------------------------------------------
        */

        $progressStatus = match (
        $this->progressStatus
        ) {

            AssemblyWorkStatus::RUNNING->value =>
                AssemblyWorkStatus::RUNNING,

            AssemblyWorkStatus::PAUSED->value =>
                AssemblyWorkStatus::PAUSED,

            default =>
                null,
        };


        $progressSessions =
            $service
                ->inProgressQuery(
                    search:
                    $this->progressSearch ?: null,

                    status:
                    $progressStatus,

                    worker:
                    $this->progressWorker ?: null,
                )
                ->paginate(
                    10,
                    ['*'],
                    'progressPage'
                );


        $progressSessions
            ->getCollection()
            ->transform(
                function ($session) use ($service) {

                    $session->report_worker_name =
                        $service->workerName(
                            $session
                        );

                    $session->report_effective_seconds =
                        $service->effectiveSeconds(
                            $session
                        );

                    $session->report_current_pause =
                        $service->currentPause(
                            $session
                        );

                    return $session;
                }
            );


        $progressSummary =
            $service->inProgressSummary();


        /*
        |--------------------------------------------------------------------------
        | ARMADORES ABIERTOS
        |--------------------------------------------------------------------------
        */

        $progressWorkers =
            AssemblyWorkSession::query()
                ->with([
                    'pauses' => fn($query) =>
                        $query
                            ->whereNull(
                                'resumed_at'
                            )
                            ->orderByDesc(
                                'paused_at'
                            ),
                ])
                ->whereIn(
                    'status',
                    [
                        AssemblyWorkStatus::RUNNING->value,
                        AssemblyWorkStatus::PAUSED->value,
                    ]
                )
                ->get()
                ->map(
                    fn($session) =>
                        $service->workerName(
                            $session
                        )
                )
                ->filter(
                    fn($name) =>
                        $name !== '—'
                )
                ->unique()
                ->sort()
                ->values();


        return view(
            'livewire.reports.assembly-report',
            compact(
                'completedSessions',
                'completedSummary',
                'completedWorkers',

                'progressSessions',
                'progressSummary',
                'progressWorkers',
            )
        );
    }

    public function exportCompletedExcel()
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'startDate' => [
                'required',
                'date',
            ],

            'endDate' => [
                'required',
                'date',
                'after_or_equal:startDate',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL ARCHIVO
        |--------------------------------------------------------------------------
        */

        $filename =
            'Reporte_Armados_Finalizados_'
            . $this->startDate
            . '_a_'
            . $this->endDate
            . '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | EXPORTACIÓN
        |--------------------------------------------------------------------------
        */

        return Excel::download(
            new AssemblyCompletedExport(
                startDate:
                $this->startDate ?: null,

                endDate:
                $this->endDate ?: null,

                search:
                $this->completedSearch ?: null,

                worker:
                $this->completedWorker ?: null,
            ),

            $filename
        );
    }

    public function exportProgressExcel()
    {
        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        $status = match (
        $this->progressStatus
        ) {

            AssemblyWorkStatus::RUNNING->value =>
                AssemblyWorkStatus::RUNNING,

            AssemblyWorkStatus::PAUSED->value =>
                AssemblyWorkStatus::PAUSED,

            default =>
                null,
        };


        /*
        |--------------------------------------------------------------------------
        | NOMBRE
        |--------------------------------------------------------------------------
        */

        $timezone = config(
            'cedis.display_timezone',
            'America/Monterrey'
        );


        $filename =
            'Reporte_Armados_En_Proceso_'
            . now($timezone)->format(
                'Y-m-d_His'
            )
            . '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | DESCARGA
        |--------------------------------------------------------------------------
        */

        return Excel::download(
            new AssemblyInProgressExport(
                search:
                $this->progressSearch ?: null,

                status:
                $status,

                worker:
                $this->progressWorker ?: null,
            ),

            $filename
        );
    }

    public function exportCompletedPdf(
        AssemblyReportService $service
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'startDate' => [
                'required',
                'date',
            ],

            'endDate' => [
                'required',
                'date',
                'after_or_equal:startDate',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTROS ACTUALES
        |--------------------------------------------------------------------------
        */

        $startDate =
            $this->startDate ?: null;

        $endDate =
            $this->endDate ?: null;

        $search =
            $this->completedSearch ?: null;

        $worker =
            $this->completedWorker ?: null;


        /*
        |--------------------------------------------------------------------------
        | REGISTROS
        |--------------------------------------------------------------------------
        |
        | Utilizamos exactamente la misma consulta que:
        |
        | - pantalla R001
        | - Excel R001
        |
        */

        $sessions =
            $service
                ->completedQuery(
                    startDate: $startDate,
                    endDate: $endDate,
                    search: $search,
                    worker: $worker,
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | DATOS CALCULADOS PARA PRESENTACIÓN
        |--------------------------------------------------------------------------
        */

        $sessions->transform(
            function ($session) use ($service) {

                $session->report_worker_name =
                    $service->workerName(
                        $session
                    );

                $session->report_effective_seconds =
                    $service->effectiveSeconds(
                        $session
                    );

                return $session;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESUMEN
        |--------------------------------------------------------------------------
        */

        $summary =
            $service->completedSummary(
                startDate: $startDate,
                endDate: $endDate,
                search: $search,
                worker: $worker,
            );


        /*
        |--------------------------------------------------------------------------
        | ZONA HORARIA
        |--------------------------------------------------------------------------
        */

        $timezone =
            config(
                'cedis.display_timezone',
                'America/Monterrey'
            );


        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL REPORTE
        |--------------------------------------------------------------------------
        */

        $periodLabel =
            Carbon::parse(
                $startDate,
                $timezone
            )->format('d/m/Y')
            . ' al '
            . Carbon::parse(
                $endDate,
                $timezone
            )->format('d/m/Y');


        $workerLabel =
            $worker ?: 'Todos';


        $searchLabel =
            $search ?: 'Sin filtro';


        $generatedAt =
            Carbon::now(
                $timezone
            );


        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        |
        | Lo convertimos a Base64.
        |
        | Esto evita problemas de DomPDF intentando acceder
        | directamente a archivos locales.
        |
        */


        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'pdf.reports.assembly-completed',
                [
                    'sessions' =>
                        $sessions,

                    'summary' =>
                        $summary,

                    'periodLabel' =>
                        $periodLabel,

                    'workerLabel' =>
                        $workerLabel,

                    'searchLabel' =>
                        $searchLabel,

                    'generatedAt' =>
                        $generatedAt,
                ]
            )
                ->setPaper(
                    'a4',
                    'landscape'
                )
                ->setOptions([
                    'defaultFont' =>
                        'DejaVu Sans',

                    'isRemoteEnabled' =>
                        false,
                ]);


        /*
        |--------------------------------------------------------------------------
        | RENDER
        |--------------------------------------------------------------------------
        */

        $pdf->render();


        /*
        |--------------------------------------------------------------------------
        | NUMERACIÓN DE PÁGINAS
        |--------------------------------------------------------------------------
        */

        $dompdf =
            $pdf->getDomPDF();


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
            715,
            566,
            'Página {PAGE_NUM} de {PAGE_COUNT}',
            $font,
            6,
            [
                0.39,
                0.45,
                0.55,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL ARCHIVO
        |--------------------------------------------------------------------------
        */

        $filename =
            'Reporte_Armados_Finalizados_'
            . $startDate
            . '_a_'
            . $endDate
            . '.pdf';


        /*
        |--------------------------------------------------------------------------
        | DESCARGA
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(
            function () use ($pdf) {

                echo $pdf->output();
            },

            $filename,

            [
                'Content-Type' =>
                    'application/pdf',
            ]
        );
    }

    public function exportProgressPdf(
        AssemblyReportService $service
    ) {
        /*
        |--------------------------------------------------------------------------
        | ESTADO ACTUAL DEL FILTRO
        |--------------------------------------------------------------------------
        */

        $status = match (
        $this->progressStatus
        ) {

            AssemblyWorkStatus::RUNNING->value =>
                AssemblyWorkStatus::RUNNING,

            AssemblyWorkStatus::PAUSED->value =>
                AssemblyWorkStatus::PAUSED,

            default =>
                null,
        };


        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        $search =
            $this->progressSearch ?: null;

        $worker =
            $this->progressWorker ?: null;


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        |
        | Reutilizamos exactamente R002.
        |
        */

        $sessions =
            $service
                ->inProgressQuery(
                    search:
                    $search,

                    status:
                    $status,

                    worker:
                    $worker,
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | SNAPSHOT DEL MOMENTO
        |--------------------------------------------------------------------------
        |
        | Es importante calcular now() una sola vez.
        |
        | De esa forma todas las sesiones RUNNING del mismo PDF
        | quedan calculadas exactamente contra el mismo instante.
        |
        */

        $snapshotAt =
            now();


        $sessions->transform(
            function ($session) use ($service, $snapshotAt) {

                $session->report_worker_name =
                    $service->workerName(
                        $session
                    );


                $session->report_effective_seconds =
                    $service->effectiveSeconds(
                        $session,
                        $snapshotAt
                    );


                $session->report_current_pause =
                    $service->currentPause(
                        $session
                    );


                return $session;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESUMEN FILTRADO
        |--------------------------------------------------------------------------
        */

        $summary =
            $service->inProgressSummary(
                search:
                $search,

                status:
                $status,

                worker:
                $worker,
            );


        /*
        |--------------------------------------------------------------------------
        | ETIQUETAS
        |--------------------------------------------------------------------------
        */

        $statusLabel =
            match ($status) {

                AssemblyWorkStatus::RUNNING =>
                    'En armado',

                AssemblyWorkStatus::PAUSED =>
                    'Pausados',

                default =>
                    'Todos',
            };


        $workerLabel =
            $worker ?: 'Todos';


        $searchLabel =
            $search ?: 'Sin filtro';


        /*
        |--------------------------------------------------------------------------
        | ZONA HORARIA
        |--------------------------------------------------------------------------
        */

        $timezone =
            config(
                'cedis.display_timezone',
                'America/Monterrey'
            );


        $generatedAt =
            Carbon::now(
                $timezone
            );


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'pdf.reports.assembly-in-progress',
                [
                    'sessions' =>
                        $sessions,

                    'summary' =>
                        $summary,

                    'statusLabel' =>
                        $statusLabel,

                    'workerLabel' =>
                        $workerLabel,

                    'searchLabel' =>
                        $searchLabel,

                    'generatedAt' =>
                        $generatedAt,
                ]
            )
                ->setPaper(
                    'a4',
                    'landscape'
                )
                ->setOptions([
                    'defaultFont' =>
                        'DejaVu Sans',

                    'isRemoteEnabled' =>
                        false,
                ]);


        /*
        |--------------------------------------------------------------------------
        | RENDER
        |--------------------------------------------------------------------------
        */

        $pdf->render();


        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $dompdf =
            $pdf->getDomPDF();


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
            710,
            566,
            'Página {PAGE_NUM} de {PAGE_COUNT}',
            $font,
            6,
            [
                0.39,
                0.45,
                0.55,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | ARCHIVO
        |--------------------------------------------------------------------------
        */

        $filename =
            'Reporte_Armados_En_Proceso_'
            . $generatedAt->format(
                'Y-m-d_His'
            )
            . '.pdf';


        return response()->streamDownload(
            function () use ($pdf) {

                echo $pdf->output();
            },

            $filename,

            [
                'Content-Type' =>
                    'application/pdf',
            ]
        );
    }
}