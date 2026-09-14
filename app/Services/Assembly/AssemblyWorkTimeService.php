<?php

namespace App\Services\Assembly;

use App\Enums\AssemblyPauseReason;
use App\Enums\AssemblyWorkStatus;
use App\Enums\MilestoneStage;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\AssemblyWorkPause;
use App\Models\AssemblyWorkSession;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AssemblyWorkTimeService
{
    /*
    |--------------------------------------------------------------------------
    | INICIAR
    |--------------------------------------------------------------------------
    */

    public function start(
        Unit $unit,
        int $userId
    ): AssemblyWorkSession {

        return DB::transaction(
            function () use ($unit, $userId) {

                $unit = Unit::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $unit->id
                    );


                if (
                    $unit->status
                    !== UnitStatus::ASSEMBLY_PENDING
                ) {
                    throw new RuntimeException(
                        'La unidad no está pendiente de armado.'
                    );
                }


                $actor = User::findOrFail(
                    $userId
                );


                /*
                 * Un armador sólo puede tener
                 * una unidad RUNNING.
                 */
                $activeSession =
                    AssemblyWorkSession::query()
                        ->where(
                            'current_worker_id',
                            $userId
                        )
                        ->where(
                            'status',
                            AssemblyWorkStatus::RUNNING
                                ->value
                        )
                        ->lockForUpdate()
                        ->first();


                if ($activeSession) {

                    throw new RuntimeException(
                        'Ya tienes otra unidad con armado en proceso. '
                        . 'Debes pausarla o finalizarla antes de iniciar otra.'
                    );
                }


                $milestone =
                    $unit
                        ->milestones()
                        ->where(
                            'stage',
                            MilestoneStage::ASSEMBLY_COMPLETED
                                ->value
                        )
                        ->lockForUpdate()
                        ->first();


                if (!$milestone) {
                    throw new RuntimeException(
                        'No existe la etapa de armado para esta unidad.'
                    );
                }


                $existing =
                    AssemblyWorkSession::query()
                        ->where(
                            'unit_milestone_id',
                            $milestone->id
                        )
                        ->first();


                if ($existing) {
                    throw new RuntimeException(
                        'El control de tiempo de esta unidad ya fue iniciado.'
                    );
                }


                $now = now();


                $session =
                    AssemblyWorkSession::create([
                        'unit_id' =>
                            $unit->id,

                        'unit_milestone_id' =>
                            $milestone->id,

                        'status' =>
                            AssemblyWorkStatus::RUNNING,

                        'started_at' =>
                            $now,

                        'started_by' =>
                            $userId,

                        'started_by_name' =>
                            $actor->name,

                        'current_worker_id' =>
                            $userId,

                        'current_worker_name' =>
                            $actor->name,
                    ]);


                UnitEvent::create([
                    'unit_id' =>
                        $unit->id,

                    'event_type' =>
                        UnitEventType::ASSEMBLY_STARTED,

                    'title' =>
                        'Armado iniciado',

                    'description' =>
                        'Se inició el control de tiempo del armado.',

                    'reference_type' =>
                        AssemblyWorkSession::class,

                    'reference_id' =>
                        $session->id,

                    'performed_by' =>
                        $userId,

                    'performed_by_name' =>
                        $actor->name,
                ]);


                return $session->refresh();
            }
        );
    }



    /*
    |--------------------------------------------------------------------------
    | PAUSAR
    |--------------------------------------------------------------------------
    */

    public function pause(
        Unit $unit,
        AssemblyPauseReason $reason,
        ?string $notes,
        int $userId
    ): AssemblyWorkSession {

        return DB::transaction(
            function () use ($unit, $reason, $notes, $userId) {

                $actor = User::findOrFail(
                    $userId
                );


                $session =
                    AssemblyWorkSession::query()
                        ->where(
                            'unit_id',
                            $unit->id
                        )
                        ->lockForUpdate()
                        ->first();


                if (!$session) {
                    throw new RuntimeException(
                        'El armado todavía no ha sido iniciado.'
                    );
                }


                if (
                    $session->status
                    !== AssemblyWorkStatus::RUNNING
                ) {
                    throw new RuntimeException(
                        'El armado no se encuentra activo.'
                    );
                }


                if (
                    $session->current_worker_id
                    !== $userId
                ) {
                    throw new RuntimeException(
                        'Esta unidad está siendo trabajada por otro armador.'
                    );
                }


                $now = now();


                AssemblyWorkPause::create([
                    'assembly_work_session_id' =>
                        $session->id,

                    'reason' =>
                        $reason,

                    'notes' =>
                        $notes
                        ? trim($notes)
                        : null,

                    'paused_at' =>
                        $now,

                    'paused_by' =>
                        $userId,

                    'paused_by_name' =>
                        $actor->name,
                ]);


                $session->update([
                    'status' =>
                        AssemblyWorkStatus::PAUSED,

                    /*
                     * Actualmente nadie tiene
                     * la unidad RUNNING.
                     */
                    'current_worker_id' =>
                        null,

                    'current_worker_name' =>
                        null,
                ]);


                UnitEvent::create([
                    'unit_id' =>
                        $unit->id,

                    'event_type' =>
                        UnitEventType::ASSEMBLY_PAUSED,

                    'title' =>
                        'Armado pausado',

                    'description' =>
                        'El armado fue pausado por: '
                        . $reason->label()
                        . '.',

                    'reference_type' =>
                        AssemblyWorkSession::class,

                    'reference_id' =>
                        $session->id,

                    'performed_by' =>
                        $userId,

                    'performed_by_name' =>
                        $actor->name,

                    'metadata' => [
                        'reason' =>
                            $reason->value,

                        'notes' =>
                            $notes,
                    ],
                ]);


                return $session->refresh();
            }
        );
    }



    /*
    |--------------------------------------------------------------------------
    | REANUDAR
    |--------------------------------------------------------------------------
    */

    public function resume(
        Unit $unit,
        int $userId
    ): AssemblyWorkSession {

        return DB::transaction(
            function () use ($unit, $userId) {

                $actor = User::findOrFail(
                    $userId
                );


                /*
                 * Verificar que el armador
                 * no tenga otra unidad corriendo.
                 */
                $otherSession =
                    AssemblyWorkSession::query()
                        ->where(
                            'current_worker_id',
                            $userId
                        )
                        ->where(
                            'status',
                            AssemblyWorkStatus::RUNNING
                                ->value
                        )
                        ->where(
                            'unit_id',
                            '!=',
                            $unit->id
                        )
                        ->lockForUpdate()
                        ->first();


                if ($otherSession) {
                    throw new RuntimeException(
                        'Ya tienes otra unidad con armado en proceso.'
                    );
                }


                $session =
                    AssemblyWorkSession::query()
                        ->where(
                            'unit_id',
                            $unit->id
                        )
                        ->lockForUpdate()
                        ->first();


                if (!$session) {
                    throw new RuntimeException(
                        'El armado todavía no ha sido iniciado.'
                    );
                }


                if (
                    $session->status
                    !== AssemblyWorkStatus::PAUSED
                ) {
                    throw new RuntimeException(
                        'El armado no se encuentra pausado.'
                    );
                }


                /*
                 * Debe existir exactamente una
                 * pausa abierta.
                 */
                $pause =
                    AssemblyWorkPause::query()
                        ->where(
                            'assembly_work_session_id',
                            $session->id
                        )
                        ->whereNull(
                            'resumed_at'
                        )
                        ->latest('paused_at')
                        ->lockForUpdate()
                        ->first();


                if (!$pause) {
                    throw new RuntimeException(
                        'No se encontró la pausa activa.'
                    );
                }


                $now = now();


                $duration =
                    max(
                        0,
                        $pause
                            ->paused_at
                            ->diffInSeconds(
                                $now
                            )
                    );


                $pause->update([
                    'resumed_at' =>
                        $now,

                    'duration_seconds' =>
                        $duration,

                    'resumed_by' =>
                        $userId,

                    'resumed_by_name' =>
                        $actor->name,
                ]);


                $session->update([
                    'status' =>
                        AssemblyWorkStatus::RUNNING,

                    'current_worker_id' =>
                        $userId,

                    'current_worker_name' =>
                        $actor->name,

                    'total_paused_seconds' =>
                        $session
                            ->total_paused_seconds
                        + $duration,
                ]);


                UnitEvent::create([
                    'unit_id' =>
                        $unit->id,

                    'event_type' =>
                        UnitEventType::ASSEMBLY_RESUMED,

                    'title' =>
                        'Armado reanudado',

                    'description' =>
                        'El proceso de armado fue reanudado.',

                    'reference_type' =>
                        AssemblyWorkSession::class,

                    'reference_id' =>
                        $session->id,

                    'performed_by' =>
                        $userId,

                    'performed_by_name' =>
                        $actor->name,

                    'metadata' => [
                        'pause_reason' =>
                            $pause->reason->value,

                        'pause_seconds' =>
                            $duration,
                    ],
                ]);


                return $session->refresh();
            }
        );
    }
}