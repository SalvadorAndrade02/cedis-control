<?php

namespace App\Services\Milestones;

use App\Enums\AssemblyWorkStatus;
use App\Enums\EvidenceType;
use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\AssemblyWorkSession;
use App\Models\Evidence;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\UnitMilestone;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CompleteAssemblyService
{
    /**
     * @param array<UploadedFile> $photos
     */
    public function execute(
        Unit $unit,
        array $photos,
        ?string $observations,
        int $userId,
    ): UnitMilestone {

        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES PREVIAS
        |--------------------------------------------------------------------------
        */

        if (
            $unit->status
            !== UnitStatus::ASSEMBLY_PENDING
        ) {
            throw new RuntimeException(
                'La unidad no está pendiente de armado.'
            );
        }


        if ($photos === []) {
            throw new RuntimeException(
                'Debes registrar al menos una evidencia del armado finalizado.'
            );
        }


        $storedPaths = [];


        try {

            return DB::transaction(
                function () use ($unit, $photos, $observations, $userId, &$storedPaths) {

                    /*
                    |--------------------------------------------------------------------------
                    | BLOQUEAR UNIDAD
                    |--------------------------------------------------------------------------
                    |
                    | Evitamos dos cierres simultáneos.
                    |
                    */

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
                            'La unidad ya no está pendiente de armado.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | MILESTONE
                    |--------------------------------------------------------------------------
                    */

                    $milestone = $unit
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


                    if (
                        $milestone->status
                        === MilestoneStatus::COMPLETED
                    ) {
                        throw new RuntimeException(
                            'El armado ya fue documentado.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SESIÓN DE TRABAJO
                    |--------------------------------------------------------------------------
                    |
                    | A partir de ahora ningún armado puede
                    | cerrarse sin haber iniciado el contador.
                    |
                    */

                    $workSession =
                        AssemblyWorkSession::query()
                            ->where(
                                'unit_milestone_id',
                                $milestone->id
                            )
                            ->lockForUpdate()
                            ->first();


                    if (!$workSession) {
                        throw new RuntimeException(
                            'Debes iniciar el armado antes de poder finalizarlo.'
                        );
                    }


                    /*
                     * No permitimos finalizar mientras
                     * el cronómetro esté pausado.
                     */
                    if (
                        $workSession->status
                        === AssemblyWorkStatus::PAUSED
                    ) {
                        throw new RuntimeException(
                            'El armado está pausado. Debes reanudarlo antes de finalizar.'
                        );
                    }


                    if (
                        $workSession->status
                        === AssemblyWorkStatus::COMPLETED
                    ) {
                        throw new RuntimeException(
                            'El control de tiempo del armado ya fue finalizado.'
                        );
                    }


                    if (
                        $workSession->status
                        !== AssemblyWorkStatus::RUNNING
                    ) {
                        throw new RuntimeException(
                            'El control de tiempo del armado no se encuentra activo.'
                        );
                    }


                    /*
                     * Sólo quien tiene actualmente
                     * la unidad RUNNING puede cerrarla.
                     */
                    if (
                        $workSession->current_worker_id
                        !== $userId
                    ) {
                        throw new RuntimeException(
                            'Esta unidad está siendo trabajada por otro armador.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | RESPONSABLE
                    |--------------------------------------------------------------------------
                    */

                    $actor = User::findOrFail(
                        $userId
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | FECHA ÚNICA DE FINALIZACIÓN
                    |--------------------------------------------------------------------------
                    |
                    | Usamos el mismo instante para:
                    |
                    | - session.completed_at
                    | - milestone.completed_at
                    | - occurred_at
                    |
                    */

                    $completedAt = now();


                    /*
                    |--------------------------------------------------------------------------
                    | CALCULAR TIEMPO
                    |--------------------------------------------------------------------------
                    |
                    | Ejemplo:
                    |
                    | Inicio        08:00
                    | Fin           16:00
                    | Calendario     8h
                    | Pausas       1h45
                    |
                    | Efectivo     6h15
                    |
                    */

                    $elapsedSeconds = max(
                        0,
                        $workSession
                            ->started_at
                            ->diffInSeconds(
                                $completedAt
                            )
                    );


                    $pausedSeconds = max(
                        0,
                        (int) $workSession
                            ->total_paused_seconds
                    );


                    $activeSeconds = max(
                        0,
                        $elapsedSeconds
                        - $pausedSeconds
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | GUARDAR EVIDENCIAS
                    |--------------------------------------------------------------------------
                    */

                    foreach ($photos as $photo) {

                        $extension = strtolower(
                            $photo
                                ->getClientOriginalExtension()
                            ?: 'jpg'
                        );


                        $filename =
                            uniqid(
                                'assembly_',
                                true
                            )
                            . '.'
                            . $extension;


                        $path =
                            'cedis/evidences/'
                            . $unit->vin
                            . '/assembly/'
                            . $filename;


                        $contents =
                            file_get_contents(
                                $photo->getRealPath()
                            );


                        if ($contents === false) {
                            throw new RuntimeException(
                                'No fue posible leer una de las evidencias.'
                            );
                        }


                        $stored =
                            Storage::disk('local')
                                ->put(
                                    $path,
                                    $contents
                                );


                        if (!$stored) {
                            throw new RuntimeException(
                                'No fue posible almacenar una de las evidencias.'
                            );
                        }


                        $storedPaths[] =
                            $path;


                        Evidence::create([

                            'unit_milestone_id' =>
                                $milestone->id,

                            'evidence_requirement_id' =>
                                null,

                            'type' =>
                                EvidenceType::IMAGE,

                            'storage_disk' =>
                                'local',

                            'storage_path' =>
                                $path,

                            'original_filename' =>
                                $photo
                                    ->getClientOriginalName(),

                            'mime_type' =>
                                $photo
                                    ->getMimeType(),

                            'file_size' =>
                                $photo
                                    ->getSize(),

                            'file_hash' =>
                                hash_file(
                                    'sha256',
                                    $photo->getRealPath()
                                ),

                            'captured_at' =>
                                $completedAt,

                            'uploaded_by' =>
                                $userId,
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FINALIZAR CRONÓMETRO
                    |--------------------------------------------------------------------------
                    */

                    $workSession->update([

                        'status' =>
                            AssemblyWorkStatus::COMPLETED,

                        'completed_at' =>
                            $completedAt,

                        'completed_by' =>
                            $userId,

                        'completed_by_name' =>
                            $actor->name,

                        /*
                         * Ya no hay trabajador activo
                         * sobre la unidad.
                         */
                        'current_worker_id' =>
                            null,

                        'current_worker_name' =>
                            null,

                        'total_active_seconds' =>
                            $activeSeconds,

                        /*
                         * Este ya contiene todas
                         * las pausas cerradas.
                         */
                        'total_paused_seconds' =>
                            $pausedSeconds,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | COMPLETAR MILESTONE
                    |--------------------------------------------------------------------------
                    */

                    $milestone->update([

                        'status' =>
                            MilestoneStatus::COMPLETED,

                        'occurred_at' =>
                            $completedAt,

                        'completed_at' =>
                            $completedAt,

                        'completed_by' =>
                            $userId,

                        'completed_by_name' =>
                            $actor->name,

                        'observations' =>
                            $observations,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | SIGUIENTE ETAPA
                    |--------------------------------------------------------------------------
                    */

                    $unit->update([

                        'status' =>
                            UnitStatus::DELIVERY_PENDING,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | HISTORIAL
                    |--------------------------------------------------------------------------
                    */

                    UnitEvent::create([

                        'unit_id' =>
                            $unit->id,

                        'event_type' =>
                            UnitEventType::ASSEMBLY_COMPLETED,

                        'title' =>
                            'Armado finalizado',

                        'description' =>
                            'Se registró la evidencia y se finalizó el control de tiempo del armado.',

                        'reference_type' =>
                            UnitMilestone::class,

                        'reference_id' =>
                            $milestone->id,

                        'performed_by' =>
                            $userId,

                        'performed_by_name' =>
                            $actor->name,

                        'metadata' => [

                            'evidence_count' =>
                                count($photos),

                            'assembly_started_at' =>
                                $workSession
                                    ->started_at
                                    ->toIso8601String(),

                            'assembly_completed_at' =>
                                $completedAt
                                    ->toIso8601String(),

                            'active_seconds' =>
                                $activeSeconds,

                            'paused_seconds' =>
                                $pausedSeconds,

                            'elapsed_seconds' =>
                                $elapsedSeconds,
                        ],
                    ]);


                    return $milestone
                        ->refresh();
                }
            );

        } catch (Throwable $exception) {

            /*
             * MySQL hace rollback.
             *
             * Como Storage no participa en la
             * transacción, eliminamos manualmente
             * cualquier archivo guardado.
             */
            foreach ($storedPaths as $path) {

                Storage::disk('local')
                    ->delete($path);
            }


            throw $exception;
        }
    }
}