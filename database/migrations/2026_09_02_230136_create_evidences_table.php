<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_milestone_id')
                ->constrained('unit_milestones')
                ->cascadeOnDelete();

            /*
         * Puede quedar NULL para una evidencia
         * adicional que el usuario agregue libremente.
         */
            $table->foreignId('evidence_requirement_id')
                ->nullable()
                ->constrained('evidence_requirements')
                ->nullOnDelete();

            /*
         * IMAGE
         * VIDEO
         * DOCUMENT
         * SIGNATURE
         */
            $table->string('type', 30);

            /*
         * Laravel Storage.
         */
            $table->string('storage_disk', 50)->default('local');
            $table->string('storage_path', 500);

            $table->string('original_filename')->nullable();

            $table->string('mime_type', 100)->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            /*
         * SHA-256.
         */
            $table->char('file_hash', 64)->nullable();

            /*
         * Cuando realmente fue tomada la fotografía.
         * Puede ser diferente de uploaded_at.
         */
            $table->dateTime('captured_at')->nullable();

            $table->text('description')->nullable();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
         * No queremos borrar evidencia físicamente
         * sin dejar rastro.
         */
            $table->softDeletes();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('deletion_reason')->nullable();

            $table->index('type');
            $table->index('file_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
