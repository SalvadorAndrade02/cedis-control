<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table(
            'unit_transfer_assignments',
            function (Blueprint $table) {

                $table
                    ->string(
                        'origin_name',
                        150
                    )
                    ->nullable()
                    ->after('status');

                $table
                    ->string(
                        'destination_name',
                        255
                    )
                    ->nullable()
                    ->after('origin_name');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'unit_transfer_assignments',
            function (Blueprint $table) {

                $table->dropColumn([
                    'origin_name',
                    'destination_name',
                ]);
            }
        );
    }
};