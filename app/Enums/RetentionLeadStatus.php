<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum RetentionLeadStatus: string
{
//    use IsKanbanStatus;


    case Pending = 'pending';

    case Approved = 'approved';
    case Won = 'won';

    case Declined = 'declined';
}
