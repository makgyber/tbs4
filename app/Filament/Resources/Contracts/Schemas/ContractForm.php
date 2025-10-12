<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('is_direct')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                TextInput::make('assigned_to')
                    ->required()
                    ->numeric(),
                TextInput::make('contract_type')
                    ->required(),
                TextInput::make('engagement_type'),
                TextInput::make('property_type'),
                TextInput::make('contract_price')
                    ->required()
                    ->numeric(),
                TextInput::make('visits')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('frequency'),
                TextInput::make('payment_terms')
                    ->required(),
                TextInput::make('payment_status')
                    ->required(),
                TextInput::make('duration_type'),
                Textarea::make('scope_of_work')
                    ->columnSpanFull(),
                DatePicker::make('start'),
                DatePicker::make('end'),
                TextInput::make('status'),
                TextInput::make('criticality'),
                TextInput::make('tin'),
                TextInput::make('tax_code'),
                TextInput::make('billing_name'),
                Textarea::make('billing_address')
                    ->columnSpanFull(),
                Textarea::make('sites')
                    ->columnSpanFull(),
                TextInput::make('supervisor_reviewed'),
                DateTimePicker::make('supervisor_reviewed_at'),
                Select::make('supervisor_id')
                    ->relationship('supervisor', 'name'),
                DatePicker::make('won_at'),
                TextInput::make('renew_ok'),
                TextInput::make('offboarded'),
                DatePicker::make('offboarded_at'),
                TextInput::make('offboarded_method'),
                TextInput::make('renewal_remarks'),
            ]);
    }
}
