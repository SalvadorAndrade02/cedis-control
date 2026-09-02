<?php

namespace App\Services\Imports\DTOs;

use DOMDocument;
use DOMXPath;

final readonly class CfdiContext
{
    public function __construct(
        public DOMDocument $dom,
        public DOMXPath $xpath,
        public CfdiDocumentData $data,
    ) {}
}
