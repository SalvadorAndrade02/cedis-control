<?php

namespace App\Livewire;

use App\Enums\MilestoneStage;
use App\Enums\UnitStatus;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OperationalQueue extends Component
{
    use WithPagination;

    public string $type;

    public string $search = '';

    public function mount(string $type): void
    {
        abort_unless(
            in_array(
                $type,
                [
                    'arrival',
                    'assembly',
                    'delivery',
                ],
                true
            ),
            404
        );

        abort_unless(
            Auth::user()?->can(
                $this->permissionFor($type)
            ),
            403
        );

        $this->type = $type;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function statusFor(
        string $type
    ): UnitStatus {
        return match ($type) {
            'arrival' =>
            UnitStatus::ARRIVAL_PENDING,

            'assembly' =>
            UnitStatus::ASSEMBLY_PENDING,

            'delivery' =>
            UnitStatus::DELIVERY_PENDING,
        };
    }

    private function permissionFor(
        string $type
    ): string {
        return match ($type) {
            'arrival' =>
            'arrival.view',

            'assembly' =>
            'assembly.view',

            'delivery' =>
            'delivery.view',
        };
    }

    private function previousMilestoneFor(
        string $type
    ): ?MilestoneStage {
        return match ($type) {
            'arrival' =>
            null,

            'assembly' =>
            MilestoneStage::ARRIVAL,

            'delivery' =>
            MilestoneStage::ASSEMBLY_COMPLETED,
        };
    }

    private function titleFor(
        string $type
    ): string {
        return match ($type) {
            'arrival' =>
            'Llegadas pendientes',

            'assembly' =>
            'Armados pendientes',

            'delivery' =>
            'Entregas pendientes',
        };
    }

    private function descriptionFor(
        string $type
    ): string {
        return match ($type) {
            'arrival' =>
            'Unidades que aún requieren evidencia de llegada al CEDIS.',

            'assembly' =>
            'Unidades recibidas que requieren evidencia de armado finalizado.',

            'delivery' =>
            'Unidades con armado finalizado listas para entrega a transportadora.',
        };
    }

    private function actionLabelFor(
        string $type
    ): string {
        return match ($type) {
            'arrival' =>
            'Registrar llegada',

            'assembly' =>
            'Registrar armado',

            'delivery' =>
            'Registrar entrega',
        };
    }

    private function completedTodayCount(
        string $type
    ): int {
        $stage = match ($type) {
            'arrival' =>
            MilestoneStage::ARRIVAL,

            'assembly' =>
            MilestoneStage::ASSEMBLY_COMPLETED,

            'delivery' =>
            MilestoneStage::CARRIER_DELIVERY,
        };

        return \App\Models\UnitMilestone::query()
            ->where(
                'stage',
                $stage->value
            )
            ->whereDate(
                'completed_at',
                now()->toDateString()
            )
            ->count();
    }

    public function render()
    {
        $status =
            $this->statusFor(
                $this->type
            );

        $previousStage =
            $this->previousMilestoneFor(
                $this->type
            );

        $units = Unit::query()
            ->with([
                'brand',

                'milestones' => function (
                    $query
                ) {
                    $query
                        ->with('completedBy');
                },
            ])
            ->where(
                'status',
                $status->value
            )
            ->when(
                $this->search !== '',
                function (
                    Builder $query
                ) {
                    $search = trim(
                        $this->search
                    );

                    $query->where(
                        function (
                            Builder $query
                        ) use ($search) {
                            $query
                                ->where(
                                    'vin',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'model',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'brand',
                                    function (
                                        Builder $brandQuery
                                    ) use ($search) {
                                        $brandQuery
                                            ->where(
                                                'name',
                                                'like',
                                                "%{$search}%"
                                            );
                                    }
                                );
                        }
                    );
                }
            )
            ->oldest()
            ->paginate(12);

        return view(
            'livewire.operational-queue',
            [
                'units' =>
                $units,

                'title' =>
                $this->titleFor(
                    $this->type
                ),

                'description' =>
                $this->descriptionFor(
                    $this->type
                ),

                'actionLabel' =>
                $this->actionLabelFor(
                    $this->type
                ),

                'previousStage' =>
                $previousStage,

                'completedToday' =>
                $this->completedTodayCount(
                    $this->type
                ),
            ]
        );
    }
}
