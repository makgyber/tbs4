<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum AcquisitionLeadStatus: string
{
//    use IsKanbanStatus;

    case Inquiry = 'inquiry';
    case Qualified = 'qualified';
    case Opportunity = 'opportunity';

    case ProposalSent = 'proposal sent';
//    case Approved = 'approved';

    case Won = 'won';
    case Declined = 'declined';


    public static function statuses(): Collection
    {
//        return collect(static::kanbanCases())
////            ->filter(fn($it) => $it->getId() !== 'qualified')
//            ->map(function (self $item) {
//                return [
//                    'id' => $item->getId(),
//                    'title' => $item->getTitle(),
//                ];
//            });
        return collect([]);


    }
}
