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
        return [
            Action::make('tambah_laporan')
                ->label('Tambah Laporan')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn () => \App\Filament\Lapangan\Resources\LaporanResource::getUrl('create')),
        ];
    }

    public function getLaporans()
    {
        return \App\Models\Laporan::with(['user', 'area'])
            ->orderBy('tanggal_lapor', 'desc')
            ->get();
    }
}
