<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | LIMPIAR CACHÉ DE SPATIE
        |--------------------------------------------------------------------------
        |
        | Spatie conserva permisos en caché.
        | Limpiamos antes y después del seeder para asegurarnos
        | de que los permisos nuevos sean reconocidos.
        |
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | PERMISOS
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
             * Unidades.
             */
            'units.view',
            'units.delete',
            'units.restore',


            /*
             * Importaciones.
             */
            'imports.manage',


            /*
             * Llegada.
             */
            'arrival.view',
            'arrival.complete',


            /*
             * Armado.
             */
            'assembly.view',
            'assembly.complete',


            /*
             * Entrega.
             */
            'delivery.view',
            'delivery.complete',


            /*
             * Evidencias.
             */
            'evidences.view',


            /*
             * Administración.
             */
            'users.manage',
            'catalogs.manage',
            'corrections.manage',


            /*
             * Reportes.
             */
            'reports.view',


            /*
            |--------------------------------------------------------------------------
            | TRASLADOS
            |--------------------------------------------------------------------------
            */

            /*
             * Consultar asignaciones de traslado.
             */
            'transfers.view',

            /*
             * Asignar/reasignar una unidad
             * a un trasladista.
             */
            'transfers.assign',


            /*
            |--------------------------------------------------------------------------
            | COMBUSTIBLE
            |--------------------------------------------------------------------------
            */

            /*
             * Consultar registros de gasolina.
             */
            'fuel.view',

            /*
             * Registrar una carga o
             * una salida sin carga.
             */
            'fuel.create',
        ];


        /*
        |--------------------------------------------------------------------------
        | CREAR PERMISOS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' =>
                    $permission,

                'guard_name' =>
                    'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN DE ROLES
        |--------------------------------------------------------------------------
        */

        $roles = [

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            |
            | Acceso completo.
            |
            */

            'ADMIN' =>
                $permissions,


            /*
            |--------------------------------------------------------------------------
            | RECEPCIÓN
            |--------------------------------------------------------------------------
            */

            'RECEPCION' => [

                'units.view',

                'arrival.view',
                'arrival.complete',

                'evidences.view',
            ],


            /*
            |--------------------------------------------------------------------------
            | ARMADO
            |--------------------------------------------------------------------------
            */

            'ARMADO' => [

                'units.view',

                'assembly.view',
                'assembly.complete',

                'evidences.view',
            ],


            /*
            |--------------------------------------------------------------------------
            | ENTREGA
            |--------------------------------------------------------------------------
            |
            | Este rol puede decidir qué trasladista
            | recibirá una unidad.
            |
            | Además puede consultar si ya existe
            | información de combustible.
            |
            */

            'ENTREGA' => [

                'units.view',

                'delivery.view',
                'delivery.complete',

                'evidences.view',

                'transfers.view',
                'transfers.assign',

                'fuel.view',
            ],


            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR
            |--------------------------------------------------------------------------
            |
            | Consulta operación pero no modifica
            | asignaciones ni registra gasolina.
            |
            */

            'SUPERVISOR' => [

                'units.view',

                'arrival.view',
                'assembly.view',
                'delivery.view',

                'evidences.view',

                'reports.view',

                'transfers.view',

                'fuel.view',
            ],


            /*
            |--------------------------------------------------------------------------
            | TRASLADISTA
            |--------------------------------------------------------------------------
            |
            | IMPORTANTE:
            |
            | No otorgamos units.view porque actualmente
            | ese permiso permite consultar TODAS las unidades.
            |
            | El nuevo módulo mostrará exclusivamente las
            | asignaciones que pertenezcan al usuario conectado.
            |
            */

            'TRASLADISTA' => [

                'transfers.view',

                'fuel.view',
                'fuel.create',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | CREAR / ACTUALIZAR ROLES
        |--------------------------------------------------------------------------
        */

        foreach (
            $roles
            as $roleName => $rolePermissions
        ) {

            $role =
                Role::firstOrCreate([
                    'name' =>
                        $roleName,

                    'guard_name' =>
                        'web',
                ]);


            /*
             * syncPermissions garantiza que el rol
             * tenga exactamente estos permisos.
             */
            $role->syncPermissions(
                $rolePermissions
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LIMPIAR CACHÉ NUEVAMENTE
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }
}