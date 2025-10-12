<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnnualAcquisitionsHeadCountByInquirySource;
use App\Filament\Widgets\AnnualAcquisitionsHeadCountByProposalsSent;
use App\Models\Lead;
use App\Traits\HasAssignees;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Tiptap\Nodes\Text;
use UnitEnum;

class AcquisitionsSummary extends Page implements HasTable
{
    use InteractsWithTable, HasAssignees;

    protected string $view = 'filament.pages.acquisitions-summary';
    protected static string | UnitEnum | null $navigationGroup = 'Summaries';

    protected function getHeaderWidgets(): array
    {
        return [
            AnnualAcquisitionsHeadCountByInquirySource::class,
            AnnualAcquisitionsHeadCountByProposalsSent::class
        ];
    }
}
