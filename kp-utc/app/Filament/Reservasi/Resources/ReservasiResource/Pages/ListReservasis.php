<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservasis extends ListRecords
{
    protected static string $resource = ReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
