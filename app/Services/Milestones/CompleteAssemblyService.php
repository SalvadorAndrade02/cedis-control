<?php

namespace App\Services\Milestones;

use App\Enums\EvidenceType;
use App\Enums\MilestoneStage;
use App\Enums\MilestoneStatus;
use App\Enums\UnitEventType;
use App\Enums\UnitStatus;
use App\Models\Evidence;
use App\Models\Unit;
use App\Models\UnitEvent;
use App\Models\UnitMilestone;
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

        $milestone = $unit
            ->milestones()
            ->where(
                'stage',
                MilestoneStage::ASSEMBLY_COMPLETED->value
            )
            ->first();

        if (! $milestone) {
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

        $storedPaths = [];

        try {

            return DB::transaction(
                function () use (
                    $unit,
                    $photos,
                    $observations,
                    $userId,
                    $milestone,
                    &$storedPaths
                ) {

                    foreach ($photos as $photo) {

                        $extension = strtolower(
                            $photo->getClientOriginalExtension()
                                ?: 'jpg'
                        );

                        $filename =
                            uniqid('assembly_', true)
                            . '.'
                            . $extension;

                        $path =
                            'cedis/evidences/'
                            . $unit->vin
                            . '/assembly/'
                            . $filename;

                        $contents = file_get_contents(
                            $photo->getRealPath()
                        );

                        if ($contents === false) {
                            throw new RuntimeException(
                                'No fue posible leer una de las evidencias.'
                            );
                        }

                        $stored = Storage::disk('local')
                            ->put(
                                $path,
                                $contents
                            );

                        if (! $stored) {
                            throw new RuntimeException(
                                'No fue posible almacenar una de las evidencias.'
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

                    $milestone->update([
                        'status' =>
                        MilestoneStatus::COMPLETED,

                        'occurred_at' =>
                        now(),

                        'completed_at' =>
                        now(),

                        'completed_by' =>
                        $userId,

                        'observations' =>
                        $observations,
                    ]);

                    $unit->update([
                        'status' =>
                        UnitStatus::DELIVERY_PENDING,
                    ]);

                    UnitEvent::create([
                        'unit_id' =>
                        $unit->id,

                        'event_type' =>
                        UnitEventType::ASSEMBLY_COMPLETED,

                        'title' =>
                        'Armado finalizado',

                        'description' =>
                        'Se registró la evidencia del armado finalizado de la unidad.',

                        'reference_type' =>
                        UnitMilestone::class,

                        'reference_id' =>
                        $milestone->id,

                        'performed_by' =>
                        $userId,

                        'metadata' => [
                            'evidence_count' =>
                            count($photos),
                        ],
                    ]);

                    return $milestone->refresh();
                }
            );
        } catch (Throwable $exception) {

            foreach ($storedPaths as $path) {
                Storage::disk('local')
                    ->delete($path);
            }

            throw $exception;
        }
    }
}
