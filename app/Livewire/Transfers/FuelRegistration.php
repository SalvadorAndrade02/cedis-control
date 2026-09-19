<?php

namespace App\Livewire\Transfers;

use App\Enums\FuelAmountOption;
use App\Enums\NoFuelReason;
use App\Enums\TransferAssignmentStatus;
use App\Models\UnitTransferAssignment;
use App\Services\Fuel\RegisterFuelLoadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class FuelRegistration extends Component
{
    use WithFileUploads;


    /*
    |--------------------------------------------------------------------------
    | ASIGNACIÓN
    |--------------------------------------------------------------------------
    */

    #[Locked]
    public int $assignmentId;


    /*
    |--------------------------------------------------------------------------
    | IMPORTE
    |--------------------------------------------------------------------------
    */

    public ?string $amountOption = null;

    public ?string $fixedAmount = null;

    public string $customAmount = '';


    /*
    |--------------------------------------------------------------------------
    | SIN COMBUSTIBLE
    |--------------------------------------------------------------------------
    */

    public ?string $noFuelReason = null;

    public string $reasonNotes = '';


    /*
    |--------------------------------------------------------------------------
    | TICKET
    |--------------------------------------------------------------------------
    |
    | Manejamos cámara y galería independientemente
    | para ofrecer una mejor experiencia móvil.
    |
    */

    public $cameraTicket = null;

    public $galleryTicket = null;


    /*
    |--------------------------------------------------------------------------
    | OBSERVACIONES
    |--------------------------------------------------------------------------
    */

    public string $observations = '';


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(
        int $assignmentId
    ): void {

        $this->assignmentId =
            $assignmentId;


        $assignment =
            UnitTransferAssignment::query()
                ->whereKey(
                    $this->assignmentId
                )
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SEGURIDAD
        |--------------------------------------------------------------------------
        |
        | El trasladista sólo puede abrir sus asignaciones.
        | ADMIN conserva acceso para soporte.
        |
        */

        $user =
            auth()->user();


        abort_unless(
            $user !== null,
            403
        );


        $isAdmin =
            $user->hasRole(
                'ADMIN'
            );


        $isOwner =
            $user->hasRole(
                'TRASLADISTA'
            )
            && (int) $assignment->transporter_user_id
            === (int) $user->id;


        abort_unless(
            $isAdmin || $isOwner,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ASIGNACIÓN ACTIVA
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $assignment->status
            === TransferAssignmentStatus::ASSIGNED,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | NO DEBE EXISTIR REGISTRO PREVIO
        |--------------------------------------------------------------------------
        */

        if (
            $assignment
                ->fuelLoads()
                ->exists()
        ) {
            session()->flash(
                'error',
                'Esta unidad ya cuenta con un registro de combustible.'
            );

            $this->redirectRoute(
                'transfers.mine'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SELECCIONAR IMPORTE PREDEFINIDO
    |--------------------------------------------------------------------------
    */

    public function selectFixedAmount(
        string $amount
    ): void {

        $allowed = [
            '500',
            '300',
            '200',
            '150',
        ];


        if (
            !in_array(
                $amount,
                $allowed,
                true
            )
        ) {
            return;
        }


        $this->amountOption =
            FuelAmountOption::FIXED->value;

        $this->fixedAmount =
            $amount;

        $this->customAmount =
            '';


        /*
         * Como sí habrá carga de combustible,
         * limpiamos motivos de $0.
         */

        $this->noFuelReason =
            null;

        $this->reasonNotes =
            '';


        $this->resetValidation([
            'amountOption',
            'fixedAmount',
            'customAmount',
            'noFuelReason',
            'reasonNotes',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SELECCIONAR "OTRO"
    |--------------------------------------------------------------------------
    */

    public function selectOther(): void
    {
        $this->amountOption =
            FuelAmountOption::OTHER->value;

        $this->fixedAmount =
            null;

        $this->customAmount =
            '';

        $this->noFuelReason =
            null;

        $this->reasonNotes =
            '';

        $this->resetValidation([
            'amountOption',
            'fixedAmount',
            'customAmount',
            'noFuelReason',
            'reasonNotes',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CAMBIAR MONTO PERSONALIZADO
    |--------------------------------------------------------------------------
    */

    public function updatedCustomAmount(): void
    {
        /*
         * Si deja de ser $0, los motivos
         * ya no aplican.
         */

        if (!$this->isZeroAmount()) {

            $this->noFuelReason =
                null;

            $this->reasonNotes =
                '';
        }


        $this->resetValidation([
            'customAmount',
            'noFuelReason',
            'reasonNotes',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CÁMARA
    |--------------------------------------------------------------------------
    */

    public function updatedCameraTicket(): void
    {
        if (!$this->cameraTicket) {
            return;
        }


        $this->validateOnly(
            'cameraTicket',
            [
                'cameraTicket' => [
                    'file',
                    'max:10240',
                ],
            ],
            [
                'cameraTicket.file' =>
                    'El ticket seleccionado no es válido.',

                'cameraTicket.max' =>
                    'La fotografía no puede superar los 10 MB.',
            ]
        );


        /*
         * Si usa cámara, descartamos selección
         * previa de galería.
         */

        $this->galleryTicket =
            null;

        $this->resetValidation(
            'ticket'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GALERÍA
    |--------------------------------------------------------------------------
    */

    public function updatedGalleryTicket(): void
    {
        if (!$this->galleryTicket) {
            return;
        }


        $this->validateOnly(
            'galleryTicket',
            [
                'galleryTicket' => [
                    'file',
                    'max:10240',
                ],
            ],
            [
                'galleryTicket.file' =>
                    'El ticket seleccionado no es válido.',

                'galleryTicket.max' =>
                    'La fotografía no puede superar los 10 MB.',
            ]
        );


        /*
         * Si usa galería, descartamos foto
         * previa de cámara.
         */

        $this->cameraTicket =
            null;

        $this->resetValidation(
            'ticket'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR TICKET SELECCIONADO
    |--------------------------------------------------------------------------
    */

    public function removeTicket(): void
    {
        $this->cameraTicket =
            null;

        $this->galleryTicket =
            null;

        $this->resetValidation([
            'cameraTicket',
            'galleryTicket',
            'ticket',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR
    |--------------------------------------------------------------------------
    */

    public function register(
        RegisterFuelLoadService $service
    ) {

        $user =
            auth()->user();


        if (!$user) {
            throw new AuthorizationException(
                'Usuario no autenticado.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN GENERAL
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'amountOption' => [
                'required',
                'in:FIXED,OTHER',
            ],

            'fixedAmount' => [
                'nullable',
                'in:150,200,300,500',
            ],

            'customAmount' => [
                'nullable',
                'regex:/^\d+(?:\.\d{1,2})?$/',
            ],

            'cameraTicket' => [
                'nullable',
                'file',
                'max:10240',
            ],

            'galleryTicket' => [
                'nullable',
                'file',
                'max:10240',
            ],

            'noFuelReason' => [
                'nullable',
                'string',
            ],

            'reasonNotes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'amountOption.required' =>
                'Selecciona el importe de combustible.',

            'fixedAmount.in' =>
                'El importe seleccionado no es válido.',

            'customAmount.regex' =>
                'Ingresa un importe válido con máximo dos decimales.',

            'cameraTicket.max' =>
                'La fotografía no puede superar los 10 MB.',

            'galleryTicket.max' =>
                'La fotografía no puede superar los 10 MB.',

            'reasonNotes.max' =>
                'La explicación no puede superar los 1000 caracteres.',

            'observations.max' =>
                'Las observaciones no pueden superar los 1000 caracteres.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DETERMINAR IMPORTE
        |--------------------------------------------------------------------------
        */

        if (
            $this->amountOption
            === FuelAmountOption::FIXED->value
        ) {

            if (!$this->fixedAmount) {

                $this->addError(
                    'amountOption',
                    'Selecciona un importe.'
                );

                return;
            }


            $amount =
                $this->fixedAmount;

        } else {

            if (
                trim(
                    $this->customAmount
                ) === ''
            ) {

                $this->addError(
                    'customAmount',
                    'Ingresa el importe.'
                );

                return;
            }


            $amount =
                trim(
                    $this->customAmount
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TICKET ACTUAL
        |--------------------------------------------------------------------------
        */

        $ticket =
            $this->cameraTicket
            ?? $this->galleryTicket;


        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES VISUALES ADICIONALES
        |--------------------------------------------------------------------------
        */

        if ($this->amountIsPositive($amount)) {

            if (!$ticket) {

                $this->addError(
                    'ticket',
                    'Debes tomar o adjuntar una fotografía del ticket.'
                );

                return;
            }

        } elseif ($this->amountIsZero($amount)) {

            if (!$this->noFuelReason) {

                $this->addError(
                    'noFuelReason',
                    'Selecciona el motivo por el que no se cargó gasolina.'
                );

                return;
            }


            if (
                $this->noFuelReason
                === NoFuelReason::OTHER->value
                && blank(
                    $this->reasonNotes
                )
            ) {

                $this->addError(
                    'reasonNotes',
                    'Especifica el motivo.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VOLVER A CARGAR ASIGNACIÓN
        |--------------------------------------------------------------------------
        */

        $assignment =
            UnitTransferAssignment::query()
                ->findOrFail(
                    $this->assignmentId
                );


        /*
        |--------------------------------------------------------------------------
        | EJECUTAR SERVICIO
        |--------------------------------------------------------------------------
        */

        try {

            $service->execute(
                assignment:
                $assignment,

                actor:
                $user,

                amountOption:
                $this->amountOption,

                amount:
                $amount,

                noFuelReason:
                $this->noFuelReason,

                reasonNotes:
                filled(
                    $this->reasonNotes
                )
                ? trim(
                    $this->reasonNotes
                )
                : null,

                ticket:
                $ticket,

                observations:
                filled(
                    $this->observations
                )
                ? trim(
                    $this->observations
                )
                : null,
            );

        } catch (
            ValidationException $exception
        ) {

            foreach (
                $exception->errors()
                as $field => $messages
            ) {

                foreach (
                    $messages
                    as $message
                ) {

                    $this->addError(
                        $field,
                        $message
                    );
                }
            }


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            'El registro de combustible se guardó correctamente.'
        );


        return redirect()
            ->route(
                'transfers.mine'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function isZeroAmount(): bool
    {
        if (
            $this->amountOption
            !== FuelAmountOption::OTHER->value
        ) {
            return false;
        }


        $value =
            trim(
                $this->customAmount
            );


        return $value !== ''
            && is_numeric($value)
            && (float) $value === 0.0;
    }


    private function amountIsZero(
        string $amount
    ): bool {

        return is_numeric($amount)
            && (float) $amount === 0.0;
    }


    private function amountIsPositive(
        string $amount
    ): bool {

        return is_numeric($amount)
            && (float) $amount > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $assignment =
            UnitTransferAssignment::query()
                ->with([
                    'unit.brand',
                    'fuelLoads',
                ])
                ->findOrFail(
                    $this->assignmentId
                );


        $isZeroAmount =
            $this->isZeroAmount();


        $ticket =
            $this->cameraTicket
            ?? $this->galleryTicket;


        return view(
            'livewire.transfers.fuel-registration',
            [
                'assignment' =>
                    $assignment,

                'unit' =>
                    $assignment->unit,

                'isZeroAmount' =>
                    $isZeroAmount,

                'selectedTicket' =>
                    $ticket,

                'noFuelReasons' =>
                    NoFuelReason::cases(),
            ]
        );
    }
}