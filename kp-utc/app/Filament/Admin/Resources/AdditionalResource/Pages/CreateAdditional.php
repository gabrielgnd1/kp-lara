<?php

namespace App\Filament\Admin\Resources\AdditionalResource\Pages;

use App\Filament\Admin\Resources\AdditionalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdditional extends CreateRecord
{
    protected static string $resource = AdditionalResource::class;

    protected static ?string $title = 'Tambah Fasilitas Tambahan';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
