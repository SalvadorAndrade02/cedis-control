<?php

namespace App\Livewire;

use App\Models\Unit;
use App\Services\Units\DeleteUnitService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

class DeleteUnit extends Component
{
    public int $unitId;

    public bool $showConfirmation = false;

    public string $reason = '';

    public function mount(
        Unit $unit
    ): void {

        abort_unless(
            Auth::user()?->can('units.delete'),
            403
        );

        $this->unitId =
            $unit->id;
    }


    public function open(): void
    {
        $this->showConfirmation =
            true;

        $this->reason = '';

        $this->resetValidation();
    }


    public function cancel(): void
    {
        $this->showConfirmation =
            false;

        $this->reason = '';

        $this->resetValidation();
    }


    public function delete(
        DeleteUnitService $service
    ) {

        abort_unless(
            Auth::user()?->can('units.delete'),
            403
        );


        $this->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);


        try {

            $unit = Unit::findOrFail(
                $this->unitId
            );


            $service->execute(
                unit:
                $unit,

                reason:
                $this->reason,

                userId:
                (int) Auth::id(),
            );


            session()->flash(
                'success',
                'La unidad fue eliminada del flujo operativo.'
            );


            return redirect()
                ->route(
                    'units.index'
                );

        } catch (Throwable $exception) {

            report($exception);

            $this->addError(
                'reason',
                $exception->getMessage()
            );
        }
    }


    public function render()
    {
        return view(
            'livewire.delete-unit'
        );
    }
}