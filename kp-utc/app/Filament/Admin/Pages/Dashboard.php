<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AdminStatsOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.admin.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(?string $breakpoint = null): int
    {
        return 4;
    }
}