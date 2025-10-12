<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),
                Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required(),
                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required(),
                Select::make('barangay_id')
                    ->relationship('barangay', 'name'),
                TextInput::make('street')
                    ->required(),
                TextInput::make('longitude'),
                TextInput::make('latitude'),
            ]);
    }
}
