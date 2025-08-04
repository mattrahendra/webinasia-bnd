<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 0;

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
