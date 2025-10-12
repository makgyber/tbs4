<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AddressInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('region.name')
                    ->label('Region'),
                TextEntry::make('province.name')
                    ->label('Province'),
                TextEntry::make('city.name')
                    ->label('City'),
                TextEntry::make('barangay.name')
                    ->label('Barangay')
                    ->placeholder('-'),
                TextEntry::make('street'),
                TextEntry::make('longitude')
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
