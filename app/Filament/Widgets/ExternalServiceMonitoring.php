<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExternalServiceMonitoring extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() => JobOrder::whereDate('target_date', now())
                    ->with('teams')
                    ->whereNotIn("job_order_type", [
                        "deliver billing", "pick-up check", "deliver letter/report", "meeting", "DAY OFF/WALANG PASOK",
                        "RELEASE/BODEGERO/OFFICE", "DRIVE/HATID", "OFFICE REPAIR (VEHICLE/EQUIPMENT)", "Pick up File",
                        "TRAINING/SEMINAR", "OFFICE WORK", "DELIVER ONLY", "DELIVER RENEWAL LETTER", "DELIVER CONTRACT",
                        "Absent"
                    ])
                    ->whereIn('status', ['scheduled', 'postponed', 'completed', 'started'])

            )
            ->columns([
                IconColumn::make('hasTechInputs')
                    ->label('Inputs')
                    ->icon(fn($state) => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-mark'
                    })->color(fn($state) => match ($state) {
                        true => Color::Green,
                        false => Color::Gray,
                    }),
                IconColumn::make('hasReinfestation')
                    ->label('Reinfestation')
                    ->icon(fn($state) => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-mark'
                    })->color(fn($state) => match ($state) {
                        true => Color::Green,
                        false => Color::Gray,
                    }),
                IconColumn::make('hasSlowfeeding')
                    ->label('Slow feeding')
                    ->icon(fn($state) => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-mark'
                    })->color(fn($state) => match ($state) {
                        true => Color::Green,
                        false => Color::Gray,
                    }),
                TextColumn::make('teams.code')
                    ->color(fn($record) => $record->teamColor)
                    ->sortable(),
                TextColumn::make('client.name')->sortable()->wrap(),
                TextColumn::make('address.street')->wrap(),
                TextColumn::make('sites.label')
            ])->groups(
                [
                    'teams.code',
                    'client.name'
                ]
            )->defaultGroup('teams.code');
    }

    protected function getTablePollingInterval(): ?string
    {
        return "3s";
    }

}
