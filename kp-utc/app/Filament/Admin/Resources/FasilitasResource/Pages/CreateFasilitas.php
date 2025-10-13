<?php

namespace App\Filament\Admin\Resources\FasilitasResource\Pages;

use App\Filament\Admin\Resources\FasilitasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFasilitas extends CreateRecord
{
    protected static string $resource = FasilitasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}