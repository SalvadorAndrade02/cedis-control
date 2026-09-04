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
        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permisos
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Unidades
            'units.view',

            // Importaciones
            'imports.manage',

            // Llegada
            'arrival.view',
            'arrival.complete',

            // Armado
            'assembly.view',
            'assembly.complete',

            // Entrega
            'delivery.view',
            'delivery.complete',

            // Evidencias
            'evidences.view',

            // Administración
            'users.manage',
            'catalogs.manage',

            // Reportes
            'reports.view',

            // Correcciones
            'corrections.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'ADMIN',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions(
            Permission::all()
        );


        /*
        |--------------------------------------------------------------------------
        | RECEPCIÓN
        |--------------------------------------------------------------------------
        */

        $reception = Role::firstOrCreate([
            'name' => 'RECEPCION',
            'guard_name' => 'web',
        ]);

        $reception->syncPermissions([
            'units.view',
            'arrival.view',
            'arrival.complete',
            'evidences.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ARMADO
        |--------------------------------------------------------------------------
        */

        $assembly = Role::firstOrCreate([
            'name' => 'ARMADO',
            'guard_name' => 'web',
        ]);

        $assembly->syncPermissions([
            'units.view',
            'assembly.view',
            'assembly.complete',
            'evidences.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ENTREGA
        |--------------------------------------------------------------------------
        */

        $delivery = Role::firstOrCreate([
            'name' => 'ENTREGA',
            'guard_name' => 'web',
        ]);

        $delivery->syncPermissions([
            'units.view',
            'delivery.view',
            'delivery.complete',
            'evidences.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        $supervisor = Role::firstOrCreate([
            'name' => 'SUPERVISOR',
            'guard_name' => 'web',
        ]);

        $supervisor->syncPermissions([
            'units.view',

            'arrival.view',
            'assembly.view',
            'delivery.view',

            'evidences.view',

            'reports.view',
        ]);

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }
}
