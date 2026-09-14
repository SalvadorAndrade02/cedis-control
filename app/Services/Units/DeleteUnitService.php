<?php

namespace App\Services\Units;

use App\Enums\UnitEventType;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUnitService
{
    public function execute(
        Unit $unit,
        ?string $reason,
        int $userId
    ): void {

        /*
         * Normalizamos el motivo.
         *
         * Si llega vacío, con espacios
         * o null, almacenamos null.
         */
        $reason = filled($reason)
            ? trim($reason)
            : null;


        $actor = User::findOrFail(
            $userId
        );


        DB::transaction(
            function () use ($unit, $reason, $userId, $actor) {

                /*
                 * Generamos una descripción
                 * adecuada dependiendo de si
                 * existe motivo o no.
                 */
                $description = $reason
                    ? 'La unidad fue retirada del flujo operativo. Motivo: '
                    . $reason
                    : 'La unidad fue retirada del flujo operativo.';


                /*
                 * Registramos el evento ANTES
                 * del soft delete.
                 */
                UnitEvent::create([

                    'unit_id' =>
                        $unit->id,

                    'event_type' =>
                        UnitEventType::UNIT_DELETED,

                    'title' =>
                        'Unidad eliminada',

                    'description' =>
                        $description,

                    'performed_by' =>
                        $userId,

                    'performed_by_name' =>
                        $actor->name,

                    'metadata' => [
                        'reason' =>
                            $reason,

                        'previous_status' =>
                            $unit->status->value,
                    ],
                ]);


                /*
                 * Snapshot de eliminación.
                 */
                $unit->update([

                    'deleted_by' =>
                        $userId,

                    'deletion_reason' =>
                        $reason,
                ]);


                /*
                 * SOFT DELETE.
                 *
                 * No elimina XML, PDF,
                 * milestones ni evidencias.
                 */
                $unit->delete();
            }
        );
    }
}