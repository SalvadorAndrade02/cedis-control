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
        Schema::create('invoice_data', function (Blueprint $table) {
            $table->id();

            /*
         * invoice_data se generará principalmente
         * a partir del XML.
         */
            $table->foreignId('document_id')
                ->unique()
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->string('cfdi_version', 10)->nullable();

            $table->string('series', 50)->nullable();
            $table->string('folio', 100)->nullable();

            // UUID del Timbre Fiscal.
            $table->string('uuid', 36)->nullable()->unique();

            $table->dateTime('issued_at')->nullable();
            $table->dateTime('certified_at')->nullable();

            $table->string('issuer_rfc', 20)->nullable();
            $table->string('issuer_name', 200)->nullable();

            $table->string('receiver_rfc', 20)->nullable();
            $table->string('receiver_name', 200)->nullable();

            $table->string('currency', 10)->nullable();

            // PPD / PUE...
            $table->string('payment_method', 20)->nullable();

            // 99, 03, etc.
            $table->string('payment_form', 20)->nullable();

            $table->decimal('subtotal', 15, 2)->nullable();

            $table->decimal('tax', 15, 2)->nullable();

            $table->decimal('total', 15, 2)->nullable();

            /*
         * Datos adicionales del CFDI que queramos conservar
         * sin crear decenas de columnas.
         */
            $table->json('raw_data')->nullable();

            $table->timestamps();

            $table->index(['series', 'folio']);
            $table->index('issuer_rfc');
            $table->index('receiver_rfc');
            $table->index('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_data');
    }
};
