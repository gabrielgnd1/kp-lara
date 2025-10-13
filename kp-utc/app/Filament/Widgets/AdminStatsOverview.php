<?php

namespace App\Filament\Widgets;

use App\Models\Reservasi;
use App\Models\Laporan;
use App\Models\Fasilitas;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Fasilitas', Fasilitas::distinct('nama')->count('nama'))
                ->description('Jumlah total fasilitas yang tersedia')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            
            Stat::make('Reservasi Aktif', Reservasi::where('status_reservasi', 'ACC')->count())
                ->description('Reservasi yang sedang berlangsung')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Laporan Tertunda', Laporan::where('decision', 'Belum Diproses')->count())
                ->description('Laporan yang membutuhkan perhatian')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Fasilitas Tersedia', Fasilitas::where('status', 'Available')->distinct('nama')->count('nama'))
                ->description('Fasilitas yang siap untuk dipesan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}