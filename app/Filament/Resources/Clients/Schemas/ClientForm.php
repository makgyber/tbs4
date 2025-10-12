<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('classification')
                    ->required()
                    ->default('A'),
                Textarea::make('contact_information')
                    ->columnSpanFull(),
                DatePicker::make('client_since'),
                TextInput::make('code'),
                TextInput::make('tin'),
                TextInput::make('tax_code'),
                TextInput::make('billing_name'),
                Textarea::make('billing_address')
                    ->columnSpanFull(),
                TextInput::make('xeroid'),
            ]);
    }
}
