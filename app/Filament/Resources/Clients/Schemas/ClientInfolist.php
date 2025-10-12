<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('info')
                    ->compact()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('classification'),
                        TextEntry::make('client_since')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('code')
                            ->placeholder('-'),

                    ])->columns(4)->columnSpanFull(),
                RepeatableEntry::make('contact_information')
                    ->table([
                        RepeatableEntry\TableColumn::make('Type'),
                        RepeatableEntry\TableColumn::make('Value'),
                    ])
                    ->schema([
                        TextEntry::make('type'),
                        TextEntry::make('value'),
                    ])
                    ->placeholder('-')
                    ->columnSpan(2),
                Section::make('Accounting')
                    ->compact()
                    ->schema([
                    TextEntry::make('tin')
                        ->placeholder('-'),
                    TextEntry::make('tax_code')
                        ->placeholder('-'),
                    TextEntry::make('xeroid')
                        ->placeholder('-'),
                    TextEntry::make('billing_name')
                        ->placeholder('-'),
                    TextEntry::make('billing_address')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ])->columns(2),
                Section::make('Internal')
                    ->compact()
                    ->schema([
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-'),
                ])->columns(2),
            ]);
    }
}
