<?php

namespace App\Filament\Admin\Resources\FasilitasResource\Pages;

use App\Filament\Admin\Resources\FasilitasResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListFasilitas extends ListRecords
{
    protected static string $resource = FasilitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}