<?php

namespace App\Filament\Resources\Contracts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('is_direct')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->searchable(),
                TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contract_type')
                    ->searchable(),
                TextColumn::make('engagement_type')
                    ->searchable(),
                TextColumn::make('property_type')
                    ->searchable(),
                TextColumn::make('contract_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('visits')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('frequency')
                    ->searchable(),
                TextColumn::make('payment_terms')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('duration_type')
                    ->searchable(),
                TextColumn::make('start')
                    ->date()
                    ->sortable(),
                TextColumn::make('end')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('criticality')
                    ->searchable(),
                TextColumn::make('tin')
                    ->searchable(),
                TextColumn::make('tax_code')
                    ->searchable(),
                TextColumn::make('billing_name')
                    ->searchable(),
                TextColumn::make('supervisor_reviewed'),
                TextColumn::make('supervisor_reviewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('supervisor.name')
                    ->searchable(),
                TextColumn::make('won_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('renew_ok'),
                TextColumn::make('offboarded'),
                TextColumn::make('offboarded_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('offboarded_method')
                    ->searchable(),
                TextColumn::make('renewal_remarks')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
