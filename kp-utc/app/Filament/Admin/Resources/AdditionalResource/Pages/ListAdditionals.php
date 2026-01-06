<?php

namespace App\Filament\Admin\Resources\AdditionalResource\Pages;

use App\Filament\Admin\Resources\AdditionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdditionals extends ListRecords
{
    protected static string $resource = AdditionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Fasilitas Tambahan'),
        ];
    }
}
