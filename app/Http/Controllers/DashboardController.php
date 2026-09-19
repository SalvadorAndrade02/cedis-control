<?php

namespace App\Http\Controllers;

use App\Enums\TransferAssignmentStatus;
use App\Models\UnitTransferAssignment;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DEL TRASLADISTA
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('TRASLADISTA')) {

            /*
             * Consulta base:
             *
             * únicamente asignaciones activas
             * del usuario conectado.
             */
            $activeTransfersQuery =
                UnitTransferAssignment::query()
                    ->where(
                        'transporter_user_id',
                        $user->id
                    )
                    ->where(
                        'status',
                        TransferAssignmentStatus::ASSIGNED->value
                    );


            /*
            |--------------------------------------------------------------------------
            | TRASLADOS ACTIVOS
            |--------------------------------------------------------------------------
            */

            $activeTransfers =
                (clone $activeTransfersQuery)
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | GASOLINA PENDIENTE
            |--------------------------------------------------------------------------
            */

            $pendingFuel =
                (clone $activeTransfersQuery)
                    ->whereDoesntHave(
                        'fuelLoads'
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | COMBUSTIBLE REGISTRADO
            |--------------------------------------------------------------------------
            */

            $fuelRegistered =
                (clone $activeTransfersQuery)
                    ->whereHas(
                        'fuelLoads'
                    )
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | PENDIENTES QUE REQUIEREN ATENCIÓN
            |--------------------------------------------------------------------------
            */

            $pendingFuelAssignments =
                (clone $activeTransfersQuery)
                    ->whereDoesntHave(
                        'fuelLoads'
                    )
                    ->with([
                        'unit.brand',
                    ])
                    ->orderBy(
                        'assigned_at'
                    )
                    ->limit(6)
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | TRASLADOS ACTIVOS RECIENTES
            |--------------------------------------------------------------------------
            */

            $recentTransfers =
                (clone $activeTransfersQuery)
                    ->with([
                        'unit.brand',
                        'fuelLoads',
                    ])
                    ->latest(
                        'assigned_at'
                    )
                    ->limit(6)
                    ->get();


            return view(
                'dashboard',
                [
                    'isTransporterDashboard' =>
                        true,

                    'activeTransfers' =>
                        $activeTransfers,

                    'pendingFuel' =>
                        $pendingFuel,

                    'fuelRegistered' =>
                        $fuelRegistered,

                    'pendingFuelAssignments' =>
                        $pendingFuelAssignments,

                    'recentTransfers' =>
                        $recentTransfers,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD NORMAL
        |--------------------------------------------------------------------------
        |
        | ADMIN
        | RECEPCION
        | ARMADO
        | ENTREGA
        | SUPERVISOR
        |
        | Sigue cargando exactamente la misma vista de antes.
        |
        */

        return view(
            'dashboard',
            [
                'isTransporterDashboard' =>
                    false,
            ]
        );
    }
}