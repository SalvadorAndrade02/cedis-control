<?php

namespace App\Livewire;

use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\UnitStatus;
use App\Models\Unit;
use App\Models\UnitMilestone;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardOverview extends Component
{
    /**
     * Obtiene el inicio y fin del día del CEDIS,
     * pero convertidos a UTC para consultar BD.
     */
    private function todayUtcRange(): array
    {
        $timezone = config(
            'cedis.display_timezone',
            'America/Monterrey'
        );

        $now = CarbonImmutable::now(
            $timezone
        );

        return [
            $now
                ->startOfDay()
                ->utc(),

            $now
                ->endOfDay()
                ->utc(),
        ];
    }


    /**
     * Configuración reutilizable
     * de las tres áreas operativas.
     */
    private function queueDefinitions(): array
    {
        return [

            'arrival' => [
                'permission' =>
                'arrival.view',

                'status' =>
                UnitStatus::ARRIVAL_PENDING,

                'stage' =>
                MilestoneStage::ARRIVAL,

                'title' =>
                'Llegadas',

                'pendingLabel' =>
                'Pendientes de llegada',

                'completedLabel' =>
                'Recibidas hoy',

                'actionLabel' =>
                'Registrar llegada',

                'route' =>
                'operations.arrivals',
            ],


            'assembly' => [
                'permission' =>
                'assembly.view',

                'status' =>
                UnitStatus::ASSEMBLY_PENDING,

                'stage' =>
                MilestoneStage::ASSEMBLY_COMPLETED,

                'title' =>
                'Armados',

                'pendingLabel' =>
                'Pendientes de armado',

                'completedLabel' =>
                'Finalizadas hoy',

                'actionLabel' =>
                'Registrar armado',

                'route' =>
                'operations.assemblies',
            ],


            'delivery' => [
                'permission' =>
                'delivery.view',

                'status' =>
                UnitStatus::DELIVERY_PENDING,

                'stage' =>
                MilestoneStage::CARRIER_DELIVERY,

                'title' =>
                'Entregas',

                'pendingLabel' =>
                'Pendientes de entrega',

                'completedLabel' =>
                'Entregadas hoy',

                'actionLabel' =>
                'Registrar entrega',

                'route' =>
                'operations.deliveries',
            ],
        ];
    }


    public function render()
    {
        $user = Auth::user();

        abort_unless(
            $user,
            401
        );

        [$todayStart, $todayEnd] =
            $this->todayUtcRange();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMINISTRATIVO
        |--------------------------------------------------------------------------
        |
        | ADMIN y SUPERVISOR tienen reports.view.
        | Esto evita depender directamente del nombre
        | del rol.
        |
        */

        $managementMode =
            $user->can('reports.view')
            || $user->can('users.manage')
            || $user->can('catalogs.manage');


        $managementSummary = null;

        if ($managementMode) {

            $managementSummary = [

                'total' =>
                Unit::count(),

                'importedToday' =>
                Unit::query()
                    ->whereBetween(
                        'created_at',
                        [
                            $todayStart,
                            $todayEnd,
                        ]
                    )
                    ->count(),

                'arrivalPending' =>
                Unit::query()
                    ->where(
                        'status',
                        UnitStatus::ARRIVAL_PENDING
                            ->value
                    )
                    ->count(),

                'assemblyPending' =>
                Unit::query()
                    ->where(
                        'status',
                        UnitStatus::ASSEMBLY_PENDING
                            ->value
                    )
                    ->count(),

                'deliveryPending' =>
                Unit::query()
                    ->where(
                        'status',
                        UnitStatus::DELIVERY_PENDING
                            ->value
                    )
                    ->count(),

                'completed' =>
                Unit::query()
                    ->where(
                        'status',
                        UnitStatus::COMPLETED
                            ->value
                    )
                    ->count(),

                /*
                 * Consideramos expediente terminado
                 * cuando termina CARRIER_DELIVERY.
                 */
                'completedToday' =>
                UnitMilestone::query()
                    ->where(
                        'stage',
                        MilestoneStage::CARRIER_DELIVERY
                            ->value
                    )
                    ->where(
                        'status',
                        MilestoneStatus::COMPLETED
                            ->value
                    )
                    ->whereBetween(
                        'completed_at',
                        [
                            $todayStart,
                            $todayEnd,
                        ]
                    )
                    ->count(),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | BANDEJAS DISPONIBLES PARA ESTE USUARIO
        |--------------------------------------------------------------------------
        */

        $queues = collect(
            $this->queueDefinitions()
        )
            ->filter(
                fn(array $definition) =>
                $user->can(
                    $definition['permission']
                )
            )
            ->map(
                function (
                    array $definition
                ) use (
                    $todayStart,
                    $todayEnd
                ) {

                    $pendingCount =
                        Unit::query()
                        ->where(
                            'status',
                            $definition['status']
                                ->value
                        )
                        ->count();


                    $completedToday =
                        UnitMilestone::query()
                        ->where(
                            'stage',
                            $definition['stage']
                                ->value
                        )
                        ->where(
                            'status',
                            MilestoneStatus::COMPLETED
                                ->value
                        )
                        ->whereBetween(
                            'completed_at',
                            [
                                $todayStart,
                                $todayEnd,
                            ]
                        )
                        ->count();


                    /*
                     * Mostramos máximo 6 unidades
                     * directamente en dashboard.
                     */
                    $units =
                        Unit::query()
                        ->with('brand')
                        ->where(
                            'status',
                            $definition['status']
                                ->value
                        )
                        ->oldest()
                        ->limit(6)
                        ->get();


                    return [
                        ...$definition,

                        'pendingCount' =>
                        $pendingCount,

                        'completedToday' =>
                        $completedToday,

                        'units' =>
                        $units,
                    ];
                }
            );


        return view(
            'livewire.dashboard-overview',
            [
                'managementMode' =>
                $managementMode,

                'managementSummary' =>
                $managementSummary,

                'queues' =>
                $queues,
            ]
        );
    }
}
