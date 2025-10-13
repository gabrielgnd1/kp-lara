<?php

namespace App\Filament\Reservasi\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2; // This ensures Dashboard appears first
    protected static string $view = 'filament.reservasi.pages.dashboard';
    
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}