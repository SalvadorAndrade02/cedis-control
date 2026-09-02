<?php

namespace App\Enums;

enum DocumentProcessingStatus: string
{
    case PENDING = 'PENDING';

    case PROCESSED = 'PROCESSED';

    case REVIEW_REQUIRED = 'REVIEW_REQUIRED';

    case FAILED = 'FAILED';
}
