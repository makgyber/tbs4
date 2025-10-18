<?php

namespace App\Filament\Pages;

use App\Models\Retention;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;


class RetentionBoard extends BoardPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

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
                    ->size('lg')
                    ->hiddenLabel(),
                TextEntry::make('assignedTo.name')
                    ->color(Color::Emerald)
                    ->badge()->hiddenLabel(),
                TextEntry::make('created_at')->date()->hiddenLabel(),
            ]))
            ->filters([

            ]);

    }
}
