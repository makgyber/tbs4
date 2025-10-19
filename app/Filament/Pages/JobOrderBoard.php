<?php

namespace App\Filament\Pages;

use App\Models\JobOrder;
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
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;


class JobOrderBoard extends BoardPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static string | \UnitEnum | null $navigationGroup = "Kanban Boards";

    public function board(Board $board): Board
    {
        return $board
            ->query(JobOrder::query())
            ->columnIdentifier('status')
            ->positionIdentifier('order_column')
            ->columns([
                Column::make('unscheduled')->color('orange'),
                Column::make('scheduled')->color('blue'),
                Column::make('started')->color('green'),
                Column::make('completed')->color('grey'),
                Column::make('cancelled')->color('grey'),
            ])
            ->searchable(['summary'])
            ->cardSchema(fn(Schema $schema) => $schema        // Rich card content
                ->components([
                    TextEntry::make('client.name')
                        ->size('md')
                        ->hiddenLabel()
                        ->columnSpanFull(),
                    TextEntry::make('summary')
                        ->size('xs')
                        ->hiddenLabel()
                        ->columnSpanFull(),

                    TextEntry::make('address.street')
                        ->color(Color::Orange)
                        ->size('xs')
                        ->hiddenLabel()
                        ->columnSpanFull(),

                    TextEntry::make('address.sites.contact_person')
                        ->color(Color::Emerald)
                        ->size('xs')
                        ->hiddenLabel(),
                    TextEntry::make('address.sites.contact_number')
                        ->color(Color::Emerald)
                        ->size('xs')
                        ->hiddenLabel(),
                    TextEntry::make('jobable.supervisor.name')
                        ->size('xs')
                        ->hiddenLabel(),
                    TextEntry::make('job_order_type')
                        ->size('xs')
                        ->alignRight()->hiddenLabel(),

                ])->columns(2))
            ->filtersFormWidth(Width::FourExtraLarge)
            ->filtersFormColumns(2)
            ->filters([
                TernaryFilter::make('service_type')
                    ->default(true)
                    ->placeholder('All services')
                    ->trueLabel('Technical')
                    ->falseLabel('Non-technical')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotIn('job_order_type', JobOrder::NonTechnicalJobTypes),
                        false: fn (Builder $query) => $query->whereIn('job_order_type', JobOrder::NonTechnicalJobTypes),
                        blank: fn (Builder $query) => $query, // In this example, we do not want to filter the query when it is blank.
                    ),
//                    ->indicateUsing(function (array $data): array {
//                        $indicators = [];
//
//                        if ($data['isTechnical'] ?? null) {
//                            $indicators[] = Indicator::make('Visit date ' . Carbon::parse($data['visit_date'])->toFormattedDateString())
//                                ->removeField('visit_date');
//                        }
//
//                        return $indicators;
//                    }),
                Filter::make('target_date')
                    ->schema([
                        DatePicker::make('visit_date')->default(now())
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['visit_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('target_date', '=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['visit_date'] ?? null) {
                            $indicators[] = Indicator::make('Visit date ' . Carbon::parse($data['visit_date'])->toFormattedDateString())
                                ->removeField('visit_date');
                        }

                        return $indicators;
                    }),
                SelectFilter::make('jobable.supervisor.name')
                    ->searchable()
                    ->relationship('jobable.supervisor', 'name'),

//                Filter::make('created')
//                    ->columnSpanFull()
//                    ->columns(2)
//                    ->schema([
//                        DatePicker::make('created_from')
//                            ,
//                        DatePicker::make('created_until')
//                            ,
//                    ])
//                    ->query(function (Builder $query, array $data): Builder {
//                        return $query
//                            ->when(
//                                $data['created_from'],
//                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
//                            )
//                            ->when(
//                                $data['created_until'],
//                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
//                            );
//                    })
//                    ->indicateUsing(function (array $data): array {
//                        $indicators = [];
//
//                        if ($data['created_from'] ?? null) {
//                            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
//                                ->removeField('created_from');
//                        }
//
//                        if ($data['created_until'] ?? null) {
//                            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
//                                ->removeField('created_until');
//                        }
//
//                        return $indicators;
//                    }),
            ]);

    }
}
