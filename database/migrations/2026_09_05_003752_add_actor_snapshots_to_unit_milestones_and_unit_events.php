<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        */

        Schema::table(
            'unit_milestones',
            function (Blueprint $table) {

                $table
                    ->string(
                        'completed_by_name',
                        150
                    )
                    ->nullable()
                    ->after('completed_by');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Eventos
        |--------------------------------------------------------------------------
        */

        Schema::table(
            'unit_events',
            function (Blueprint $table) {

                $table
                    ->string(
                        'performed_by_name',
                        150
                    )
                    ->nullable()
                    ->after('performed_by');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Respaldar registros históricos existentes
        |--------------------------------------------------------------------------
        |
        | Para los registros que YA existen,
        | guardamos el nombre actual del usuario.
        |
        */

        DB::table('unit_milestones')
            ->whereNotNull('completed_by')
            ->orderBy('id')
            ->chunkById(
                100,
                function ($milestones) {

                    foreach ($milestones as $milestone) {

                        $name = DB::table('users')
                            ->where(
                                'id',
                                $milestone->completed_by
                            )
                            ->value('name');

                        if ($name) {

                            DB::table('unit_milestones')
                                ->where(
                                    'id',
                                    $milestone->id
                                )
                                ->update([
                                    'completed_by_name' =>
                                    $name,
                                ]);
                        }
                    }
                }
            );


        DB::table('unit_events')
            ->whereNotNull('performed_by')
            ->orderBy('id')
            ->chunkById(
                100,
                function ($events) {

                    foreach ($events as $event) {

                        $name = DB::table('users')
                            ->where(
                                'id',
                                $event->performed_by
                            )
                            ->value('name');

                        if ($name) {

                            DB::table('unit_events')
                                ->where(
                                    'id',
                                    $event->id
                                )
                                ->update([
                                    'performed_by_name' =>
                                    $name,
                                ]);
                        }
                    }
                }
            );
    }


    public function down(): void
    {
        Schema::table(
            'unit_milestones',
            function (Blueprint $table) {

                $table->dropColumn(
                    'completed_by_name'
                );
            }
        );


        Schema::table(
            'unit_events',
            function (Blueprint $table) {

                $table->dropColumn(
                    'performed_by_name'
                );
            }
        );
    }
};
