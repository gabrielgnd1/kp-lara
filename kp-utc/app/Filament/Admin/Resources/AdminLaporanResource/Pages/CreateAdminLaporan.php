<?php

namespace App\Filament\Admin\Resources\AdminLaporanResource\Pages;

use App\Filament\Admin\Resources\AdminLaporanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminLaporan extends CreateRecord
{
    protected static string $resource = AdminLaporanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}