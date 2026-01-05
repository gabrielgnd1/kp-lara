<?php

namespace App\Filament\Lapangan\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.lapangan.pages.dashboard';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getLaporans()
    {
        return \App\Models\Laporan::with(['user', 'area'])
            ->orderBy('tanggal_lapor', 'desc')
            ->get();
    }
}
