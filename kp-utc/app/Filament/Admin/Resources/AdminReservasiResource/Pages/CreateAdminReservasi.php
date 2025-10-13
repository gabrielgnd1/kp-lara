<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminReservasi extends CreateRecord
{
    protected static string $resource = AdminReservasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}