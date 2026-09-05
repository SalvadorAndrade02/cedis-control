<?php

namespace App\Services\Milestones;

use App\Enums\EvidenceType;
use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\Carrier;
use App\Models\CarrierDelivery;
use App\Models\Evidence;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\UnitMilestone;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;
use App\Models\User;

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

        $milestone = $unit
            ->milestones()
            ->where(
                'stage',
                MilestoneStage::CARRIER_DELIVERY->value
            )
            ->first();

        if (! $milestone) {
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

        $storedPaths = [];

        try {

            return DB::transaction(
                function () use (
                    $unit,
                    $carrierName,
                    $operatorName,
                    $operatorIdentification,
                    $operatorPhone,
                    $vehiclePlate,
                    $vehicleNumber,
                    $transportType,
                    $photos,
                    $observations,
                    $userId,
                    $milestone,
                    &$storedPaths
                ) {

                    /*
                     * Por ahora permitimos capturar
                     * transportadora libremente.
                     *
                     * Después Administración tendrá
                     * su catálogo formal.
                     */
                    $carrier = Carrier::firstOrCreate(
                        [
                            'name' => trim($carrierName),
                        ],
                        [
                            'active' => true,
                        ]
                    );

                    foreach ($photos as $photo) {

                        $extension = strtolower(
                            $photo->getClientOriginalExtension()
                                ?: 'jpg'
                        );

                        $filename =
                            uniqid('delivery_', true)
                            . '.'
                            . $extension;

                        $path =
                            'cedis/evidences/'
                            . $unit->vin
                            . '/delivery/'
                            . $filename;

                        $contents = file_get_contents(
                            $photo->getRealPath()
                        );

                        if ($contents === false) {
                            throw new RuntimeException(
                                'No fue posible leer una evidencia de entrega.'
                            );
                        }

                        $stored = Storage::disk('local')
                            ->put(
                                $path,
                                $contents
                            );

                        if (! $stored) {
                            throw new RuntimeException(
                                'No fue posible almacenar una evidencia.'
                            );
                        }

                        $storedPaths[] = $path;

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
                            $photo->getClientOriginalName(),

                            'mime_type' =>
                            $photo->getMimeType(),

                            'file_size' =>
                            $photo->getSize(),

                            'file_hash' =>
                            hash_file(
                                'sha256',
                                $photo->getRealPath()
                            ),

                            'captured_at' =>
                            now(),

                            'uploaded_by' =>
                            $userId,
                        ]);
                    }

                    CarrierDelivery::create([
                        'unit_milestone_id' =>
                        $milestone->id,

                        'carrier_id' =>
                        $carrier->id,

                        'operator_name' =>
                        trim($operatorName),

                        'operator_identification' =>
                        $operatorIdentification,

                        'operator_phone' =>
                        $operatorPhone,

                        'vehicle_plate' =>
                        strtoupper(
                            trim($vehiclePlate)
                        ),

                        'vehicle_number' =>
                        $vehicleNumber,

                        'transport_type' =>
                        $transportType,

                        'delivered_at' =>
                        now(),

                        'observations' =>
                        $observations,
                    ]);

                    $actor = User::findOrFail(
                        $userId
                    );

                    $milestone->update([
                        'status' =>
                        MilestoneStatus::COMPLETED,

                        'occurred_at' =>
                        now(),

                        'completed_at' =>
                        now(),

                        'completed_by' =>
                        $userId,

                        'completed_by_name' =>
                        $actor->name,

                        'observations' =>
                        $observations,
                    ]);

                    /*
                     * Aquí termina el proceso CEDIS.
                     */
                    $unit->update([
                        'status' =>
                        UnitStatus::COMPLETED,
                    ]);

                    UnitEvent::create([
                        'unit_id' =>
                        $unit->id,

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
                            trim($operatorName),

                            'vehicle_plate' =>
                            strtoupper(
                                trim($vehiclePlate)
                            ),

                            'evidence_count' =>
                            count($photos),
                        ],
                    ]);

                    return $milestone->refresh();
                }
            );
        } catch (Throwable $exception) {

            /*
             * Si MySQL hace rollback también
             * limpiamos los archivos.
             */
            foreach ($storedPaths as $path) {
                Storage::disk('local')
                    ->delete($path);
            }

            throw $exception;
        }
    }
}
