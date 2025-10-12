<?php

namespace App\Filament\Widgets;

use App\Traits\HasAssignees;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnnualAcquisitionsHeadCountByProposalsSent extends TableWidget
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
            ->heading('Head count handled by status')
            ->columns([
                TextColumn::make('status')->label('Status'),
                ...$cols
            ])
            ->records(fn()=>$this->getData());
    }

    private function getData(): array {
        $assignees = $this->getAssignees();

        $query ="select leads.status ";
        foreach ($assignees as $assignee) {
            $query .= ", SUM(CASE WHEN users.name = '{$assignee->name}' THEN 1 ELSE 0 END) as '{$assignee->name}' ";
        }
        $query .= "from leads join users on leads.assigned_to = users.id ";
        $query .= "where year(leads.created_at) = ? ";
        $query .= "group by leads.status ";
        $query .= "order by leads.status";

        $data = collect(DB::select($query, [now()->year]))->map(function($item){
            return (array) $item;
        })->toArray();
        return $data;
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
