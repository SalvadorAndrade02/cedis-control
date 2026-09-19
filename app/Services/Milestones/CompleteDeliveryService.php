<?php

namespace App\Services\Milestones;

use App\Enums\EvidenceType;
use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\TransferAssignmentStatus;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\Carrier;
use App\Models\CarrierDelivery;
use App\Models\Evidence;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\UnitMilestone;
use App\Models\UnitTransferAssignment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CompleteDeliveryService
{
    /**
     * @param array<UploadedFile> $photos
     */
    public function execute(
        Unit $unit,
        string $carrierName,
        string $operatorName,
        ?string $operatorIdentification,
        ?string $operatorPhone,
        string $vehiclePlate,
        ?string $vehicleNumber,
        ?string $transportType,
        array $photos,
        ?string $observations,
        int $userId,
    ): UnitMilestone {

        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES RÁPIDAS
        |--------------------------------------------------------------------------
        |
        | Estas dan una respuesta inmediata antes de abrir la transacción.
        | De todas formas volveremos a validar dentro de ella.
        |
        */

        if (
            $unit->status
            !== UnitStatus::DELIVERY_PENDING
        ) {
            throw new RuntimeException(
                'La unidad no está pendiente de entrega.'
            );
        }

        if ($photos === []) {
            throw new RuntimeException(
                'Debes registrar al menos una evidencia de entrega.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ARCHIVOS GUARDADOS
        |--------------------------------------------------------------------------
        |
        | Si MySQL hace rollback, necesitamos eliminar manualmente
        | las fotografías que alcanzaron a guardarse en Storage.
        |
        */

        $storedPaths = [];


        try {

            return DB::transaction(
                function () use ($unit, $carrierName, $operatorName, $operatorIdentification, $operatorPhone, $vehiclePlate, $vehicleNumber, $transportType, $photos, $observations, $userId, &$storedPaths) {

                    /*
                    |--------------------------------------------------------------------------
                    | FECHA ÚNICA DE FINALIZACIÓN
                    |--------------------------------------------------------------------------
                    |
                    | CarrierDelivery, milestone y traslado usarán exactamente
                    | el mismo instante.
                    |
                    */

                    $completedAt = now();


                    /*
                    |--------------------------------------------------------------------------
                    | BLOQUEAR Y VOLVER A VALIDAR LA UNIDAD
                    |--------------------------------------------------------------------------
                    */

                    $lockedUnit = Unit::query()
                        ->whereKey(
                            $unit->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                    if (
                        $lockedUnit->status
                        !== UnitStatus::DELIVERY_PENDING
                    ) {
                        throw new RuntimeException(
                            'La unidad ya no está pendiente de entrega.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BLOQUEAR Y VALIDAR MILESTONE DE ENTREGA
                    |--------------------------------------------------------------------------
                    */

                    $milestone = UnitMilestone::query()
                        ->where(
                            'unit_id',
                            $lockedUnit->id
                        )
                        ->where(
                            'stage',
                            MilestoneStage::CARRIER_DELIVERY->value
                        )
                        ->lockForUpdate()
                        ->first();


                    if (!$milestone) {
                        throw new RuntimeException(
                            'No existe la etapa de entrega para esta unidad.'
                        );
                    }


                    if (
                        $milestone->status
                        === MilestoneStatus::COMPLETED
                    ) {
                        throw new RuntimeException(
                            'La entrega ya fue documentada.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | USUARIO QUE COMPLETA LA ENTREGA
                    |--------------------------------------------------------------------------
                    */

                    $actor = User::query()
                        ->findOrFail(
                            $userId
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDAR ASIGNACIÓN ACTIVA DE TRASLADO
                    |--------------------------------------------------------------------------
                    |
                    | Actualmente:
                    |
                    | 0 ASSIGNED:
                    |     Permitimos completar la entrega.
                    |
                    | 1 ASSIGNED:
                    |     Se convertirá automáticamente a COMPLETED.
                    |
                    | 2+ ASSIGNED:
                    |     Es una inconsistencia de datos y bloqueamos el cierre.
                    |
                    */

                    $activeTransferAssignments =
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
                            ->get();


                    if (
                        $activeTransferAssignments->count() > 1
                    ) {
                        throw new RuntimeException(
                            'La unidad tiene más de una asignación de traslado activa. '
                            . 'Corrige las asignaciones antes de completar la entrega.'
                        );
                    }


                    $activeTransferAssignment =
                        $activeTransferAssignments
                            ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORTADORA
                    |--------------------------------------------------------------------------
                    |
                    | Por ahora se sigue permitiendo la captura libre.
                    |
                    */

                    $carrier = Carrier::firstOrCreate(
                        [
                            'name' =>
                                trim(
                                    $carrierName
                                ),
                        ],
                        [
                            'active' =>
                                true,
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | EVIDENCIAS DE ENTREGA
                    |--------------------------------------------------------------------------
                    */

                    foreach ($photos as $photo) {

                        $extension = strtolower(
                            $photo->getClientOriginalExtension()
                            ?: 'jpg'
                        );


                        $filename =
                            uniqid(
                                'delivery_',
                                true
                            )
                            . '.'
                            . $extension;


                        $path =
                            'cedis/evidences/'
                            . $lockedUnit->vin
                            . '/delivery/'
                            . $filename;


                        $contents =
                            file_get_contents(
                                $photo->getRealPath()
                            );


                        if ($contents === false) {
                            throw new RuntimeException(
                                'No fue posible leer una evidencia de entrega.'
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
                                'No fue posible almacenar una evidencia.'
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
                                $photo->getMimeType(),

                            'file_size' =>
                                $photo->getSize(),

                            'file_hash' =>
                                hash_file(
                                    'sha256',
                                    $photo->getRealPath()
                                ),

                            /*
                             * Usamos la misma fecha del cierre.
                             */
                            'captured_at' =>
                                $completedAt,

                            'uploaded_by' =>
                                $userId,
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ENTREGA A TRANSPORTADORA
                    |--------------------------------------------------------------------------
                    */

                    CarrierDelivery::create([
                        'unit_milestone_id' =>
                            $milestone->id,

                        'carrier_id' =>
                            $carrier->id,

                        'operator_name' =>
                            trim(
                                $operatorName
                            ),

                        'operator_identification' =>
                            $operatorIdentification,

                        'operator_phone' =>
                            $operatorPhone,

                        'vehicle_plate' =>
                            strtoupper(
                                trim(
                                    $vehiclePlate
                                )
                            ),

                        'vehicle_number' =>
                            $vehicleNumber,

                        'transport_type' =>
                            $transportType,

                        'delivered_at' =>
                            $completedAt,

                        'observations' =>
                            $observations,
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
                    | COMPLETAR ASIGNACIÓN DE TRASLADO
                    |--------------------------------------------------------------------------
                    |
                    | No modificamos:
                    |
                    | transporter_user_id
                    | transporter_name
                    | assigned_by
                    | assigned_by_name
                    | assigned_at
                    | notes
                    |
                    | porque forman parte del histórico.
                    |
                    */

                    if ($activeTransferAssignment) {

                        $activeTransferAssignment
                            ->update([
                                'status' =>
                                    TransferAssignmentStatus::COMPLETED,

                                'completed_at' =>
                                    $completedAt,

                                'cancelled_at' =>
                                    null,
                            ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | COMPLETAR UNIDAD
                    |--------------------------------------------------------------------------
                    |
                    | Aquí termina el proceso CEDIS.
                    |
                    */

                    $lockedUnit->update([
                        'status' =>
                            UnitStatus::COMPLETED,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | EVENTO DE AUDITORÍA
                    |--------------------------------------------------------------------------
                    |
                    | Conservamos el evento actual de entrega y añadimos al
                    | metadata la información del traslado, cuando exista.
                    |
                    */

                    UnitEvent::create([
                        'unit_id' =>
                            $lockedUnit->id,

                        'event_type' =>
                            UnitEventType::DELIVERY_COMPLETED,

                        'title' =>
                            'Entrega a transportadora',

                        'description' =>
                            'La unidad fue entregada a la transportadora y el expediente quedó completo.',

                        'reference_type' =>
                            UnitMilestone::class,

                        'reference_id' =>
                            $milestone->id,

                        'performed_by' =>
                            $userId,

                        'performed_by_name' =>
                            $actor->name,

                        'metadata' => [
                            'carrier' =>
                                $carrier->name,

                            'operator' =>
                                trim(
                                    $operatorName
                                ),

                            'vehicle_plate' =>
                                strtoupper(
                                    trim(
                                        $vehiclePlate
                                    )
                                ),

                            'evidence_count' =>
                                count(
                                    $photos
                                ),

                            /*
                             * Información histórica del traslado.
                             */
                            'transfer_assignment_id' =>
                                $activeTransferAssignment?->id,

                            'transporter_user_id' =>
                                $activeTransferAssignment
                                        ?->transporter_user_id,

                            'transporter_name' =>
                                $activeTransferAssignment
                                        ?->transporter_name,
                        ],
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | RESULTADO
                    |--------------------------------------------------------------------------
                    */

                    return $milestone
                        ->refresh();
                }
            );

        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | LIMPIEZA DE ARCHIVOS
            |--------------------------------------------------------------------------
            |
            | MySQL revierte automáticamente la transacción.
            | Storage no, por eso eliminamos manualmente cualquier archivo
            | que se hubiera alcanzado a guardar.
            |
            */

            foreach (
                $storedPaths as $path
            ) {
                Storage::disk('local')
                    ->delete(
                        $path
                    );
            }


            throw $exception;
        }
    }
}