<?php

namespace App\Services\Fuel;

use App\Enums\FuelAmountOption;
use App\Enums\NoFuelReason;
use App\Enums\TransferAssignmentStatus;
use App\Enums\UnitStatus;
use App\Models\UnitFuelLoad;
use App\Models\UnitTransferAssignment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegisterFuelLoadService
{
    /*
    |--------------------------------------------------------------------------
    | IMPORTES PREDEFINIDOS
    |--------------------------------------------------------------------------
    |
    | Trabajamos internamente en centavos para evitar comparar dinero
    | utilizando float.
    |
    */

    private const FIXED_AMOUNTS_CENTS = [
        15000, // $150
        20000, // $200
        30000, // $300
        50000, // $500
    ];


    /*
    |--------------------------------------------------------------------------
    | ARCHIVOS DE TICKET PERMITIDOS
    |--------------------------------------------------------------------------
    */

    private const ALLOWED_TICKET_MIMES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/heic',
        'image/heif',
    ];


    private const MAX_TICKET_SIZE =
        10 * 1024 * 1024; // 10 MB


    public function execute(
        UnitTransferAssignment $assignment,
        User $actor,
        FuelAmountOption|string $amountOption,
        int|float|string $amount,
        NoFuelReason|string|null $noFuelReason = null,
        ?string $reasonNotes = null,
        ?UploadedFile $ticket = null,
        ?string $observations = null,
    ): UnitFuelLoad {

        /*
        |--------------------------------------------------------------------------
        | AUTORIZACIÓN GENERAL
        |--------------------------------------------------------------------------
        */

        if (!$actor->active) {
            throw new AuthorizationException(
                'Tu usuario se encuentra inactivo.'
            );
        }


        if (!$actor->can('fuel.create')) {
            throw new AuthorizationException(
                'No tienes permiso para registrar combustible.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR OPCIÓN
        |--------------------------------------------------------------------------
        */

        $option =
            $amountOption instanceof FuelAmountOption
            ? $amountOption
            : FuelAmountOption::tryFrom(
                strtoupper(
                    trim(
                        (string) $amountOption
                    )
                )
            );


        if (!$option) {
            throw ValidationException::withMessages([
                'amountOption' =>
                    'La opción de importe seleccionada no es válida.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR IMPORTE
        |--------------------------------------------------------------------------
        */

        $amountCents =
            $this->amountToCents(
                $amount
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR IMPORTES PREDEFINIDOS
        |--------------------------------------------------------------------------
        */

        if (
            $option === FuelAmountOption::FIXED
            && !in_array(
                $amountCents,
                self::FIXED_AMOUNTS_CENTS,
                true
            )
        ) {
            throw ValidationException::withMessages([
                'amount' =>
                    'El importe seleccionado debe ser $150, $200, $300 o $500.',
            ]);
        }


        /*
         * $0 solamente se maneja mediante "Otro".
         */
        if (
            $amountCents === 0
            && $option !== FuelAmountOption::OTHER
        ) {
            throw ValidationException::withMessages([
                'amount' =>
                    'Para registrar una carga de $0 debes seleccionar la opción Otro.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR MOTIVO SIN GASOLINA
        |--------------------------------------------------------------------------
        */

        $reason = null;


        if ($amountCents === 0) {

            if ($noFuelReason === null) {
                throw ValidationException::withMessages([
                    'noFuelReason' =>
                        'Debes indicar por qué no se realizó la carga de gasolina.',
                ]);
            }


            $reason =
                $noFuelReason instanceof NoFuelReason
                ? $noFuelReason
                : NoFuelReason::tryFrom(
                    strtoupper(
                        trim(
                            (string) $noFuelReason
                        )
                    )
                );


            if (!$reason) {
                throw ValidationException::withMessages([
                    'noFuelReason' =>
                        'El motivo seleccionado no es válido.',
                ]);
            }


            /*
             * Cuando el motivo es "Otro",
             * necesitamos explicación.
             */
            if (
                $reason === NoFuelReason::OTHER
                && blank($reasonNotes)
            ) {
                throw ValidationException::withMessages([
                    'reasonNotes' =>
                        'Debes especificar el motivo por el que no se cargó gasolina.',
                ]);
            }

        } else {

            /*
             * Si sí hubo gasolina, estos campos no aplican.
             */
            $reason = null;
            $reasonNotes = null;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR TICKET
        |--------------------------------------------------------------------------
        */

        if (
            $amountCents > 0
            && !$ticket
        ) {
            throw ValidationException::withMessages([
                'ticket' =>
                    'Debes tomar o adjuntar una fotografía del ticket.',
            ]);
        }


        if ($ticket) {

            if (!$ticket->isValid()) {
                throw ValidationException::withMessages([
                    'ticket' =>
                        'El archivo del ticket no pudo cargarse correctamente.',
                ]);
            }


            if (
                $ticket->getSize()
                > self::MAX_TICKET_SIZE
            ) {
                throw ValidationException::withMessages([
                    'ticket' =>
                        'La fotografía del ticket no puede superar los 10 MB.',
                ]);
            }


            $mimeType =
                $ticket->getMimeType();


            if (
                !$mimeType
                || !in_array(
                    $mimeType,
                    self::ALLOWED_TICKET_MIMES,
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'ticket' =>
                        'El ticket debe ser una imagen JPG, PNG, WEBP, HEIC o HEIF.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ARCHIVOS CREADOS
        |--------------------------------------------------------------------------
        |
        | Si la transacción falla, Storage no hace rollback automáticamente.
        |
        */

        $storedPaths = [];


        try {

            return DB::transaction(
                function () use ($assignment, $actor, $option, $amountCents, $reason, $reasonNotes, $ticket, $observations, &$storedPaths) {

                    /*
                    |--------------------------------------------------------------------------
                    | BLOQUEAR ASIGNACIÓN
                    |--------------------------------------------------------------------------
                    |
                    | Esto también evita registros duplicados cuando dos
                    | solicitudes intentan guardar al mismo tiempo.
                    |
                    */

                    $lockedAssignment =
                        UnitTransferAssignment::query()
                            ->with('unit')
                            ->whereKey(
                                $assignment->id
                            )
                            ->lockForUpdate()
                            ->firstOrFail();


                    /*
                    |--------------------------------------------------------------------------
                    | ASIGNACIÓN ACTIVA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $lockedAssignment->status
                        !== TransferAssignmentStatus::ASSIGNED
                    ) {
                        throw ValidationException::withMessages([
                            'assignment' =>
                                'Esta asignación de traslado ya no se encuentra activa.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UNIDAD TODAVÍA EN PROCESO
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $lockedAssignment->unit->status
                        !== UnitStatus::DELIVERY_PENDING
                    ) {
                        throw ValidationException::withMessages([
                            'assignment' =>
                                'La unidad ya no se encuentra pendiente de entrega.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDAR PROPIETARIO DE LA ASIGNACIÓN
                    |--------------------------------------------------------------------------
                    |
                    | El trasladista sólo puede capturar combustible
                    | de SUS propias unidades.
                    |
                    | ADMIN conserva una excepción para soporte.
                    |
                    */

                    $isAdmin =
                        $actor->hasRole(
                            'ADMIN'
                        );


                    $isAssignedTransporter =
                        $actor->hasRole(
                            'TRASLADISTA'
                        )
                        && (int) $lockedAssignment
                            ->transporter_user_id
                        === (int) $actor->id;


                    if (
                        !$isAdmin
                        && !$isAssignedTransporter
                    ) {
                        throw new AuthorizationException(
                            'No puedes registrar combustible para una unidad asignada a otro trasladista.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | EVITAR REGISTRO DUPLICADO
                    |--------------------------------------------------------------------------
                    |
                    | Para este MVP permitimos un registro de combustible
                    | por asignación.
                    |
                    */

                    $alreadyRegistered =
                        UnitFuelLoad::query()
                            ->where(
                                'transfer_assignment_id',
                                $lockedAssignment->id
                            )
                            ->exists();


                    if ($alreadyRegistered) {
                        throw ValidationException::withMessages([
                            'assignment' =>
                                'Esta asignación ya cuenta con un registro de combustible.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INFORMACIÓN DEL TICKET
                    |--------------------------------------------------------------------------
                    */

                    $ticketDisk = null;
                    $ticketPath = null;
                    $ticketOriginalFilename = null;
                    $ticketMimeType = null;
                    $ticketFileSize = null;
                    $ticketFileHash = null;


                    if ($ticket) {

                        $ticketMimeType =
                            $ticket->getMimeType();


                        $extension =
                            $this->extensionForMime(
                                $ticketMimeType
                            );


                        $filename =
                            'fuel_'
                            . Str::uuid()
                            . '.'
                            . $extension;


                        $ticketPath =
                            'cedis/fuel/'
                            . $lockedAssignment->unit->vin
                            . '/'
                            . $filename;


                        $contents =
                            file_get_contents(
                                $ticket->getRealPath()
                            );


                        if ($contents === false) {
                            throw ValidationException::withMessages([
                                'ticket' =>
                                    'No fue posible leer la fotografía del ticket.',
                            ]);
                        }


                        $stored =
                            Storage::disk('local')
                                ->put(
                                    $ticketPath,
                                    $contents
                                );


                        if (!$stored) {
                            throw ValidationException::withMessages([
                                'ticket' =>
                                    'No fue posible almacenar la fotografía del ticket.',
                            ]);
                        }


                        $storedPaths[] =
                            $ticketPath;


                        $ticketDisk =
                            'local';


                        $ticketOriginalFilename =
                            $ticket
                                ->getClientOriginalName();


                        $ticketFileSize =
                            $ticket
                                ->getSize();


                        $ticketFileHash =
                            hash_file(
                                'sha256',
                                $ticket->getRealPath()
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CREAR REGISTRO
                    |--------------------------------------------------------------------------
                    */

                    return UnitFuelLoad::create([
                        'unit_id' =>
                            $lockedAssignment->unit_id,

                        'transfer_assignment_id' =>
                            $lockedAssignment->id,

                        'amount_option' =>
                            $option,

                        'amount' =>
                            $this->centsToDecimal(
                                $amountCents
                            ),

                        'no_fuel_reason' =>
                            $reason,

                        'reason_notes' =>
                            filled($reasonNotes)
                            ? trim($reasonNotes)
                            : null,

                        'fueled_at' =>
                            now(),

                        /*
                         * Snapshot histórico.
                         */
                        'registered_by' =>
                            $actor->id,

                        'registered_by_name' =>
                            $actor->name,

                        /*
                         * Ticket.
                         */
                        'ticket_storage_disk' =>
                            $ticketDisk,

                        'ticket_storage_path' =>
                            $ticketPath,

                        'ticket_original_filename' =>
                            $ticketOriginalFilename,

                        'ticket_mime_type' =>
                            $ticketMimeType,

                        'ticket_file_size' =>
                            $ticketFileSize,

                        'ticket_file_hash' =>
                            $ticketFileHash,

                        /*
                         * Observaciones libres.
                         */
                        'observations' =>
                            filled($observations)
                            ? trim($observations)
                            : null,
                    ]);
                }
            );

        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | LIMPIAR ARCHIVOS SI MYSQL FALLA
            |--------------------------------------------------------------------------
            */

            foreach ($storedPaths as $path) {

                Storage::disk('local')
                    ->delete(
                        $path
                    );
            }


            throw $exception;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CONVERTIR IMPORTE A CENTAVOS
    |--------------------------------------------------------------------------
    */

    private function amountToCents(
        int|float|string $amount
    ): int {

        $value =
            trim(
                (string) $amount
            );


        /*
         * La UI enviará valores como:
         *
         * 300
         * 300.00
         * 425.50
         * 0
         */

        if (
            !preg_match(
                '/^\d+(?:\.\d{1,2})?$/',
                $value
            )
        ) {
            throw ValidationException::withMessages([
                'amount' =>
                    'El importe debe ser un número válido con máximo dos decimales.',
            ]);
        }


        [$whole, $decimal] =
            array_pad(
                explode(
                    '.',
                    $value,
                    2
                ),
                2,
                ''
            );


        $whole =
            (int) $whole;


        $decimal =
            str_pad(
                $decimal,
                2,
                '0'
            );


        $cents =
            ($whole * 100)
            + (int) $decimal;


        /*
         * DECIMAL(10,2)
         *
         * Máximo:
         * 99,999,999.99
         */

        if (
            $cents > 9_999_999_999
        ) {
            throw ValidationException::withMessages([
                'amount' =>
                    'El importe indicado es demasiado grande.',
            ]);
        }


        return $cents;
    }


    /*
    |--------------------------------------------------------------------------
    | CENTAVOS → DECIMAL
    |--------------------------------------------------------------------------
    */

    private function centsToDecimal(
        int $cents
    ): string {

        return sprintf(
            '%d.%02d',
            intdiv(
                $cents,
                100
            ),
            $cents % 100
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXTENSIÓN SEGURA SEGÚN MIME
    |--------------------------------------------------------------------------
    */

    private function extensionForMime(
        string $mimeType
    ): string {

        return match ($mimeType) {

            'image/jpeg',
            'image/jpg' =>
                'jpg',

            'image/png' =>
                'png',

            'image/webp' =>
                'webp',

            'image/heic' =>
                'heic',

            'image/heif' =>
                'heif',

            default =>
                'jpg',
        };
    }
}