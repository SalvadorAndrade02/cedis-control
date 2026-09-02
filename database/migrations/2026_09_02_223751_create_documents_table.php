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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            // XML / PDF
            $table->string('document_type', 20);

            $table->string('original_filename');

            // Laravel Storage
            $table->string('storage_disk', 50)->default('local');
            $table->string('storage_path', 500);

            // SHA-256 para detectar archivos duplicados.
            $table->char('file_hash', 64)->unique();

            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            /*
         * Sirve para asociar visualmente XML + PDF.
         *
         * Ejemplos:
         * SIAA50536
         * 0300054189
         * SA29952
         */
            $table->string('pair_key', 150)->nullable()->index();

            /*
         * PENDING
         * PROCESSED
         * REVIEW_REQUIRED
         * FAILED
         */
            $table->string('processing_status', 30)->default('PENDING');

            $table->timestamp('processed_at')->nullable();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('document_type');
            $table->index('processing_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
