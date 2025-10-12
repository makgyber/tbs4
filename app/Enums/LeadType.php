<?php

namespace App\Enums;

enum LeadType: string
{
    case Acquisition = 'new';
    case Retention = "retention";

}
