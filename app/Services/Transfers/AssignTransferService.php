<?php

namespace App\Services\Transfers;

use App\Enums\TransferAssignmentStatus;
use App\Enums\UnitStatus;
use App\Models\Unit;
use App\Models\UnitTransferAssignment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignTransferService
{
    public function execute(
        Unit $unit,
        User $transporter,
        User $assignedBy,
        ?string $notes = null,
        ?string $originName = 'CEDIS',
        ?string $destinationName = null,
    ): UnitTransferAssignment {
        /*
        |--------------------------------------------------------------------------
        | AUTORIZACIÓN
        |--------------------------------------------------------------------------
        */

        if (!$assignedBy->can('transfers.assign')) {
            throw new AuthorizationException(
                'No tienes permiso para asignar unidades a trasladistas.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR TRASLADISTA
        |--------------------------------------------------------------------------
        */

        if (!$transporter->active) {
            throw ValidationException::withMessages([
                'transporter' =>
                    'El trasladista seleccionado se encuentra inactivo.',
            ]);
        }

        if (!$transporter->hasRole('TRASLADISTA')) {
            throw ValidationException::withMessages([
                'transporter' =>
                    'El usuario seleccionado no tiene el rol TRASLADISTA.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN
        |--------------------------------------------------------------------------
        |
        | Además bloquearemos temporalmente la fila de la unidad.
        |
        | Esto evita que dos administradores puedan asignar la misma unidad
        | prácticamente al mismo tiempo.
        |
        */

        return DB::transaction(
            function () use ($unit, $transporter, $assignedBy, $notes, $originName, $destinationName) {

                /*
                 * Volvemos a consultar la unidad dentro de la
                 * transacción y bloqueamos su fila.
                 */
                $lockedUnit = Unit::query()
                    ->whereKey($unit->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | VALIDAR ESTADO DE LA UNIDAD
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedUnit->status
                    !== UnitStatus::DELIVERY_PENDING
                ) {
                    throw ValidationException::withMessages([
                        'unit' =>
                            'La unidad debe encontrarse pendiente de entrega para poder asignar un trasladista.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | BUSCAR ASIGNACIÓN ACTUAL
                |--------------------------------------------------------------------------
                */

                $currentAssignment =
                    UnitTransferAssignment::query()
                        ->where(
                            'unit_id',
                            $lockedUnit->id
                        )
                        ->where(
                            'status',
                            TransferAssignmentStatus::ASSIGNED->value
                        )
                        ->lockForUpdate()
                        ->latest('id')
                        ->first();


                /*
                |--------------------------------------------------------------------------
                | YA ESTÁ ASIGNADA AL MISMO TRASLADISTA
                |--------------------------------------------------------------------------
                |
                | No creamos otro registro innecesariamente.
                |
                */

                if (
                    $currentAssignment
                    && $currentAssignment->transporter_user_id
                    === $transporter->id
                ) {
                    return $currentAssignment;
                }

                /*
|--------------------------------------------------------------------------
| CONSERVAR DATOS DEL TRASLADO EN UNA REASIGNACIÓN
|--------------------------------------------------------------------------
|
| Cambiar al trasladista no significa cambiar el viaje.
|
| Por eso conservamos:
|
| - origen
| - destino
| - observaciones
|
| de la asignación actualmente activa.
|
*/

                if ($currentAssignment) {

                    $originName =
                        $currentAssignment->origin_name
                        ?: 'CEDIS';

                    $destinationName =
                        $currentAssignment->destination_name;

                    $notes =
                        $currentAssignment->notes;
                }


                /*
                |--------------------------------------------------------------------------
                | CANCELAR ASIGNACIÓN ANTERIOR
                |--------------------------------------------------------------------------
                */

                if ($currentAssignment) {
                    $currentAssignment->update([
                        'status' =>
                            TransferAssignmentStatus::CANCELLED,

                        'cancelled_at' =>
                            now(),
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | CREAR NUEVA ASIGNACIÓN
                |--------------------------------------------------------------------------
                */

                return UnitTransferAssignment::create([
                    'unit_id' =>
                        $lockedUnit->id,

                    'transporter_user_id' =>
                        $transporter->id,

                    'transporter_name' =>
                        $transporter->name,

                    'assigned_by' =>
                        $assignedBy->id,

                    'assigned_by_name' =>
                        $assignedBy->name,

                    'status' =>
                        TransferAssignmentStatus::ASSIGNED,

                    'origin_name' =>
                        filled($originName)
                        ? trim($originName)
                        : 'CEDIS',

                    'destination_name' =>
                        filled($destinationName)
                        ? trim($destinationName)
                        : null,

                    'assigned_at' =>
                        now(),

                    'completed_at' =>
                        null,

                    'cancelled_at' =>
                        null,

                    'notes' =>
                        filled($notes)
                        ? trim($notes)
                        : null,
                ]);
            }
        );
    }
}