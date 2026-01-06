<?php

namespace App\Filament\Admin\Resources\AdminLaporanResource\Pages;

use App\Filament\Admin\Resources\AdminLaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminLaporans extends ListRecords
{
    protected static string $resource = AdminLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Laporan'),
        ];
    }
}