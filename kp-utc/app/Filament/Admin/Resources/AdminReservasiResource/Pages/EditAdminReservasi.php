<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminReservasi extends EditRecord
{
    protected static string $resource = AdminReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}