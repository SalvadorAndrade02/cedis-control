<?php

namespace App\Livewire\Transfers;

use App\Enums\TransferAssignmentStatus;
use App\Models\UnitTransferAssignment;
use Livewire\Component;
use Livewire\WithPagination;

class MyTransfers extends Component
{
    use WithPagination;


    public string $search = '';

    public ?int $selectedAssignmentId = null;


    public function mount(): void
    {
        /*
        |--------------------------------------------------------------------------
        | PANTALLA EXCLUSIVA DE TRASLADISTAS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->check()
            && auth()->user()->hasRole('TRASLADISTA'),
            403
        );
    }


    public function updatedSearch(): void
    {
        $this->resetPage();
    }


    public function showDetails(
        int $assignmentId
    ): void {
        /*
         * IMPORTANTE:
         *
         * No basta con recibir el ID.
         * Debemos verificar que la asignación realmente
         * pertenezca al usuario conectado.
         */

        $assignment =
            UnitTransferAssignment::query()
                ->whereKey(
                    $assignmentId
                )
                ->where(
                    'transporter_user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->firstOrFail();


        $this->selectedAssignmentId =
            $assignment->id;
    }


    public function closeDetails(): void
    {
        $this->selectedAssignmentId =
            null;
    }


    public function render()
    {
        $assignments =
            UnitTransferAssignment::query()
                ->with([
                    'unit.brand',

                    /*
                     * Nos servirá para indicar si
                     * ya existe registro de gasolina.
                     */
                    'fuelLoads',
                ])
                ->where(
                    'transporter_user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->when(
                    filled($this->search),

                    function ($query) {

                        $search =
                            trim(
                                $this->search
                            );

                        $query->whereHas(
                            'unit',
                            function ($unitQuery) use ($search) {

                                $unitQuery
                                    ->where(
                                        'vin',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'model',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhereHas(
                                        'brand',
                                        fn($brandQuery) =>
                                            $brandQuery->where(
                                                'name',
                                                'like',
                                                '%' . $search . '%'
                                            )
                                    );
                            }
                        );
                    }
                )
                ->latest(
                    'assigned_at'
                )
                ->paginate(12);


        $selectedAssignment =
            $this->selectedAssignmentId
            ? UnitTransferAssignment::query()
                ->with([
                    'unit.brand',
                    'fuelLoads',
                ])
                ->whereKey(
                    $this->selectedAssignmentId
                )
                ->where(
                    'transporter_user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->first()
            : null;


        $totalAssigned =
            UnitTransferAssignment::query()
                ->where(
                    'transporter_user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->count();


        $pendingFuel =
            UnitTransferAssignment::query()
                ->where(
                    'transporter_user_id',
                    auth()->id()
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->whereDoesntHave(
                    'fuelLoads'
                )
                ->count();


        return view(
            'livewire.transfers.my-transfers',
            [
                'assignments' =>
                    $assignments,

                'selectedAssignment' =>
                    $selectedAssignment,

                'totalAssigned' =>
                    $totalAssigned,

                'pendingFuel' =>
                    $pendingFuel,
            ]
        );
    }
}