<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {

            $table->softDeletes();

            $table
                ->foreignId('deleted_by')
                ->nullable()
                ->after('deleted_at')
                ->constrained('users')
                ->nullOnDelete();

            $table
                ->text('deletion_reason')
                ->nullable()
                ->after('deleted_by');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {

            $table->dropForeign([
                'deleted_by',
            ]);

            $table->dropColumn([
                'deleted_by',
                'deletion_reason',
            ]);

            $table->dropSoftDeletes();
        });
    }
};