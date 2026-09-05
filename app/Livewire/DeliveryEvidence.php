<?php

namespace App\Livewire;

use App\Enums\MilestoneStage;
use App\Models\Unit;
use App\Services\Milestones\CompleteDeliveryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class DeliveryEvidence extends Component
{
    use WithFileUploads;

    public int $unitId;

    public string $carrierName = '';

    public string $operatorName = '';

    public string $operatorIdentification = '';

    public string $operatorPhone = '';

    public string $vehiclePlate = '';

    public string $vehicleNumber = '';

    public string $transportType = '';

    public array $photos = [];

    public string $observations = '';

    public ?string $errorMessage = null;

    public function mount(Unit $unit): void
    {
        abort_unless(
            Auth::user()?->can('delivery.complete'),
            403
        );

        $this->unitId = $unit->id;
    }

    protected function rules(): array
    {
        return [
            'carrierName' => [
                'required',
                'string',
                'max:150',
            ],

            'operatorName' => [
                'required',
                'string',
                'max:150',
            ],

            'operatorIdentification' => [
                'nullable',
                'string',
                'max:100',
            ],

            'operatorPhone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'vehiclePlate' => [
                'required',
                'string',
                'max:30',
            ],

            'vehicleNumber' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transportType' => [
                'nullable',
                'string',
                'max:100',
            ],

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
        CompleteDeliveryService $service
    ) {
        abort_unless(
            Auth::user()?->can('delivery.complete'),
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

                carrierName: trim($this->carrierName),

                operatorName: trim($this->operatorName),

                operatorIdentification: trim($this->operatorIdentification)
                    ?: null,

                operatorPhone: trim($this->operatorPhone)
                    ?: null,

                vehiclePlate: trim($this->vehiclePlate),

                vehicleNumber: trim($this->vehicleNumber)
                    ?: null,

                transportType: trim($this->transportType)
                    ?: null,

                photos: $this->photos,

                observations: trim($this->observations)
                    ?: null,

                userId: (int) Auth::id(),
            );

            session()->flash(
                'success',
                'La entrega fue registrada correctamente. El expediente de la unidad está completo.'
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
                    MilestoneStage::CARRIER_DELIVERY->value
                ),
            ])
            ->findOrFail(
                $this->unitId
            );

        $milestone =
            $unit->milestones->first();

        return view(
            'livewire.delivery-evidence',
            compact(
                'unit',
                'milestone'
            )
        );
    }
}
