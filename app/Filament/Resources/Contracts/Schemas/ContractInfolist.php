<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('is_direct')
                    ->numeric(),
                TextEntry::make('client.name')
                    ->label('Client'),
                TextEntry::make('assigned_to')
                    ->numeric(),
                TextEntry::make('contract_type'),
                TextEntry::make('engagement_type')
                    ->placeholder('-'),
                TextEntry::make('property_type')
                    ->placeholder('-'),
                TextEntry::make('contract_price')
                    ->numeric(),
                TextEntry::make('visits')
                    ->numeric(),
                TextEntry::make('frequency')
                    ->placeholder('-'),
                TextEntry::make('payment_terms'),
                TextEntry::make('payment_status'),
                TextEntry::make('duration_type')
                    ->placeholder('-'),
                TextEntry::make('scope_of_work')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('start')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('criticality')
                    ->placeholder('-'),
                TextEntry::make('tin')
                    ->placeholder('-'),
                TextEntry::make('tax_code')
                    ->placeholder('-'),
                TextEntry::make('billing_name')
                    ->placeholder('-'),
                TextEntry::make('billing_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sites')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('supervisor_reviewed')
                    ->placeholder('-'),
                TextEntry::make('supervisor_reviewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor')
                    ->placeholder('-'),
                TextEntry::make('won_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('renew_ok')
                    ->placeholder('-'),
                TextEntry::make('offboarded')
                    ->placeholder('-'),
                TextEntry::make('offboarded_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('offboarded_method')
                    ->placeholder('-'),
                TextEntry::make('renewal_remarks')
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
