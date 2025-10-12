<?php

namespace App\Filament\Resources\JobOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('code')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('summary')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('target_date')
                    ->required(),
                DateTimePicker::make('target_end'),
                Select::make('client_id')
                    ->relationship('client', 'name'),
                Select::make('address_id')
                    ->relationship('address', 'id'),
                TextInput::make('site_id')
                    ->numeric(),
                TextInput::make('job_order_type')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('unscheduled'),
                TextInput::make('created_by')
                    ->numeric(),
                TextInput::make('reviewed_by')
                    ->numeric(),
                TextInput::make('sa_uploaded_by')
                    ->numeric(),
                TextInput::make('jobable_type')
                    ->required(),
                TextInput::make('jobable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('approved')
                    ->numeric()
                    ->default(0),
                TextInput::make('reviewed')
                    ->numeric()
                    ->default(0),
                Textarea::make('pre_service_notes')
                    ->columnSpanFull(),
                Textarea::make('review')
                    ->columnSpanFull(),
                Textarea::make('dept_head_review')
                    ->columnSpanFull(),
                TextInput::make('rating'),
                TextInput::make('dept_head_rating'),
                Textarea::make('service_acknowledgement')
                    ->columnSpanFull(),
                Textarea::make('signature')
                    ->columnSpanFull(),
                Textarea::make('addpest')
                    ->columnSpanFull(),
                Textarea::make('endorsed_files')
                    ->columnSpanFull(),
                Textarea::make('endorsed_jo_hard_copy')
                    ->columnSpanFull(),
                Textarea::make('endorsed_sa')
                    ->columnSpanFull(),
                Textarea::make('endorsed_layout')
                    ->columnSpanFull(),
                TextInput::make('tech_call_received')
                    ->numeric()
                    ->default(0),
                TextInput::make('upsell')
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('reviewed_at'),
            ]);
    }
}
