<?php

namespace App\Filament\Admin\Resources\FasilitasResource\Pages;

use App\Filament\Admin\Resources\FasilitasResource;
use Filament\Resources\Pages\ViewRecord;

class ViewFasilitas extends ViewRecord
{
    protected static string $resource = FasilitasResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
