<?php

namespace App\Enums;

enum VinSource: string
{
    case CONCEPT_NO_IDENTIFICATION =
    'CONCEPT_NO_IDENTIFICATION';

    case VEHICLE_COMPLEMENT_NIV =
    'VEHICLE_COMPLEMENT_NIV';

    case ADDENDA_SERIAL_NUMBER =
    'ADDENDA_SERIAL_NUMBER';

    case DESCRIPTION_NIV =
    'DESCRIPTION_NIV';

    case MANUAL =
    'MANUAL';
}
