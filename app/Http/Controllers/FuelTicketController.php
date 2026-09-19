<?php

namespace App\Http\Controllers;

use App\Models\UnitFuelLoad;
use Illuminate\Support\Facades\Storage;

class FuelTicketController extends Controller
{
    public function __invoke(
        UnitFuelLoad $fuelLoad
    ) {
        $user = auth()->user();

        abort_unless(
            $user !== null
            && $user->can('fuel.view'),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | SEGURIDAD PARA TRASLADISTA
        |--------------------------------------------------------------------------
        |
        | ADMIN / ENTREGA / SUPERVISOR pueden consultar combustible.
        |
        | Un usuario que únicamente es TRASLADISTA
        | sólo puede abrir tickets pertenecientes a sus propias asignaciones.
        |
        */

        $isOperationalReviewer =
            $user->hasAnyRole([
                'ADMIN',
                'ENTREGA',
                'SUPERVISOR',
            ]);


        if (
            $user->hasRole('TRASLADISTA')
            && !$isOperationalReviewer
        ) {

            $belongsToUser =
                (int) $fuelLoad
                    ->transferAssignment
                    ->transporter_user_id
                === (int) $user->id;


            abort_unless(
                $belongsToUser,
                403
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR QUE EXISTA TICKET
        |--------------------------------------------------------------------------
        */

        abort_if(
            blank(
                $fuelLoad->ticket_storage_disk
            )
            || blank(
                $fuelLoad->ticket_storage_path
            ),
            404,
            'Este registro no tiene ticket.'
        );


        $disk =
            Storage::disk(
                $fuelLoad->ticket_storage_disk
            );


        abort_unless(
            $disk->exists(
                $fuelLoad->ticket_storage_path
            ),
            404,
            'El archivo del ticket no existe.'
        );


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA INLINE
        |--------------------------------------------------------------------------
        |
        | Se abre en el navegador.
        |
        */

        return $disk->response(
            $fuelLoad->ticket_storage_path,
            $fuelLoad->ticket_original_filename
            ?: 'ticket-combustible',
            [
                'Content-Type' =>
                    $fuelLoad->ticket_mime_type
                    ?: 'application/octet-stream',
            ],
            'inline'
        );
    }
}