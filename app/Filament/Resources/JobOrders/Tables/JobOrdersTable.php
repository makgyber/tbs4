<?php

namespace App\Filament\Resources\JobOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('target_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('target_end')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->searchable(),
                TextColumn::make('address.id')
                    ->searchable(),
                TextColumn::make('site_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('job_order_type')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reviewed_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sa_uploaded_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jobable_type')
                    ->searchable(),
                TextColumn::make('jobable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reviewed')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating')
                    ->searchable(),
                TextColumn::make('dept_head_rating')
                    ->searchable(),
                TextColumn::make('tech_call_received')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('upsell')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable(),
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
