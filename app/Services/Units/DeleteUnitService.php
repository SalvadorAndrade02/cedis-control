<?php

namespace App\Services\Units;

use App\Models\Document;
use App\Models\DocumentUnit;
use App\Models\InvoiceData;
use App\Models\Unit;
use App\Models\UnitEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class DeleteUnitService
{
    /**
     * Elimina físicamente una unidad y todos los
     * registros/archivos que pertenezcan exclusivamente
     * a ella.
     *
     * IMPORTANTE:
     * Actualmente se limita a local/testing para evitar
     * borrado irreversible accidental en producción.
     */
    public function execute(
        Unit $unit,
        ?string $reason,
        int $userId
    ): void {

        /*
        |--------------------------------------------------------------------------
        | PROTECCIÓN
        |--------------------------------------------------------------------------
        |
        | Para tu etapa actual de desarrollo esto permite reutilizar
        | XML/PDF/VIN de prueba sin dejar disponible un purge accidental
        | en producción.
        |
        */

        if (
            !app()->environment([
                'local',
                'testing',
            ])
        ) {

            throw new RuntimeException(
                'La eliminación permanente de unidades sólo está habilitada en desarrollo.'
            );
        }


        $reason = filled($reason)
            ? trim($reason)
            : null;


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN DE BASE DE DATOS
        |--------------------------------------------------------------------------
        |
        | Los archivos NO se eliminan todavía.
        |
        | Primero hacemos commit de MySQL y solamente después eliminamos
        | los archivos físicos. Así evitamos el peor escenario:
        |
        | 1. borrar archivo
        | 2. falla SQL
        | 3. rollback DB
        | 4. registro vuelve pero archivo ya desapareció
        |
        */

        $filesToDelete = DB::transaction(
            function () use ($unit) {

                /*
                |--------------------------------------------------------------------------
                | 1. BLOQUEAR UNIDAD
                |--------------------------------------------------------------------------
                */

                $lockedUnit = Unit::withTrashed()
                    ->whereKey(
                        $unit->getKey()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | 2. CARGAR RELACIONES
                |--------------------------------------------------------------------------
                */

                $lockedUnit->load([

                    'milestones.evidences',

                    'milestones.assemblyWorkSession.pauses',

                    'milestones.carrierDelivery',
                ]);


                /*
                 * Usaremos una llave disk|path para evitar
                 * intentar eliminar dos veces el mismo archivo.
                 */
                $files = [];


                /*
                |--------------------------------------------------------------------------
                | 3. IDENTIFICAR ARCHIVOS DE EVIDENCIA
                |--------------------------------------------------------------------------
                */

                foreach (
                    $lockedUnit->milestones
                    as $milestone
                ) {

                    foreach (
                        $milestone->evidences
                        as $evidence
                    ) {

                        $this->rememberFile(
                            files: $files,
                            model: $evidence
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 4. IDENTIFICAR DOCUMENTOS DE LA UNIDAD
                |--------------------------------------------------------------------------
                |
                | Guardamos los IDs ANTES de borrar document_units.
                |
                */

                $documentIds = DocumentUnit::query()
                    ->where(
                        'unit_id',
                        $lockedUnit->id
                    )
                    ->pluck(
                        'document_id'
                    )
                    ->unique()
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | 5. PAUSAS + SESIONES DE ARMADO
                |--------------------------------------------------------------------------
                |
                | assembly_work_pauses depende de assembly_work_sessions,
                | por eso pausas se eliminan primero.
                |
                */

                foreach (
                    $lockedUnit->milestones
                    as $milestone
                ) {

                    $workSession =
                        $milestone->assemblyWorkSession;


                    if ($workSession) {

                        $workSession
                            ->pauses()
                            ->delete();


                        $workSession
                            ->delete();
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 6. ENTREGAS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $lockedUnit->milestones
                    as $milestone
                ) {

                    if (
                        $milestone->carrierDelivery
                    ) {

                        $milestone
                            ->carrierDelivery()
                            ->delete();
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 7. EVIDENCIAS
                |--------------------------------------------------------------------------
                |
                | Los archivos ya fueron registrados arriba.
                | Aquí borramos solamente los registros.
                |
                */

                foreach (
                    $lockedUnit->milestones
                    as $milestone
                ) {

                    $milestone
                        ->evidences()
                        ->delete();
                }


                /*
                |--------------------------------------------------------------------------
                | 8. MILESTONES
                |--------------------------------------------------------------------------
                */

                foreach (
                    $lockedUnit->milestones
                    as $milestone
                ) {

                    $milestone->delete();
                }


                /*
                |--------------------------------------------------------------------------
                | 9. EVENTOS DE TRAZABILIDAD
                |--------------------------------------------------------------------------
                */

                UnitEvent::query()
                    ->where(
                        'unit_id',
                        $lockedUnit->id
                    )
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | 10. AUDITORÍA ASOCIADA A LA UNIDAD
                |--------------------------------------------------------------------------
                |
                | Este método soporta algunos esquemas comunes.
                | Si audit_logs no existe, simplemente no hace nada.
                |
                */

                $this->deleteAuditLogs(
                    $lockedUnit
                );


                /*
                |--------------------------------------------------------------------------
                | 11. DESASOCIAR DOCUMENTOS
                |--------------------------------------------------------------------------
                */

                DocumentUnit::query()
                    ->where(
                        'unit_id',
                        $lockedUnit->id
                    )
                    ->delete();


                /*
                |--------------------------------------------------------------------------
                | 12. DOCUMENTOS HUÉRFANOS
                |--------------------------------------------------------------------------
                |
                | MUY IMPORTANTE:
                |
                | Un CFDI puede contener múltiples unidades.
                |
                | Si documento 20 pertenece a:
                |
                | unidad 1
                | unidad 2
                |
                | y eliminamos unidad 1:
                |
                | documento 20 NO debe eliminarse.
                |
                | Sólo eliminamos documentos que ya no tienen ninguna
                | fila restante en document_units.
                |
                */

                $orphanDocumentIds =
                    $documentIds
                        ->filter(
                            function ($documentId) {

                                return !DocumentUnit::query()
                                    ->where(
                                        'document_id',
                                        $documentId
                                    )
                                    ->exists();
                            }
                        )
                        ->values();


                if (
                    $orphanDocumentIds
                        ->isNotEmpty()
                ) {

                    /*
                     * Bloqueamos los documentos antes
                     * de destruirlos.
                     */
                    $orphanDocuments =
                        Document::query()
                            ->whereIn(
                                'id',
                                $orphanDocumentIds
                            )
                            ->lockForUpdate()
                            ->get();


                    /*
                     * Guardamos XML/PDF para eliminarlos
                     * físicamente después del commit.
                     */
                    foreach (
                        $orphanDocuments
                        as $document
                    ) {

                        $this->rememberFile(
                            files: $files,
                            model: $document
                        );
                    }


                    /*
                     * invoice_data pertenece al XML.
                     */
                    InvoiceData::query()
                        ->whereIn(
                            'document_id',
                            $orphanDocumentIds
                        )
                        ->delete();


                    /*
                     * Al eliminar documents también desaparece:
                     *
                     * - file_hash
                     * - storage_path
                     * - processing_status
                     * - referencia al archivo importado
                     *
                     * Esto es necesario para poder volver
                     * a importar el mismo XML/PDF.
                     */
                    Document::query()
                        ->whereIn(
                            'id',
                            $orphanDocumentIds
                        )
                        ->delete();
                }


                /*
                |--------------------------------------------------------------------------
                | 13. UNIDAD
                |--------------------------------------------------------------------------
                |
                | Unit usa SoftDeletes.
                |
                | delete()      = deleted_at
                | forceDelete() = DELETE físico
                |
                */

                $lockedUnit
                    ->forceDelete();


                /*
                 * Devolvemos solamente las referencias
                 * de archivos. MySQL todavía debe completar
                 * correctamente el commit.
                 */
                return array_values(
                    $files
                );

            },
            attempts: 3
        );


        /*
        |--------------------------------------------------------------------------
        | 14. ARCHIVOS FÍSICOS
        |--------------------------------------------------------------------------
        |
        | Llegamos aquí únicamente después de que la transacción SQL
        | se confirmó correctamente.
        |
        */

        $this->deletePhysicalFiles(
            $filesToDelete
        );


        /*
        |--------------------------------------------------------------------------
        | LOG DE DESARROLLO
        |--------------------------------------------------------------------------
        |
        | No queda dentro del expediente porque la unidad ya no existe.
        | Sólo ayuda a detectar quién ejecutó un purge durante pruebas.
        |
        */

        Log::warning(
            'Unidad eliminada permanentemente durante pruebas.',
            [
                'unit_id' =>
                    $unit->getKey(),

                'performed_by' =>
                    $userId,

                'reason' =>
                    $reason,
            ]
        );
    }


    /**
     * Guarda la referencia física de un archivo.
     *
     * Documents utiliza:
     *
     * storage_disk
     * storage_path
     *
     * Para Evidence también soportamos algunos nombres
     * alternativos por seguridad.
     *
     * @param array<string, array{disk:string,path:string}> $files
     */
    private function rememberFile(
        array &$files,
        Model $model
    ): void {

        $path =
            $model->getAttribute(
                'storage_path'
            )
            ?: $model->getAttribute(
                'file_path'
            )
            ?: $model->getAttribute(
                'path'
            );


        if (
            !is_string($path)
            || trim($path) === ''
        ) {

            return;
        }


        $disk =
            $model->getAttribute(
                'storage_disk'
            );


        if (
            !is_string($disk)
            || trim($disk) === ''
        ) {

            $disk = 'local';
        }


        $disk = trim(
            $disk
        );

        $path = trim(
            $path
        );


        $key =
            $disk
            . '|'
            . $path;


        $files[$key] = [
            'disk' =>
                $disk,

            'path' =>
                $path,
        ];
    }


    /**
     * @param array<int, array{disk:string,path:string}> $files
     */
    private function deletePhysicalFiles(
        array $files
    ): void {

        foreach (
            $files
            as $file
        ) {

            try {

                $storage =
                    Storage::disk(
                        $file['disk']
                    );


                /*
                 * Si ya desapareció, no lo consideramos error.
                 */
                if (
                    !$storage->exists(
                        $file['path']
                    )
                ) {

                    continue;
                }


                $deleted =
                    $storage->delete(
                        $file['path']
                    );


                if (!$deleted) {

                    Log::warning(
                        'No fue posible eliminar un archivo físico durante el purge.',
                        [
                            'disk' =>
                                $file['disk'],

                            'path' =>
                                $file['path'],
                        ]
                    );
                }

            } catch (Throwable $exception) {

                /*
                 * La DB YA fue confirmada.
                 *
                 * No lanzamos nuevamente la excepción porque haría
                 * creer a la UI que la unidad sigue existiendo.
                 */
                report(
                    $exception
                );


                Log::error(
                    'Error eliminando archivo físico después del purge.',
                    [
                        'disk' =>
                            $file['disk'],

                        'path' =>
                            $file['path'],

                        'error' =>
                            $exception
                                ->getMessage(),
                    ]
                );
            }
        }
    }


    /**
     * Elimina registros de auditoría asociados directamente
     * a Unit si existe una tabla audit_logs.
     */
    private function deleteAuditLogs(
        Unit $unit
    ): void {

        if (
            !Schema::hasTable(
                'audit_logs'
            )
        ) {

            return;
        }


        /*
         * Variante:
         *
         * audit_logs.unit_id
         */
        if (
            Schema::hasColumn(
                'audit_logs',
                'unit_id'
            )
        ) {

            DB::table(
                'audit_logs'
            )
                ->where(
                    'unit_id',
                    $unit->id
                )
                ->delete();

            return;
        }


        /*
         * Variante polimórfica:
         *
         * subject_type
         * subject_id
         */
        if (
            Schema::hasColumn(
                'audit_logs',
                'subject_type'
            )
            &&
            Schema::hasColumn(
                'audit_logs',
                'subject_id'
            )
        ) {

            DB::table(
                'audit_logs'
            )
                ->where(
                    'subject_type',
                    Unit::class
                )
                ->where(
                    'subject_id',
                    $unit->id
                )
                ->delete();

            return;
        }


        /*
         * Variante polimórfica:
         *
         * auditable_type
         * auditable_id
         */
        if (
            Schema::hasColumn(
                'audit_logs',
                'auditable_type'
            )
            &&
            Schema::hasColumn(
                'audit_logs',
                'auditable_id'
            )
        ) {

            DB::table(
                'audit_logs'
            )
                ->where(
                    'auditable_type',
                    Unit::class
                )
                ->where(
                    'auditable_id',
                    $unit->id
                )
                ->delete();
        }
    }
}