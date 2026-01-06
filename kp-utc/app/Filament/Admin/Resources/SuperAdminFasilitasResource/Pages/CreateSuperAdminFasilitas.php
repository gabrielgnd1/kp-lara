<?php

namespace App\Filament\Admin\Resources\SuperAdminFasilitasResource\Pages;

use App\Filament\Admin\Resources\SuperAdminFasilitasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuperAdminFasilitas extends CreateRecord
{
    protected static string $resource = SuperAdminFasilitasResource::class;

    protected static ?string $title = 'Tambah Fasilitas';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
