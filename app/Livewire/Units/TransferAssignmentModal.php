<?php

namespace App\Livewire\Units;

use App\Enums\TransferAssignmentStatus;
use App\Enums\UnitStatus;
use App\Models\Unit;
use App\Models\UnitTransferAssignment;
use App\Models\User;
use App\Services\Transfers\AssignTransferService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TransferAssignmentModal extends Component
{
    /*
    |--------------------------------------------------------------------------
    | UNIDAD
    |--------------------------------------------------------------------------
    |
    | Locked evita que el ID pueda ser modificado desde el cliente.
    |
    */

    #[Locked]
    public int $unitId;
    public string $originName = 'CEDIS';

    public string $destinationName = '';
    public bool $isReassignment = false;


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    public bool $showModal = false;


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO
    |--------------------------------------------------------------------------
    */

    public ?int $transporterId = null;

    public string $notes = '';


    /*
    |--------------------------------------------------------------------------
    | MENSAJE LOCAL
    |--------------------------------------------------------------------------
    */

    public ?string $successMessage = null;


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(int $unitId): void
    {
        $this->unitId = $unitId;
    }


    /*
    |--------------------------------------------------------------------------
    | REGLAS
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'transporterId' => [
                'required',
                'integer',

                Rule::exists('users', 'id')
                    ->where(
                        fn($query) =>
                            $query->where(
                                'active',
                                true
                            )
                    ),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'originName' => [
                'required',
                'string',
                'max:150',
            ],

            'destinationName' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MENSAJES
    |--------------------------------------------------------------------------
    */

    protected function messages(): array
    {
        return [
            'transporterId.required' =>
                'Debes seleccionar un trasladista.',

            'transporterId.exists' =>
                'El trasladista seleccionado no existe o se encuentra inactivo.',

            'notes.max' =>
                'Las observaciones no pueden superar los 1000 caracteres.',

            'originName.required' =>
                'Debes indicar el origen.',

            'destinationName.required' =>
                'Debes indicar el destino.',

            'destinationName.max' =>
                'El destino no puede superar los 255 caracteres.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR MODAL
    |--------------------------------------------------------------------------
    */

    public function openModal(): void
    {
        abort_unless(
            auth()->user()?->can('transfers.assign'),
            403
        );

        $unit = Unit::query()
            ->findOrFail(
                $this->unitId
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR ESTADO DE LA UNIDAD
        |--------------------------------------------------------------------------
        */

        if (
            $unit->status
            !== UnitStatus::DELIVERY_PENDING
        ) {
            $this->addError(
                'unit',
                'La unidad debe encontrarse pendiente de entrega para asignar un trasladista.'
            );

            return;
        }


        $this->resetValidation();

        $this->successMessage = null;


        /*
        |--------------------------------------------------------------------------
        | ASIGNACIÓN ACTUAL
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        | Primero obtenemos $currentAssignment y DESPUÉS
        | utilizamos sus datos.
        |
        */

        $currentAssignment =
            $unit
                ->activeTransferAssignment()
                ->first();

        $this->isReassignment =
            $currentAssignment !== null;


        /*
        |--------------------------------------------------------------------------
        | PRESELECCIONAR TRASLADISTA
        |--------------------------------------------------------------------------
        */

        $this->transporterId =
            $currentAssignment
                    ?->transporter_user_id;


        /*
        |--------------------------------------------------------------------------
        | ORIGEN / DESTINO
        |--------------------------------------------------------------------------
        */

        $this->originName =
            $currentAssignment?->origin_name
            ?: 'CEDIS';

        $this->destinationName =
            $currentAssignment?->destination_name
            ?: '';


        /*
        |--------------------------------------------------------------------------
        | OBSERVACIONES
        |--------------------------------------------------------------------------
        */

        $this->notes =
            $currentAssignment?->notes
            ?: '';


        /*
        |--------------------------------------------------------------------------
        | ABRIR MODAL
        |--------------------------------------------------------------------------
        */

        $this->showModal = true;
    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR MODAL
    |--------------------------------------------------------------------------
    */

    public function closeModal(): void
    {
        $this->showModal = false;

        $this->resetValidation();

        $this->transporterId = null;

        $this->originName = 'CEDIS';

        $this->destinationName = '';

        $this->notes = '';

        $this->isReassignment = false;
    }


    /*
    |--------------------------------------------------------------------------
    | ASIGNAR / REASIGNAR
    |--------------------------------------------------------------------------
    */

    public function assign(
        AssignTransferService $service
    ): void {
        abort_unless(
            auth()->user()?->can('transfers.assign'),
            403
        );

        $this->resetValidation();

        $validated =
            $this->validate();


        /*
        |--------------------------------------------------------------------------
        | OBTENER UNIDAD
        |--------------------------------------------------------------------------
        */

        $unit = Unit::query()
            ->findOrFail(
                $this->unitId
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN AMIGABLE DE ESTADO
        |--------------------------------------------------------------------------
        |
        | El servicio también lo valida dentro de
        | una transacción. Esto es únicamente para
        | dar una respuesta inmediata en la UI.
        |
        */

        if (
            $unit->status
            !== UnitStatus::DELIVERY_PENDING
        ) {
            $this->addError(
                'unit',
                'La unidad ya no se encuentra pendiente de entrega.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR QUE SEA REALMENTE TRASLADISTA
        |--------------------------------------------------------------------------
        */

        $transporter =
            User::query()
                ->role('TRASLADISTA')
                ->where(
                    'active',
                    true
                )
                ->whereKey(
                    $validated[
                        'transporterId'
                    ]
                )
                ->first();

        if (!$transporter) {
            $this->addError(
                'transporterId',
                'El usuario seleccionado no es un trasladista activo.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ASIGNACIÓN ANTERIOR
        |--------------------------------------------------------------------------
        |
        | La utilizaremos para determinar si:
        |
        | - es asignación nueva;
        | - es reasignación;
        | - seleccionaron al mismo usuario.
        |
        */

        $previousAssignment =
            UnitTransferAssignment::query()
                ->where(
                    'unit_id',
                    $unit->id
                )
                ->where(
                    'status',
                    TransferAssignmentStatus::ASSIGNED->value
                )
                ->latest('id')
                ->first();

        $sameTransporter =
            $previousAssignment
            && $previousAssignment
                ->transporter_user_id
            === $transporter->id;


        /*
        |--------------------------------------------------------------------------
        | EJECUTAR SERVICIO
        |--------------------------------------------------------------------------
        */

        try {
            $assignment =
                $service->execute(
                    unit: $unit,
                    transporter: $transporter,
                    assignedBy: auth()->user(),

                    notes: filled(
                        $validated['notes'] ?? null
                    )
                    ? trim($validated['notes'])
                    : null,

                    originName:
                    $validated['originName'],

                    destinationName:
                    $validated['destinationName'],
                );
        } catch (ValidationException $exception) {

            /*
             * El servicio utiliza algunas claves
             * internas como "transporter" y "unit".
             * Las trasladamos a nuestro formulario.
             */

            foreach (
                $exception->errors()
                as $field => $messages
            ) {
                $targetField =
                    $field === 'transporter'
                    ? 'transporterId'
                    : $field;

                foreach ($messages as $message) {
                    $this->addError(
                        $targetField,
                        $message
                    );
                }
            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | MENSAJE
        |--------------------------------------------------------------------------
        */

        if ($sameTransporter) {
            $this->successMessage =
                'La unidad ya estaba asignada a '
                . $assignment->transporter_name
                . '.';
        } elseif ($previousAssignment) {
            $this->successMessage =
                'La unidad fue reasignada correctamente a '
                . $assignment->transporter_name
                . '.';
        } else {
            $this->successMessage =
                'La unidad fue asignada correctamente a '
                . $assignment->transporter_name
                . '.';
        }


        /*
        |--------------------------------------------------------------------------
        | CERRAR MODAL
        |--------------------------------------------------------------------------
        */

        $this->showModal = false;

        $this->transporterId = null;

        $this->notes = '';

        $this->isReassignment = false;

        $this->resetValidation();


        /*
         * Lo dejamos disponible por si posteriormente
         * otro componente del expediente necesita
         * reaccionar a la asignación.
         */
        $this->dispatch(
            'transfer-assigned',
            unitId: $unit->id,
            assignmentId: $assignment->id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $unit = Unit::query()
            ->with('brand')
            ->findOrFail(
                $this->unitId
            );


        /*
         * Asignación actualmente vigente.
         */
        $activeAssignment =
            $unit
                ->activeTransferAssignment()
                ->with([
                    'transporter',
                    'assignedBy',
                ])
                ->first();


        /*
         * Sólo usuarios activos y con rol
         * TRASLADISTA.
         */
        $transporters =
            User::query()
                ->role('TRASLADISTA')
                ->where(
                    'active',
                    true
                )
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'email',
                ]);


        return view(
            'livewire.units.transfer-assignment-modal',
            [
                'unit' =>
                    $unit,

                'activeAssignment' =>
                    $activeAssignment,

                'transporters' =>
                    $transporters,
            ]
        );
    }
}