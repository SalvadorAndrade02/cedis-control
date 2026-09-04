<?php

namespace App\Livewire;

use App\Enums\MilestoneStage;
use App\Models\Unit;
use App\Services\Milestones\CompleteAssemblyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class AssemblyEvidence extends Component
{
    use WithFileUploads;

    public int $unitId;

    public array $photos = [];

    public string $observations = '';

    public ?string $errorMessage = null;

    public function mount(Unit $unit): void
    {
        abort_unless(
            Auth::user()?->can('assembly.complete'),
            403
        );

        $this->unitId = $unit->id;
    }

    protected function rules(): array
    {
        return [
            'photos' => [
                'required',
                'array',
                'min:1',
                'max:15',
            ],

            'photos.*' => [
                'image',
                'max:10240',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ];
    }

    public function complete(
        CompleteAssemblyService $service
    ) {
        abort_unless(
            Auth::user()?->can('assembly.complete'),
            403
        );

        $this->validate();

        $this->errorMessage = null;

        try {

            $unit = Unit::findOrFail(
                $this->unitId
            );

            $service->execute(
                unit: $unit,

                photos: $this->photos,

                observations: trim($this->observations)
                    ?: null,

                userId: (int) Auth::id(),
            );

            session()->flash(
                'success',
                'El armado finalizado fue documentado correctamente.'
            );

            return redirect()
                ->route(
                    'units.show',
                    $unit
                );
        } catch (Throwable $exception) {

            report($exception);

            $this->errorMessage =
                $exception->getMessage();
        }
    }

    public function removePhoto(
        int $index
    ): void {
        unset(
            $this->photos[$index]
        );

        $this->photos = array_values(
            $this->photos
        );
    }

    public function render()
    {
        $unit = Unit::query()
            ->with([
                'milestones' => fn($query) =>
                $query->where(
                    'stage',
                    MilestoneStage::ASSEMBLY_COMPLETED->value
                ),
            ])
            ->findOrFail(
                $this->unitId
            );

        $milestone =
            $unit->milestones->first();

        return view(
            'livewire.assembly-evidence',
            compact(
                'unit',
                'milestone'
            )
        );
    }
}
