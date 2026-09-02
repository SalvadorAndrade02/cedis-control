<?php

namespace App\Enums;

enum MilestoneStage: string
{
    case ARRIVAL = 'ARRIVAL';

    case ASSEMBLY_COMPLETED = 'ASSEMBLY_COMPLETED';

    case CARRIER_DELIVERY = 'CARRIER_DELIVERY';
}
