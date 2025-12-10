<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminReservasis extends ListRecords
{
    protected static string $resource = AdminReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction removed - Super Admin cannot create reservations
        ];
    }
}