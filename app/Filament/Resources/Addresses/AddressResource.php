<?php

namespace App\Filament\Resources\Addresses;

use App\Filament\Resources\Addresses\Pages\CreateAddress;
use App\Filament\Resources\Addresses\Pages\EditAddress;
use App\Filament\Resources\Addresses\Pages\ViewAddress;
use App\Filament\Resources\Addresses\RelationManagers\SitesRelationManager;
use App\Filament\Resources\Addresses\Schemas\AddressForm;
use App\Filament\Resources\Addresses\Schemas\AddressInfolist;
use App\Filament\Resources\Addresses\Tables\AddressesTable;
use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\Sites\SiteResource;
use App\Models\Address;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ClientResource::class;

    protected static ?string $recordTitleAttribute = 'street';

    public static function form(Schema $schema): Schema
    {
        return AddressForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AddressInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddressesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SitesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateAddress::route('/create'),
            'view' => ViewAddress::route('/{record}'),
            'edit' => EditAddress::route('/{record}/edit'),
        ];
    }
}
