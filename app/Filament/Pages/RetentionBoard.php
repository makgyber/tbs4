<?php

namespace App\Filament\Pages;

use App\Models\Retention;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;


class RetentionBoard extends BoardPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static string | \UnitEnum | null $navigationGroup = "Kanban Boards";

    public function board(Board $board): Board
    {
        return $board
            ->query(Retention::query())
            ->columnIdentifier('status')
            ->positionIdentifier('order_column')
            ->columns([
                Column::make('pending')->label('Pending')->color('orange'),
                Column::make('approved')->label('Approved')->color('blue'),
                Column::make('won')->label('Won')->color('green'),
                Column::make('declined')->label('Declined')->color('grey'),
            ])
            ->searchable(['summary'])
            ->cardSchema(fn(Schema $schema) => $schema        // Rich card content
            ->components([
                TextEntry::make('summary')
                    ->size('md')
                    ->hiddenLabel()
                    ->columnSpanFull(),
                TextEntry::make('assignedTo.name')
                    ->color(Color::Emerald)
                    ->size('xs')
                    ->hiddenLabel(),
                TextEntry::make('created_at')
                    ->size('xs')
                    ->alignRight()
                    ->date()->hiddenLabel(),

            ])->columns(2))
            ->filtersFormWidth(Width::FourExtraLarge)
            ->filtersFormColumns(2)
            ->filters([
                SelectFilter::make('assignedTo')
                    ->searchable()
                    ->relationship('assignedTo', 'name'),
                Filter::make('overdue')
                    ->schema([
                        DatePicker::make('created_from')
                            ->default(now()->firstOfMonth()),
                        DatePicker::make('created_until')
                            ->default(now()->lastOfMonth()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),
            ]);

    }
}
