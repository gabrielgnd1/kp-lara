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
            Stat::make('Total Facilities', Fasilitas::count())
                ->description('Total number of facilities')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            
            Stat::make('Active Reservations', Reservasi::where('status_reservasi', 'ACC')->count())
                ->description('Currently active reservations')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Pending Reports', Laporan::where('decision', 'Belum Diproses')->count())
                ->description('Reports requiring attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Available Facilities', Fasilitas::where('status', 'Available')->count())
                ->description('Facilities ready for booking')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}