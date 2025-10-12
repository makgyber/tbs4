<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ExternalServiceMonitoring;
//use App\Filament\Widgets\JobOrdersThisMonth;
//use App\Filament\Widgets\LeadsByServiceTypeChart;
//use App\Filament\Widgets\LeadsBySourceChart;
//use App\Filament\Widgets\LeadsChart;
//use App\Filament\Widgets\RetentionLeadsFunnel;
//use App\Filament\Widgets\SchedulingTrendChart;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return !auth()->user()->hasRole('technician');
    }

    public function mount(): void
    {
        if (auth()->user()->hasRole('technician')) {
            redirect(route('filament.admin.pages.command-center'));
        }
    }

    public function getWidgets(): array
    {
        return [
            ExternalServiceMonitoring::class,
//            SchedulingTrendChart::class,
//            JobOrdersThisMonth::class,
//            LeadsBySourceChart::class,
//            LeadsByServiceTypeChart::class,
//            LeadsChart::class,
//            RetentionLeadsFunnel::class,

        ];
    }
}
