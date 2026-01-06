<?php

namespace App\Filament\Admin\Resources\MenuMakanResource\Pages;

use App\Filament\Admin\Resources\MenuMakanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuMakan extends ListRecords
{
    protected static string $resource = MenuMakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Menu Makan'),
        ];
    }
}
