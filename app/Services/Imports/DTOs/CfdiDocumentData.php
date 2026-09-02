<?php

namespace App\Services\Imports\DTOs;

use Carbon\CarbonImmutable;

final readonly class CfdiDocumentData
{
    public function __construct(
        public ?string $version,
        public ?string $series,
        public ?string $folio,

        public ?CarbonImmutable $issuedAt,
        public ?CarbonImmutable $certifiedAt,

        public ?string $currency,

        public ?string $paymentMethod,
        public ?string $paymentForm,

        public ?string $issuerRfc,
        public ?string $issuerName,

        public ?string $receiverRfc,
        public ?string $receiverName,

        public ?string $uuid,

        public ?string $subtotal,
        public ?string $tax,
        public ?string $total,
    ) {}
}
