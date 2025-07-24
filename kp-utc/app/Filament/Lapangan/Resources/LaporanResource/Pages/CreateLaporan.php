<?php

namespace App\Filament\Lapangan\Resources\LaporanResource\Pages;

use App\Filament\Lapangan\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporan extends CreateRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
