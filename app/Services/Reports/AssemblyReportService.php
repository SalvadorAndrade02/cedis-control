<?php

namespace App\Services\Reports;

use App\Enums\AssemblyWorkStatus;
use App\Models\AssemblyWorkPause;
use App\Models\AssemblyWorkSession;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class AssemblyReportService
{
    /**
     * Consulta base de armados finalizados.
     */
    public function completedQuery(
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $search = null,
        ?string $worker = null,
    ): Builder {

        $query = AssemblyWorkSession::query()
            ->with([
                'unit.brand',
            ])
            ->whereHas('unit')
            ->where(
                'status',
                AssemblyWorkStatus::COMPLETED->value
            )
            ->whereNotNull(
                'completed_at'
            );


        /*
        |--------------------------------------------------------------------------
        | FECHA INICIAL
        |--------------------------------------------------------------------------
        |
        | Las fechas seleccionadas por el usuario corresponden
        | al huso horario de visualización.
        |
        | La BD continúa trabajando en UTC.
        |
        */

        if ($startDate) {

            $query->where(
                'completed_at',
                '>=',
                $this->startOfDayUtc(
                    $startDate
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHA FINAL
        |--------------------------------------------------------------------------
        */

        if ($endDate) {

            $query->where(
                'completed_at',
                '<=',
                $this->endOfDayUtc(
                    $endDate
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ARMADOR
        |--------------------------------------------------------------------------
        */

        if (
            $worker !== null
            && trim($worker) !== ''
        ) {

            $query->where(
                'completed_by_name',
                trim($worker)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        |
        | VIN
        | Marca
        | Modelo
        |
        */

        if (
            $search !== null
            && trim($search) !== ''
        ) {

            $search =
                trim($search);

            $like =
                '%' . $search . '%';


            $query->whereHas(
                'unit',
                function ($unitQuery) use ($like) {

                    $unitQuery->where(
                        function ($query) use ($like) {

                            $query
                                ->where(
                                    'vin',
                                    'like',
                                    $like
                                )
                                ->orWhere(
                                    'model',
                                    'like',
                                    $like
                                )
                                ->orWhereHas(
                                    'brand',
                                    fn($brandQuery) =>
                                        $brandQuery->where(
                                            'name',
                                            'like',
                                            $like
                                        )
                                );
                        }
                    );
                }
            );
        }


        return $query
            ->orderByDesc(
                'completed_at'
            );
    }


    /**
     * Consulta de armados actualmente abiertos.
     *
     * Incluye:
     *
     * RUNNING
     * PAUSED
     */
    public function inProgressQuery(
        ?string $search = null,
        ?AssemblyWorkStatus $status = null,
        ?string $worker = null,
    ): Builder {

        $query = AssemblyWorkSession::query()
            ->with([
                'unit.brand',

                'pauses' => fn($query) =>
                    $query->orderByDesc(
                        'paused_at'
                    ),
            ])
            ->whereHas('unit')
            ->whereIn(
                'status',
                [
                    AssemblyWorkStatus::RUNNING->value,
                    AssemblyWorkStatus::PAUSED->value,
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | FILTRAR RUNNING / PAUSED
        |--------------------------------------------------------------------------
        */

        if (
            $status === AssemblyWorkStatus::RUNNING
            || $status === AssemblyWorkStatus::PAUSED
        ) {

            $query->where(
                'status',
                $status->value
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        if (
            $search !== null
            && trim($search) !== ''
        ) {

            $search =
                trim($search);

            $like =
                '%' . $search . '%';


            $query->whereHas(
                'unit',
                function ($unitQuery) use ($like) {

                    $unitQuery->where(
                        function ($query) use ($like) {

                            $query
                                ->where(
                                    'vin',
                                    'like',
                                    $like
                                )
                                ->orWhere(
                                    'model',
                                    'like',
                                    $like
                                )
                                ->orWhereHas(
                                    'brand',
                                    fn($brandQuery) =>
                                        $brandQuery->where(
                                            'name',
                                            'like',
                                            $like
                                        )
                                );
                        }
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ARMADOR
        |--------------------------------------------------------------------------
        |
        | RUNNING:
        | current_worker_name
        |
        | PAUSED:
        | el trabajador está almacenado en la pausa abierta.
        |
        */

        if (
            $worker !== null
            && trim($worker) !== ''
        ) {

            $worker =
                trim($worker);


            $query->where(
                function ($query) use ($worker) {

                    /*
                     * Sesión activa.
                     */
                    $query
                        ->where(
                            function ($running) use ($worker) {

                                $running
                                    ->where(
                                        'status',
                                        AssemblyWorkStatus::RUNNING->value
                                    )
                                    ->where(
                                        'current_worker_name',
                                        $worker
                                    );
                            }
                        )


                        /*
                         * Sesión pausada.
                         */
                        ->orWhere(
                            function ($paused) use ($worker) {

                                $paused
                                    ->where(
                                        'status',
                                        AssemblyWorkStatus::PAUSED->value
                                    )
                                    ->whereHas(
                                        'pauses',
                                        function ($pauseQuery) use ($worker) {

                                            $pauseQuery
                                                ->whereNull(
                                                    'resumed_at'
                                                )
                                                ->where(
                                                    'paused_by_name',
                                                    $worker
                                                );
                                        }
                                    );
                            }
                        );
                }
            );
        }


        return $query
            ->orderBy(
                'status'
            )
            ->orderBy(
                'started_at'
            );
    }


    /**
     * Calcula el tiempo efectivo actual.
     *
     * COMPLETED:
     * utiliza el snapshot definitivo guardado.
     *
     * RUNNING:
     * elapsed hasta ahora - pausas cerradas.
     *
     * PAUSED:
     * elapsed hasta que inició la pausa actual
     * - pausas anteriores.
     */
    public function effectiveSeconds(
        AssemblyWorkSession $session,
        ?CarbonInterface $now = null
    ): int {

        /*
         * Un armado finalizado ya tiene
         * el tiempo definitivo calculado.
         */
        if (
            $session->status
            === AssemblyWorkStatus::COMPLETED
        ) {

            return max(
                0,
                (int) $session->total_active_seconds
            );
        }


        if (!$session->started_at) {

            return 0;
        }


        $now ??=
            now();


        /*
        |--------------------------------------------------------------------------
        | PAUSED
        |--------------------------------------------------------------------------
        */

        if (
            $session->status
            === AssemblyWorkStatus::PAUSED
        ) {

            $currentPause =
                $this->currentPause(
                    $session
                );


            /*
             * Normalmente siempre habrá una pausa
             * abierta cuando status = PAUSED.
             *
             * El fallback a now() evita romper
             * el reporte ante datos históricos
             * inconsistentes.
             */
            $end =
                $currentPause?->paused_at
                ?? $now;

        } else {

            /*
             * RUNNING.
             */
            $end =
                $now;
        }


        $elapsedSeconds =
            (int) $session
                ->started_at
                ->diffInSeconds(
                    $end
                );


        $pausedSeconds =
            (int) $session
                ->total_paused_seconds;


        return max(
            0,
            $elapsedSeconds
            - $pausedSeconds
        );
    }


    /**
     * Nombre que debe mostrarse como armador
     * dentro del reporte.
     */
    public function workerName(
        AssemblyWorkSession $session
    ): string {

        /*
        |--------------------------------------------------------------------------
        | FINALIZADO
        |--------------------------------------------------------------------------
        */

        if (
            $session->status
            === AssemblyWorkStatus::COMPLETED
        ) {

            return
                $session->completed_by_name
                ?: $session->started_by_name
                ?: '—';
        }


        /*
        |--------------------------------------------------------------------------
        | EN ARMADO
        |--------------------------------------------------------------------------
        */

        if (
            $session->status
            === AssemblyWorkStatus::RUNNING
        ) {

            return
                $session->current_worker_name
                ?: $session->started_by_name
                ?: '—';
        }


        /*
        |--------------------------------------------------------------------------
        | PAUSADO
        |--------------------------------------------------------------------------
        */

        if (
            $session->status
            === AssemblyWorkStatus::PAUSED
        ) {

            $currentPause =
                $this->currentPause(
                    $session
                );


            return
                $currentPause?->paused_by_name
                ?: $session->started_by_name
                ?: '—';
        }


        return '—';
    }


    /**
     * Obtiene la pausa que actualmente
     * mantiene detenida la sesión.
     */
    public function currentPause(
        AssemblyWorkSession $session
    ): ?AssemblyWorkPause {

        /*
         * Si pauses ya fue eager-loaded,
         * evitamos otra consulta SQL.
         */
        if (
            $session->relationLoaded(
                'pauses'
            )
        ) {

            return $session
                ->pauses
                ->first(
                    fn($pause) =>
                        $pause->resumed_at
                        === null
                );
        }


        return $session
            ->pauses()
            ->whereNull(
                'resumed_at'
            )
            ->orderByDesc(
                'paused_at'
            )
            ->first();
    }


    /**
     * Resumen de armados finalizados.
     */
    public function completedSummary(
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $search = null,
        ?string $worker = null,
    ): array {

        $baseQuery =
            $this->completedQuery(
                startDate:
                $startDate,

                endDate:
                $endDate,

                search:
                $search,

                worker:
                $worker,
            )
                ->reorder();


        $total =
            (clone $baseQuery)
                ->count();


        $totalSeconds =
            (int) (
                (clone $baseQuery)
                    ->sum(
                        'total_active_seconds'
                    )
            );


        $averageSeconds =
            $total > 0
            ? (int) round(
                $totalSeconds
                / $total
            )
            : 0;


        $workers =
            (clone $baseQuery)
                ->whereNotNull(
                    'completed_by_name'
                )
                ->distinct()
                ->count(
                    'completed_by_name'
                );


        return [
            'total' =>
                $total,

            'total_seconds' =>
                $totalSeconds,

            'average_seconds' =>
                $averageSeconds,

            'workers' =>
                $workers,
        ];
    }


    /**
     * Resumen actual de trabajos abiertos.
     */
    public function inProgressSummary(
        ?string $search = null,
        ?AssemblyWorkStatus $status = null,
        ?string $worker = null,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | MISMA CONSULTA DE R002
        |--------------------------------------------------------------------------
        |
        | Así los indicadores respetan:
        |
        | - búsqueda
        | - estado
        | - armador
        |
        */

        $baseQuery =
            $this
                ->inProgressQuery(
                    search: $search,
                    status: $status,
                    worker: $worker,
                )
                ->reorder();


        /*
        |--------------------------------------------------------------------------
        | EN ARMADO
        |--------------------------------------------------------------------------
        */

        $running =
            (clone $baseQuery)
                ->where(
                    'status',
                    AssemblyWorkStatus::RUNNING->value
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | PAUSADOS
        |--------------------------------------------------------------------------
        */

        $paused =
            (clone $baseQuery)
                ->where(
                    'status',
                    AssemblyWorkStatus::PAUSED->value
                )
                ->count();


        return [
            'running' =>
                $running,

            'paused' =>
                $paused,

            'total' =>
                $running + $paused,
        ];
    }


    /**
     * Convierte YYYY-MM-DD del usuario
     * al inicio del día en UTC.
     */
    private function startOfDayUtc(
        string $date
    ): CarbonImmutable {

        return CarbonImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $date . ' 00:00:00',
            $this->displayTimezone()
        )
            ->utc();
    }


    /**
     * Convierte YYYY-MM-DD del usuario
     * al final del día en UTC.
     */
    private function endOfDayUtc(
        string $date
    ): CarbonImmutable {

        return CarbonImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $date . ' 23:59:59',
            $this->displayTimezone()
        )
            ->utc();
    }


    /**
     * Zona horaria utilizada por CEDIS
     * para presentar fechas al usuario.
     */
    private function displayTimezone(): string
    {
        return config(
            'cedis.display_timezone',
            'America/Monterrey'
        );
    }
}