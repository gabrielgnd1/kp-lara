<?php

namespace App\Filament\Admin\Resources\AdditionalResource\Pages;

use App\Filament\Admin\Resources\AdditionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdditional extends EditRecord
{
    protected static string $resource = AdditionalResource::class;

    protected static ?string $title = 'Ubah Fasilitas Tambahan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
