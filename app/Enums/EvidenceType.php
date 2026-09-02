<?php

namespace App\Enums;

enum EvidenceType: string
{
    case IMAGE = 'IMAGE';

    case VIDEO = 'VIDEO';

    case DOCUMENT = 'DOCUMENT';

    case SIGNATURE = 'SIGNATURE';
}
