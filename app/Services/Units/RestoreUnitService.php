<?php

namespace App\Services\Units;

use App\Enums\UnitEventType;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RestoreUnitService
{
    public function execute(
        Unit $unit,
        int $userId
    ): void {

        $actor = User::findOrFail(
            $userId
        );

        DB::transaction(
            function () use ($unit, $userId, $actor) {

                /*
                 * Primero restauramos.
                 */
                $unit->restore();


                UnitEvent::create([
                    'unit_id' =>
                        $unit->id,

                    'event_type' =>
                        UnitEventType::UNIT_RESTORED,

                    'title' =>
                        'Unidad restaurada',

                    'description' =>
                        'La unidad fue restaurada al flujo del sistema.',

                    'performed_by' =>
                        $userId,

                    'performed_by_name' =>
                        $actor->name,
                ]);


                /*
                 * Conservamos el motivo histórico
                 * en el UnitEvent.
                 *
                 * Limpiamos el estado actual
                 * de eliminación.
                 */
                $unit->update([
                    'deleted_by' =>
                        null,

                    'deletion_reason' =>
                        null,
                ]);
            }
        );
    }
}