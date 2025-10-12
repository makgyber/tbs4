<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HasAssignees;

class AnnualAcquisitionsHeadCountByInquirySource extends TableWidget
{
    use HasAssignees;


    public function table(Table $table): Table
    {
        $assignees = $this->getAssignees();
        $cols = [];
        foreach ($assignees as $assignee) {
            $cols[] = TextColumn::make($assignee->name);
        }

        return $table
            ->heading('Head count by source of inquiry')
            ->columns([
                TextColumn::make('source')->label('Inquiry Source'),
                ...$cols
            ])
            ->records(fn()=>$this->getData());
    }

    private function getData(): array {
        $assignees = $this->getAssignees();

        $query ="select leads.source ";
        foreach ($assignees as $assignee) {
            $query .= ", SUM(CASE WHEN users.name = '{$assignee->name}' THEN 1 ELSE 0 END) as '{$assignee->name}' ";
        }
        $query .= "from leads join users on leads.assigned_to = users.id ";
        $query .= "where year(leads.won_at) = ? ";
        $query .= "group by leads.source ";
        $query .= "order by leads.source";

        $data = collect(DB::select($query, [now()->year]))->map(function($item){
            if (is_null($item->source)) {
                $item->source = 'not specified';
            }
            return (array) $item;
        })->toArray();
        return $data;
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }


}

